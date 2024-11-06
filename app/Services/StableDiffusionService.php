<?php

namespace App\Services;

use App\Models\StableDiffusionGeneratedImage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StableDiffusionService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.stable_diffusion.api_key');
        $this->apiUrl = config('services.stable_diffusion.api_url');
    }

    public function generateImage($prompt, $imageFormat, $modelVersion)
    {
        // Set up headers and payload similar to your Python request
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'image/*'
        ];

        $data = [
            'prompt' => $prompt,
            'output_format' => $imageFormat, 
            'model' => $modelVersion,
        ];

        // Send the request
        $response = Http::withHeaders($headers)
            ->asMultipart() // Use multipart to handle image data properly
            ->post($this->apiUrl, $data);

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
                Storage::put('public/' . $imageName, $response->body());

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
