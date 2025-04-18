<?php

namespace App\Services;

use App\Models\SiteSettings;
use App\Models\StableDiffusionGeneratedImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;

class StableDiffusionService
{
    protected $apiKey;
    protected $apiUrl;
    protected $siteSettings;

    public function __construct()
    {
        $this->apiKey = config('services.stable_diffusion.api_key');
        $this->apiUrl = config('services.stable_diffusion.api_url');

        $this->siteSettings = SiteSettings::find(1);
    }

    public function generateImage($endpoint, $prompt, $imageFormat, $modelVersion, $mode = 'text-to-image', $baseImage = null, $strength = null)
    {
        // Set up headers and payload
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'image/*'
        ];
    
        // Prepare the data payload
        $data = [
            'prompt' => $prompt,
            'output_format' => $imageFormat,
            'model' => $modelVersion,
            'mode' => $mode,
        ];
    
        // Include base image and strength for image-to-image mode

        // if ($mode === 'image-to-image') {
        //     if ($baseImage) {
        //         $data['baseImage'] = $baseImage;
        //     }
        //     if ($strength !== null) {
        //         $data['strength'] = $strength;
        //     }
        // }
    
        //     Log::info('Service Data:', $data);
        
        if ($mode === 'image-to-image') {
            if (!$baseImage) {
                throw new \Exception('Base image is required for image-to-image generation.');
            }
            if (!$strength) {
                throw new \Exception('Strength is required for image-to-image generation.');
            }
    
            $data['strength'] = $strength;
    
            // Add the base image to the request
            $response = Http::withHeaders($headers)
                ->asMultipart()
                ->attach('image', file_get_contents($baseImage->getRealPath()), $baseImage->getClientOriginalName())
                ->post($endpoint, $data);
        } else {
            // For text-to-image
            $response = Http::withHeaders($headers)
                ->asMultipart()
                ->post($endpoint, $data);
        }
    
        // Log the response
        Log::info('Stable Diffusion API Response:', [
            'status' => $response->status(),
            'response_preview' => substr($response->body(), 0, 100) // Log the first 100 characters
        ]);
    
        // Extract the seed from headers if it exists
        $seedId = $response->header('seed') ?? null;
    
        if ($response->status() == 200) {
            // Generate the image name in the desired format
            $imageName = time() . '-' . uniqid() . '.webp';
            $imagePath = $imageName;
    
            // Create an instance of Intervention Image from the response content
            $image = Image::make($response->body());
    
            // Define the watermark path
            $watermarkPath = public_path('backend/uploads/site/' . $this->siteSettings->watermark);
            $watermark = Image::make($watermarkPath); // Create an instance of the watermark image
    
            // Apply watermark at the bottom-right corner with 10px offset
            $image->insert($watermark, 'bottom-right', 10, 10);
    
            // Save the watermarked image to a temporary file
            $tempImagePath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
            $image->save($tempImagePath);
    
            // Upload the image to Azure Blob Storage
            $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
            $containerName = config('filesystems.disks.azure.container');
            $blobClient->createBlockBlob($containerName, $imageName, file_get_contents($tempImagePath), new CreateBlockBlobOptions());
    
            // Save the image data to the database
            $imageModel = StableDiffusionGeneratedImage::create([
                'user_id' => auth()->id(),
                'prompt' => $prompt,
                'status' => 'generated',
                'in_frontend' => false,
                'image_url' => $imagePath, // Save only the image name
                'seed' => $seedId,
            ]);
    
            // Clean up the temporary file
            unlink($tempImagePath);
    
            // Construct the full Azure URL for the response
            $imageUrl = config('filesystems.disks.azure.url') 
                . config('filesystems.disks.azure.container') 
                . '/' . $imageName 
                . '?' . config('filesystems.disks.azure.sas_token');
    
            return [
                'image_url' => $imageUrl, // Return the full Azure URL
                'seed' => $seedId,  // Return the seed ID
            ];
        } else {
            return response()->json([
                'error' => $response->json() ?? 'An error occurred during image generation.'
            ], $response->status());
        }
    }

}
