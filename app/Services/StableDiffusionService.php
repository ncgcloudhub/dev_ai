<?php

namespace App\Services;

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

    public function generateImage($prompt, $width = 512, $height = 512, $steps = 20, $samples = 1)
{
    // Prepare the payload according to the API requirements
    $payload = [
        'key' => $this->apiKey,
        'prompt' => $prompt,
        'negative_prompt' => null,
        'width' => (string) $width,
        'height' => (string) $height,
        'samples' => (string) $samples,
        'num_inference_steps' => (string) $steps,
        'seed' => null,
        'guidance_scale' => 7.5,
        'safety_checker' => 'yes',
        'multi_lingual' => 'no',
        'panorama' => 'no',
        'self_attention' => 'no',
        'upscale' => 'no',
        'embeddings_model' => null,
        'webhook' => null,
        'track_id' => null,
    ];

    Log::info('Stable Diffusion Payload:', $payload);

    // Send the request to the Stable Diffusion API
    $response = Http::asMultipart()->withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
        'Accept' => 'image/*'
    ])->post($this->apiUrl, $payload);

    Log::info('API Response Status:', ['status' => $response->status()]);
    Log::info('API Response Body:', ['body' => $response->body()]);

    if ($response->successful()) {
        // Save image to local storage
        Storage::put('public/lighthouse.jpeg', $response->body());

        return response()->json([
            'message' => 'Image generated successfully!',
            'path' => asset('storage/lighthouse.jpeg'),
        ]);
    } else {
        return response()->json([
            'error' => $response->json(),
        ], $response->status());
    }
}


}
