<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\StableDiffusionGeneratedImage;
use App\Models\StableDiffusionImageLike;
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
    $optimizePrompt = $request->input('hiddenPromptOptimize') ?? '0';

    if ($style) {
        $prompt .= " in " . $style;  // Example: "coffee in Watercolor"
    }

    if($optimizePrompt == '1'){
        $rephrasedPrompt = rephrasePrompt($prompt);
    }else {
        // If not optimized, use the original prompt
        $rephrasedPrompt = $prompt;
    }
 
    // Call the service to generate the image
    $result = $this->stableDiffusionService->generateImage($rephrasedPrompt, $imageFormat, $modelVersion);

    // Return the response as JSON
    return response()->json([
        'image_url' => $result['image_url'] ?? null,
        'image_base64' => $result['image_base64'] ?? null,
        'prompt' => $rephrasedPrompt,
    ]);
}


public function likeImage(Request $request)
{
    $imageId = $request->input('image_id');
    $user = auth()->user();

    $like = StableDiffusionImageLike::where('user_id', $user->id)->where('image_id', $imageId)->first();

    if ($like) {
        $like->delete(); // Unlike the image
        $status = 'unliked';
    } else {
        StableDiffusionImageLike::create([
            'user_id' => $user->id,
            'image_id' => $imageId
        ]);
        $status = 'liked';
    }

    $likesCount = StableDiffusionImageLike::where('image_id', $imageId)->count();

    return response()->json([
        'status' => $status,
        'likes_count' => $likesCount
    ]);
}

    

}
