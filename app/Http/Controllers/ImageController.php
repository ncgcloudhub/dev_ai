<?php

namespace App\Http\Controllers;

use App\Services\StableDiffusionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use App\Models\GeneratedImage;
use App\Models\PackageHistory;
use App\Models\SiteSettings;
use App\Models\StableDiffusionGeneratedImage;
use Illuminate\Support\Facades\Log;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use OpenAI\Laravel\Facades\OpenAI;


class ImageController extends Controller
{
    protected $stableDiffusionService;
    protected $siteSettings;

    public function __construct(StableDiffusionService $stableDiffusionService)
    {
        $this->stableDiffusionService = $stableDiffusionService;
        $this->siteSettings = SiteSettings::find(1);
    }

    public function imageIndex(Request $request)
    {
        $apiKey = config('services.stable_diffusion.api_key');
        $user_id = Auth::id();
        $lastPackageHistory = PackageHistory::where('user_id', $user_id)->latest()->first();
        $lastPackageId = $lastPackageHistory ? $lastPackageHistory->package_id : null;

        $generatedImages = GeneratedImage::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        foreach ($generatedImages as $image) {
            $image->image_url = config('filesystems.disks.azure.url')
                . config('filesystems.disks.azure.container') . '/' . $image->image
                . '?' . config('filesystems.disks.azure.sas_token');
        }

        $dalleImages = ModelsDalleImageGenerate::where('user_id', $user_id)
            ->orderBy('id', 'desc')->get();
        foreach ($dalleImages as $image) {
            $image->image_url = config('filesystems.disks.azure.url')
                . config('filesystems.disks.azure.container') . '/' . $image->image
                . '?' . config('filesystems.disks.azure.sas_token');
        }

        $merged = collect();
        foreach ($dalleImages as $image) {
            $merged->push($image);
        }
        foreach ($generatedImages as $image) {
            $merged->push($image);
        }

        $merged = $merged->sortByDesc('id')->values();

        $perPage = $request->get('perPage', 24); // default is 24
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $merged->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $images = new LengthAwarePaginator($currentItems, $merged->count(), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return view('backend.image_generate.images_sd_d', compact('apiKey', 'lastPackageId', 'images'));
    }

    // Dalle Image Generate
    public function generateImageDalle(Request $request)
    {
        ini_set('max_execution_time', 300);

        $id = Auth::user()->id;
        $creditsLeft = Auth::user()->credits_left;

        $apiKey = config('app.openai_api_key');
        $size = '1024x1024';
        $style = 'vivid';
        $userStyles = $request->input('style', []);
        $quality = 'standard';
        $n = 1;

        // Convert array to string
        $userStyleImplode = implode(', ', $userStyles);

        $response = null;

        // Handle image-based generation
        $prompt = null;

        // Image-to-Image logic
        if ($request->hasFile('custom_image') && $request->file('custom_image')->isValid()) {
            // Get and encode the image
            $imageFile = $request->file('custom_image');
            $base64Image = base64_encode(file_get_contents($imageFile));
            
            // Extract prompt from the image
            $response = $this->callOpenAIImageAPI($base64Image);

            // Decode or access the content as an array
            $responseArray = json_decode(json_encode($response), true);

            if (isset($responseArray['choices'][0]['message']['content'])) {
                $extractedPrompt = $responseArray['choices'][0]['message']['content'];
                $prompt = $extractedPrompt;  // Use the prompt extracted from the image
            } else {
                // If extraction fails, set a default prompt or return error
                Log::error('Failed to extract prompt from image analysis response');
                $prompt = 'Please provide a valid text prompt or an image for generation.';
            }

        } else {
            // Handle user text input
            $prompt = $request->prompt;

            if ($userStyleImplode) {
                $prompt .= ' and the style should be: ' . $userStyleImplode . '. ';
            }
        }

        Log::info('Using prompt: ' . $prompt);  // Log the prompt to verify

        // Handle other parameters
        if ($request->quality) {
            $quality = $request->quality;
        }

        if ($request->image_res) {
            $size = $request->image_res;
        }

        if ($request->no_of_result) {
            $n = $request->no_of_result;
            $n = intval($n);
            if ($n > $creditsLeft) {
                return response()->json(['error' => 'Failed to generate image, insufficient credits'], 500);
            }
        }

        // Ensure prompt is optimized
        $prompt = checkOptimizePrompt($prompt, $request);

        if ($creditsLeft >= 1) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $prompt, // Use the correct prompt based on the input type
                'size' => $size,
                'style' => $style,
                'quality' => $quality,
                'n' => $n,
            ]);

            Log::info('API Response: ' . $response->body());  // Log the full response for debugging
        } else {
            return response()->json(['error' => 'Failed to generate image, insufficient credits'], 500);
        }

        // Handle the response
        if ($response && $response->successful()) {
            Log::info('Dalle API Response received', ['response' => $response->json()]);

            $responseData = $response->json();

            foreach ($responseData['data'] as $imageData) {
                Log::info('Processing image', ['image_url' => $imageData['url']]);

                $imageDataBinary = file_get_contents($imageData['url']);
                Log::info('Image data loaded from URL', ['url' => $imageData['url']]);

                try {
                    // Create image from blob
                    $sourceImage = Image::make($imageDataBinary);
                    Log::info('Image successfully loaded into memory');
                } catch (\Exception $e) {
                    Log::error('Error loading image from binary', ['exception' => $e->getMessage()]);
                    continue; // Skip this iteration if image loading fails
                }

                // Additional processing, watermark, etc. (unchanged)
                $watermarkPath = public_path('backend/uploads/site/' . $this->siteSettings->watermark); // Path to your watermark image
                    Log::info('Loading watermark', ['watermark_path' => $watermarkPath]);

                    try {
                        $watermark = Image::make($watermarkPath);
                        Log::info('Watermark successfully loaded');
                    } catch (\Exception $e) {
                        Log::error('Error loading watermark', ['exception' => $e->getMessage()]);
                        continue; // Skip if watermark loading fails
                    }

                    // Insert watermark at bottom-right corner with a 10px offset
                    $sourceImage->insert($watermark, 'bottom-right', 10, 10);
                    Log::info('Watermark inserted into image');

                    // Resize and compress the image
                    $compressedImage = $sourceImage->encode('webp', 80); // Compress and output as webp format
                    Log::info('Image compressed to webp format', ['quality' => 80]);

                    // Save the compressed watermarked image to Azure Blob Storage
                    try {
                        $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
                        $source = 'dalle';
                        $userName = Str::slug(auth()->user()->name);
                        $timestamp = now()->format('YmdHis');
                        $imageName = "generated-images/{$source}-{$userName}-{$timestamp}.webp";

                        $containerName = config('filesystems.disks.azure.container');
                        $blobClient->createBlockBlob($containerName, $imageName, $compressedImage->__toString(), new CreateBlockBlobOptions());

                        Log::info('Image successfully uploaded to Azure Blob Storage', ['image_name' => $imageName, 'container' => $containerName]);
                    } catch (\Exception $e) {
                        Log::error('Error uploading image to Azure Blob Storage', ['exception' => $e->getMessage()]);
                        continue; // Skip if upload fails
                    }

                    $imagePath = $imageName;

                    // Save image information to the database
                    try {
                        $imageModel = new GeneratedImage();
                        $imageModel->image = $imagePath;
                        $imageModel->user_id = auth()->user()->id;
                        $imageModel->prompt = $prompt;
                        $imageModel->model = 'dall-e-3';
                        $imageModel->resolution = $size;
                        $imageModel->style = $userStyleImplode;
                        $imageModel->save();
                        
                        Log::info('Image information successfully saved to the database', ['image_model' => $imageModel]);
                    } catch (\Exception $e) {
                        Log::error('Error saving image information to the database', ['exception' => $e->getMessage()]);
                    }
                }
            // Deduct credits and update the user information
            deductUserTokensAndCredits(0, calculateCredits($size, $quality));
            logActivity('Image Generation', 'Image generated using ' . ($request->dall_e_2 ? 'DALL-E 2' : 'DALL-E 3'));

                // Return successful response
                return response()->json([
                    'data' => $responseData['data'],
                    'prompt' => $prompt, // Include the final used prompt
]);

           
        } else {
            Log::error('Failed to generate image', ['error' => $response->body()]);
            return response()->json(['error' => 'Failed to generate image'], 500);
        }
    }

    // Call OpenAI to analyze the image and extract a prompt
    private function callOpenAIImageAPI($base64Image)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => 'What’s in this image?'],
                        ['type' => 'image_url', 'image_url' => [
                            'url' => 'data:image/jpeg;base64,' . $base64Image,
                        ]],
                    ],
                ],
            ],
            'max_completion_tokens' => 300,
        ]);
    
        return $response;
    }
    
}
