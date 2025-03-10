<?php

namespace App\Http\Controllers;

use App\Services\StableDiffusionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use App\Models\SiteSettings;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;

class ImageController extends Controller
{
    protected $stableDiffusionService;
    protected $siteSettings;


    public function __construct(StableDiffusionService $stableDiffusionService)
    {
        $this->stableDiffusionService = $stableDiffusionService;
        $this->siteSettings = SiteSettings::find(1);
    }
    
    public function imageIndex()
    {
        $apiKey = config('services.stable_diffusion.api_key');
        return view('backend.image_generate.images_sd_d',compact('apiKey'));
    }

    public function generateImageSdDalle(Request $request)
    {
        $id = Auth::user()->id;
        $creditsLeft = Auth::user()->credits_left;
    
        $prompt = $request->input('prompt');
        $style = $request->input('hiddenStyle');
        $imageFormat = $request->input('hiddenImageFormat') ?? 'jpeg';
        $modelVersion = $request->input('hiddenModelVersion');
        $quality = $request->input('hiddenQuality') ?? 'standard';
        $resolution = $request->input('hiddenResolution') ?? '1024x1024';
        $styleCommon = $request->input('hiddenstyleCommon');
        $noOfResults = intval($request->input('no_of_result', 1));
    
        if ($noOfResults > $creditsLeft) {
            return response()->json(['error' => 'Not enough credits'], 500);
        }
    
        // Optimize Prompt if needed
        if ($request->input('hiddenPromptOptimize') == '1') {
            $prompt = rephrasePrompt($prompt);
        }
    
        $response = null;
    
        if (strpos($modelVersion, 'dalle') !== false) {
            Log::info("Dalle Image Inside before");
            $response = $this->generateDalleImage($prompt, $resolution, $quality, $noOfResults);
            Log::info("Dalle Image Inside After");
        } else {
            $mode = $request->input('mode') ?? 'text-to-image';
            $baseImage = $request->file('base_image');
            $strength = $request->input('strength');
    
            $response = $this->generateStableDiffusionImage($prompt, $style, $imageFormat, $modelVersion, $mode, $baseImage, $strength);
        }
    
        return $this->handleApiResponse($response, $prompt, $resolution, $styleCommon);
    }

    // Helper function to generate DALL-E image
    private function generateDalleImage($prompt, $resolution, $quality, $noOfResults)
    {
        $apiKey = config('app.openai_api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/images/generations', [
            'model' => 'dall-e-3',
            'prompt' => $prompt,
            'size' => $resolution,
            'quality' => $quality,
            'n' => $noOfResults,
        ]);
        Log::info('Dalle Image Response: ', ['response' => $response->json()]);
        return $response;
       
    }

    // Helper function to generate Stable Diffusion image
    private function generateStableDiffusionImage($prompt, $style, $imageFormat, $modelVersion, $mode, $baseImage, $strength)
    {
        $endpoints = [
            'sd-ultra' => env('STABLE_DIFFUSION_API_URL_ULTRA', 'https://api.stability.ai/v2beta/stable-image/generate/ultra'),
            'sd-core' => env('STABLE_DIFFUSION_API_URL_CORE', 'https://api.stability.ai/v2beta/stable-image/generate/core'),
            'default' => env('STABLE_DIFFUSION_API_URL', 'https://api.stability.ai/v2beta/stable-image/generate/sd3'),
        ];

        $endpoint = $endpoints[$modelVersion] ?? $endpoints['default'];

        if ($style) {
            $prompt .= " in " . $style;
        }

        return $this->stableDiffusionService->generateImage(
            $endpoint,
            $prompt,
            $imageFormat,
            $modelVersion,
            $mode,
            $baseImage,
            $strength
        );
    }

    private function handleApiResponse($response, $prompt, $resolution, $style)
    {
        if (!$response || !$response->successful()) {
            logActivity('Image Generation Error', 'Failed to generate image');
            return response()->json(['error' => 'Failed to generate image'], 500);
        }
    
        $responseData = $response->json();
        $savedImages = [];
    
        foreach ($responseData['data'] as $imageData) {
            $imageUrl = $imageData['url'] ?? null;
            if (!$imageUrl) continue;
    
            $compressedImage = $this->applyWatermarkAndCompress($imageUrl);
            $imagePath = $this->uploadToAzure($compressedImage);
    
            // Save image in the database
            $imageModel = new ModelsDalleImageGenerate;
            $imageModel->image = $imagePath;
            $imageModel->user_id = auth()->user()->id;
            $imageModel->status = 'inactive';
            $imageModel->prompt = $prompt;
            $imageModel->resolution = $resolution;
            $imageModel->style = $style;
            $imageModel->save();
    
            // Store the saved image URL for response
            $savedImages[] = $imagePath;
            Log::info("Saved Image Path: " . $imagePath);
        }
    
        Log::info("All Saved Images: ", $savedImages);

        deductUserTokensAndCredits(0, calculateCredits($resolution, 'standard'));
    
        // Return the saved image URLs in the response
        return response()->json([
            'message' => 'Image generated successfully',
            'images' => $savedImages
        ]);
    }

    private function applyWatermarkAndCompress($imageDataBinary)
    {
        try {
            // Create image from blob
            $sourceImage = Image::make($imageDataBinary);
            Log::info('Image successfully loaded into memory');

            // Load watermark
            $watermarkPath = public_path('backend/uploads/site/' . $this->siteSettings->watermark); // Path to watermark
            Log::info('Loading watermark', ['watermark_path' => $watermarkPath]);

            $watermark = Image::make($watermarkPath);
            Log::info('Watermark successfully loaded');

            // Insert watermark
            $sourceImage->insert($watermark, 'bottom-right', 10, 10);
            Log::info('Watermark inserted into image');

            // Resize and compress the image to WebP format
            $compressedImage = $sourceImage->encode('webp', 80);
            Log::info('Image compressed to webp format', ['quality' => 80]);

            return $compressedImage;
        } catch (\Exception $e) {
            Log::error('Error processing image', ['exception' => $e->getMessage()]);
            return null;
        }
    }

    private function uploadToAzure($compressedImage)
    {
        try {
            $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
            $containerName = config('filesystems.disks.azure.container');
            $imageName = time() . '-' . uniqid() . '.webp';

            $blobClient->createBlockBlob($containerName, $imageName, $compressedImage->__toString(), new CreateBlockBlobOptions());

            Log::info('Image uploaded to Azure', ['image_name' => $imageName]);
            return $imageName;
        } catch (\Exception $e) {
            Log::error('Azure upload failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
        



    
}
