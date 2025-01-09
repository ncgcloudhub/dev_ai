<?php

namespace App\Services;

use App\Models\SiteSettings;
use App\Models\StableDiffusionGeneratedImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
        // Set up headers and payload similar to your Python request
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

    // Include base image and strength only if mode is 'image-to-image'
    if ($mode === 'image-to-image') {
        if ($baseImage) {
            $data['baseImage'] = $baseImage;
        }
        if ($strength !== null) {
            $data['strength'] = $strength;
        }
    }

        Log::info('Service Data:', $data);

        
      // Add image-to-image specific parameters
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


            Log::info('Stable Diffusion API Response:', [
                'status' => $response->status(),
                'response_preview' => substr($response->body(), 0, 100) // Log the first 100 characters only
            ]);

             // Log the full response headers for inspection
            Log::info('Stable Diffusion API Response Headers:', $response->headers());

            // Extract the seed from headers if it exists
            $seedId = $response->header('seed') ?? null;

            if ($response->status() == 200) {
                // Use selected output format as file extension
                $extension = strtolower($imageFormat); // Convert format to lowercase to ensure compatibility
                // Save the binary content as an image
                $imageName = 'user_' . auth()->id() . '_image_' . time() . '.' . $extension;
            
                 // Create an instance of Intervention Image from the response content
                $image = Image::make($response->body());
                
                // Define the watermark path
                $watermarkPath = public_path('backend/uploads/site/' .$this->siteSettings->watermark);
                
                // Adjust path as necessary
                $watermark = Image::make($watermarkPath); // Create an instance of the watermark image

                // Apply watermark at the bottom-right corner with 10px offset
                $image->insert($watermark, 'bottom-right', 10, 10);
                
                // Save the watermarked image to the storage
                $watermarkedImagePath = storage_path('app/public/' . $imageName);
                $image->save($watermarkedImagePath);

                // Save the image data to the database
                StableDiffusionGeneratedImage::create([
                    'user_id' => auth()->id(),
                    'prompt' => $prompt,
                    'status' => 'generated', // Set the status as needed
                    'in_frontend' => false, // Set to true if you want to show in frontend
                    'image_url' => asset('storage/' . $imageName),
                    'seed' => $seedId,  // Store the seed ID if it exists

                ]);
            
                return [
                    'image_url' => asset('storage/' . $imageName), // Return the generated image URL
                    'seed' => $seedId,  // Return the seed ID along with the image URL

                ];
            } else {
                return response()->json([
                    'error' => $response->json() ?? 'An error occurred during image generation.'
                ], $response->status());
            }
            
    }


}
