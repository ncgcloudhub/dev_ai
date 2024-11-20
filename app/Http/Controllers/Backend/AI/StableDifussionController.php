<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\StableDiffusionGeneratedImage;
use App\Models\StableDiffusionImageLike;
use App\Services\StableDiffusionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; 

class StableDifussionController extends Controller
{
    protected $stableDiffusionService;

    public function __construct(StableDiffusionService $stableDiffusionService)
    {
        $this->stableDiffusionService = $stableDiffusionService;
    }

    public function index()
    {
        $images = StableDiffusionGeneratedImage::where('user_id', auth()->id())->orderBy('id', 'desc')->get();
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

public function incrementDownloadCount($id)
{
    $image = StableDiffusionGeneratedImage::findOrFail($id);
    $image->increment('downloads');
    
    return response()->json(['status' => 'success']);
}


// STABLE VIDEO

public function Videoindex()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.video.stable_video',compact('apiKey'));
}


public function generateVideo(Request $request)
{
    $imagePath = $request->file('image')->getRealPath(); // Uploaded image
    $apiUrl = "https://api.stability.ai/v2beta/image-to-video";
    $configapiKey = config('services.stable_diffusion.api_key'); // API key from config

    // Send request to generate the video
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $configapiKey,
    ])->attach(
        'image', file_get_contents($imagePath), $request->file('image')->getClientOriginalName()
    )->post($apiUrl, [
        'seed' => $request->input('seed', 0),
        'cfg_scale' => $request->input('cfg_scale', 1.8),
        'motion_bucket_id' => $request->input('motion_bucket_id', 127),
    ]);

    // Check for successful response
    if ($response->successful()) {
        $generationId = $response->json()['id'];

        Log::info('Video Generation Successful', [
            'generation_id' => $generationId,
        ]);
        // Return the generation ID in the response
        return response()->json(['id' => $generationId], 200);
    } else {

        Log::error('Video Generation Failed', [
            'error' => $response->json(),
            'status_code' => $response->status(),
        ]);
        return response()->json(['error' => $response->json()], $response->status());
    }
}


public function getVideoResult($generationId)
{
    $apiUrl = "https://api.stability.ai/v2beta/image-to-video/result/{$generationId}";
    $configapiKey = config('services.stable_diffusion.api_key'); // API key from config

    // Send GET request to fetch the video result
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $configapiKey,
        'Accept' => 'video/*',  // Accept video content type
    ])->get($apiUrl);

    // Check if video is in-progress
    if ($response->status() == 202) {
        return response()->json([
            'message' => 'Video generation in progress, try again in a few seconds.',
            'status' => 202,
        ]);
    }

    // Check if video generation is complete
    if ($response->status() == 200) {
        // Save the video file
        $videoPath = 'videos/video_' . time() . '.mp4';
        Storage::put($videoPath, $response->body());

        // Return the video URL
        return response()->json([
            'message' => 'Video generation complete!',
            'video_url' => asset('storage/' . $videoPath), // URL to access the video
        ]);
    }

    // Handle error response
    return response()->json([
        'error' => $response->json(),
        'status' => $response->status(),
    ]);
}






    

}
