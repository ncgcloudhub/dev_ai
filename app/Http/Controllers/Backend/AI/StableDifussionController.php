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
use Intervention\Image\Facades\Image;

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
        'Accept' => 'application/json',  // Accept JSON response
    ])->get($apiUrl);

    // Check if video generation is in-progress (status: 202)
    if ($response->status() == 202) {
        return response()->json([
            'message' => 'Video generation in progress, try again in a few seconds.',
            'status' => 202,
            'generation_id' => $generationId,  // Return the generation ID for polling
        ]);
    }

    // Check if video generation is complete (status: 200)
    if ($response->status() == 200) {
        $videoData = $response->json();
        
        // Base64-encoded video data
        $videoBase64 = $videoData['video'];

        // Decode base64 video and save to storage
        $videoPath = 'videos/video_' . time() . '.mp4';
        Storage::put($videoPath, base64_decode($videoBase64));  // Save decoded video to disk

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

// TEXT TO VIDEO 
public function TextVideoindex()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.video.stable_text_video',compact('apiKey'));
}

public function generateImageToVideo(Request $request)
{

    ini_set('max_execution_time', 300);

    // Step 1: Validate the input
    Log::info('Step 1: Validation started.');
    $request->validate([
        'prompt' => 'required|string',
        'seed' => 'nullable|integer',
        'cfg_scale' => 'nullable|numeric',
        'motion_bucket_id' => 'nullable|integer',
    ]);
    Log::info('Step 1: Validation completed.');

    // Step 2: Generate Image
    Log::info('Step 2: Image generation started.');

    $prompt = $request->input('prompt');
    $style = $request->input('hiddenStyle', null);
    $imageFormat = 'jpeg'; // Default image format
    $modelVersion = 'sd3.5-large'; // Default model version

    if ($style) {
        $prompt .= " in " . $style;
    }

    Log::info('Prompt: ' . $prompt);

    //Call the service to generate the image
    $imageResult = $this->stableDiffusionService->generateImage($prompt, $imageFormat, $modelVersion);

    Log::info('Step 2: Image generation API response.', ['response' => $imageResult]);

    if (empty($imageResult['image_url'])) {
        Log::error('Step 2: Image generation failed. No image URL returned.');
        return response()->json(['error' => 'Image generation failed'], 500);
    }

    $imageUrl = $imageResult['image_url']; // Get the image URL
    Log::info('Step 2: Image generated successfully.', ['image_url' => $imageUrl]);

    try {
        // Step 2.1: Load the image from the local file path
        Log::info('Step 2.1: Loading the image from URL.', ['image_url' => $imageUrl]);
        $fileName = basename($imageUrl);

        // Build the local file path
        $filePath = storage_path('app/public/' . $fileName);
    
        // Check if the file exists
        if (!file_exists($filePath)) {
            Log::error('Step 2.1: File not found in storage.', ['file_path' => $filePath]);
            return response()->json(['error' => 'File not found in storage.'], 404);
        }
    
        // Load the image using Intervention
        $image = Image::make($filePath);
    
        Log::info('Step 2.1: Image loaded successfully.', ['file_path' => $filePath]);

    // Step 2.2: Resize the image to 768x768
    Log::info('Step 2.2: Resizing the image to 768x768.');
    $image->resize(768, 768);
    Log::info('Step 2.2: Image resized successfully.');

    // Step 3: Save the resized image locally
    $resizedImagePath = storage_path('app/public/resized_image_' . time() . '.jpg');
    Log::info('Step 3: Saving the resized image.', ['path' => $resizedImagePath]);
    $image->save($resizedImagePath);
    Log::info('Step 3: Resized image saved successfully.', ['path' => $resizedImagePath]);
    
    } catch (\Exception $e) {
        Log::error('Step 2.1: Image processing failed.', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Image processing failed'], 500);
    } 
   
    // Step 3: Use the Generated Image to Create a Video
    Log::info('Step 3: Video generation started.');

    $apiUrl = "https://api.stability.ai/v2beta/image-to-video";
    $configapiKey = config('services.stable_diffusion.api_key');

    Log::info('Sending video generation request to API.', ['api_url' => $apiUrl]);

    // Send the request to generate the video
    $videoResponse = Http::withHeaders([
        'Authorization' => 'Bearer ' . $configapiKey,
    ])->attach('image', fopen($resizedImagePath, 'r'), basename($resizedImagePath))->post($apiUrl, [
        'image_url' => $imageUrl,
        'seed' => $request->input('seed', 0),
        'cfg_scale' => $request->input('cfg_scale', 1.8),
        'motion_bucket_id' => $request->input('motion_bucket_id', 127),
    ]);

    Log::info('Step 3: Video generation API response.', ['response' => $videoResponse->json()]);

    if (!$videoResponse->successful()) {
        Log::error('Step 3: Video generation failed.', ['details' => $videoResponse->json()]);
        return response()->json(['error' => 'Video generation failed', 'details' => $videoResponse->json()], $videoResponse->status());
    }

    $generationId = $videoResponse->json()['id'];
    Log::info('Step 3: Video generation successful.', ['generation_id' => $generationId]);

    // Step 4: Return the generation ID and image URL for polling
    Log::info('Step 4: Returning response with generation ID and image URL.');
    return response()->json([
        'message' => 'Video generation started',
        'image_url' => $imageUrl,
        'generation_id' => $generationId,
    ]);
}


public function testResize(Request $request)
{
    // Image URL to be resized
    $imageUrl = 'https://media.licdn.com/dms/image/v2/D4D12AQHVMcx-ckRzTQ/article-cover_image-shrink_600_2000/article-cover_image-shrink_600_2000/0/1693712101604?e=2147483647&v=beta&t=x2x4Klr6G4MotvdA5Vknr0huNRVJi0dNJ4ojugImG-Q'; // Example placeholder image
    
    try {
        // Step 1: Download the image from the URL
        $image = Image::make($imageUrl);

        // Step 2: Resize the image to 768x768
        $image->resize(768, 768);

        // Step 3: Save the resized image locally (in storage/app/public)
        $imagePath = storage_path('app/public/resized_image.jpg');
        $image->save($imagePath);

        Log::info('Image resized successfully and saved to ' . $imagePath);

        // Return the resized image URL
        return response()->json([
            'message' => 'Image resized successfully',
            'image_url' => asset('storage/resized_image.jpg'),
        ]);
    } catch (\Exception $e) {
        Log::error('Image resize failed: ' . $e->getMessage());
        return response()->json(['error' => 'Image resize failed'], 500);
    }
}










    

}
