<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\StableDiffusionGeneratedImage;
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
        $images = StableDiffusionGeneratedImage::where('user_id', auth()->id())->get();
        return view('backend.image_generate.stable_diffusion', compact('images'));
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

    Log::info('Request Data:', $request->all());
   
    $request->validate([
        'prompt' => 'required|string',
        'hiddenStyle' => 'nullable|string',
        'hiddenImageFormat' => 'nullable|string',
        'hiddenModelVersion' => 'nullable|string',
    ]);

    $prompt = $request->input('prompt');
    $style = $request->input('hiddenStyle');
    $imageFormat = $request->input('hiddenImageFormat') ?? 'jpeg';
    $modelVersion = $request->input('hiddenModelVersion') ?? 'sd3.5-large';

    if ($style) {
        $prompt .= " in " . $style;  // Example: "coffee in Watercolor"
    }

    $rephrasedPrompt = rephrasePrompt($prompt);

    // Call the service to generate the image
    $result = $this->stableDiffusionService->generateImage($rephrasedPrompt, $imageFormat, $modelVersion);

    // Return the response as JSON
    return response()->json([
        'image_url' => $result['image_url'] ?? null,
        'image_base64' => $result['image_base64'] ?? null,
    ]);
}

    

}
