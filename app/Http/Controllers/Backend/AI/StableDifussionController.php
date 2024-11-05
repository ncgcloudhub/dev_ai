<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Services\StableDiffusionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StableDifussionController extends Controller
{
    protected $stableDiffusionService;

    public function __construct(StableDiffusionService $stableDiffusionService)
    {
        $this->stableDiffusionService = $stableDiffusionService;
    }

    public function index()
    {
       
        return view('backend.image_generate.stable_form');
    }

    // public function generate(Request $request)
    // {
    //     $request->validate([
    //         'prompt' => 'required|string',
    //         'width' => 'nullable|integer',
    //         'height' => 'nullable|integer',
    //         'steps' => 'nullable|integer',
    //     ]);

    //     $prompt = $request->input('prompt');
    //     $width = $request->input('width', 512);
    //     $height = $request->input('height', 512);
    //     $steps = $request->input('steps', 50);

    //     $result = $this->stableDiffusionService->generateImage($prompt, $width, $height, $steps);

    //     if (isset($result['error'])) {
    //         return response()->json(['error' => $result['message']], 500);
    //     }
    //     Log::info('Image Generation Success:', $result);

    //     return response()->json([
    //         'image_url' => $result['image_url'] ?? null,
    //         'image_base64' => $result['image_base64'] ?? null,
    //     ]);
    // }

    public function generate(Request $request)
{
    $request->validate([
        'prompt' => 'required|string',
        'width' => 'nullable|integer',
        'height' => 'nullable|integer',
        'steps' => 'nullable|integer',
        'samples' => 'nullable|integer',
    ]);

    $prompt = $request->input('prompt');
    $width = $request->input('width', 512);
    $height = $request->input('height', 512);
    $steps = $request->input('steps', 20);
    $samples = $request->input('samples', 1);

    // Call the service to generate the image
    $result = $this->stableDiffusionService->generateImage($prompt);

    // Return the response as JSON
    return response()->json([
        'image_url' => $result['image_url'] ?? null,
        'image_base64' => $result['image_base64'] ?? null,
    ]);
}

    

}
