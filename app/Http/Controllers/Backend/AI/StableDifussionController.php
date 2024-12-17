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
use Illuminate\Support\Str;

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

    // Set the appropriate endpoint
    $endpoints = [
        'sd-ultra' => env('STABLE_DIFFUSION_API_URL_ULTRA', 'https://api.stability.ai/v2beta/stable-image/generate/ultra'),
        'sd-core' => env('STABLE_DIFFUSION_API_URL_CORE', 'https://api.stability.ai/v2beta/stable-image/generate/core'),
        'default' => env('STABLE_DIFFUSION_API_URL', 'https://api.stability.ai/v2beta/stable-image/generate/sd3'),
    ];

    $endpoint = $endpoints['default']; // Default to SD3 endpoint

    if (array_key_exists($modelVersion, $endpoints)) {
        $endpoint = $endpoints[$modelVersion];
    }

    // Log the selected model and endpoint
    Log::info('Selected Model Version:', ['modelVersion' => $modelVersion]);
    Log::info('Resolved Endpoint:', ['endpoint' => $endpoint]);

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
    $result = $this->stableDiffusionService->generateImage($endpoint, $rephrasedPrompt, $imageFormat, $modelVersion);

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


     // Set the appropriate endpoint
     $endpoints = [
        'sd-ultra' => env('STABLE_DIFFUSION_API_URL_ULTRA', 'https://api.stability.ai/v2beta/stable-image/generate/ultra'),
        'sd-core' => env('STABLE_DIFFUSION_API_URL_CORE', 'https://api.stability.ai/v2beta/stable-image/generate/core'),
        'default' => env('STABLE_DIFFUSION_API_URL', 'https://api.stability.ai/v2beta/stable-image/generate/sd3'),
    ];

    $endpoint = $endpoints['default']; // Default to SD3 endpoint

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
    $imageResult = $this->stableDiffusionService->generateImage($endpoint, $prompt, $imageFormat, $modelVersion);

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


// Upscale
public function UpscaleForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.video.stable_upscale',compact('apiKey'));
}

public function upscale(Request $request)
{
    Log::info('Upscale Request Data:', $request->all());

    $request->validate([
        'image' => 'required|image', // Validate that an image file is provided
        'prompt' => 'nullable|string', // Optional prompt
        'output_format' => 'nullable|string', // Output format
    ]);

    $image = $request->file('image');
    $prompt = $request->input('prompt') ?? '';
    $outputFormat = $request->input('output_format') ?? 'webp';
    $upscaleType = $request->input('upscale_type'); 
    $configapiKey = config('services.stable_diffusion.api_key');

    try {
        // Stability AI Upscale API endpoint
        $url = $upscaleType == 'fast' 
        ? "https://api.stability.ai/v2beta/stable-image/upscale/fast"
        : "https://api.stability.ai/v2beta/stable-image/upscale/conservative";

        // Prepare the image file
        $filePath = $image->getRealPath();

        $data = [
            'output_format' => $outputFormat,
        ];

        if ($upscaleType === 'conservative') {
            $data['prompt'] = $prompt;
        }

        // Make the HTTP request using Laravel's HTTP Client
        $response = Http::timeout(60) // Set timeout to 60 seconds
        ->withHeaders([
            'Authorization' => 'Bearer ' . $configapiKey,
            'accept' => 'image/*',
        ])->attach(
            'image', file_get_contents($filePath), $image->getClientOriginalName()
        )->asMultipart()->post($url, $data);

         // Log the response details
        Log::info('Received response from Stability AI Upscale API.', [
            'status_code' => $response->status(),
            'response_body' => $response->body(),
        ]);

        if ($response->successful()) {
            // Save the upscaled image
            $fileName = 'upscaled_' . $image->getClientOriginalName();
            $outputPath = 'images/upscaled/' . $fileName;
            Storage::disk('public')->put($outputPath, $response->body());

            return response()->json([
                'status' => 'success',
                'message' => 'Image upscaled successfully.',
                'upscaled_image_url' => asset('storage/' . $outputPath),
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $response->json('error') ?? 'An error occurred while upscaling the image.',
            ], $response->status());
        }
    } catch (\Exception $e) {
        Log::error('Upscale Error: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred during the upscale process.',
        ], 500);
    }
}

// STABLE EDIT

public function EditForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_edit.edit_form',compact('apiKey'));
}

public function editBackground(Request $request)
{
    
    // Log request data
    Log::info('Edit Background Request Data:', $request->all());

    // Validate input
    $request->validate([
        'subject_image' => 'required|file|mimes:jpeg,png,jpg',
        'background_prompt' => 'required|string',
        'output_format' => 'required|string|in:webp,jpeg,png',
    ]);

    // Extract inputs
    $subjectImage = $request->file('subject_image');
    $backgroundPrompt = $request->input('background_prompt');
    $outputFormat = $request->input('output_format');
    $configapiKey = config('services.stable_diffusion.api_key');

    // API endpoint and key
    $url = "https://api.stability.ai/v2beta/stable-image/edit/replace-background-and-relight";

    try {
        // Prepare and log API request
        Log::info('Preparing to call Stability AI Replace Background API.', [
            'url' => $url,
            'background_prompt' => $backgroundPrompt,
            'output_format' => $outputFormat,
        ]);

        // Send API request
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $configapiKey,
            'accept' => 'image/*',
        ])->attach(
            'subject_image',
            file_get_contents($subjectImage->getRealPath()),
            $subjectImage->getClientOriginalName()
        )->asMultipart()->post($url, [
            'background_prompt' => $backgroundPrompt,
            'output_format' => $outputFormat,
        ]);

        // Log response
        if ($response->successful()) {
            Log::info('Received successful response from Stability AI API.', [
                'status' => $response->status(),
                'id' => $response->json()['id'] ?? 'N/A',
            ]);

            // $imageUrl = "data:image/{$outputFormat};base64," . base64_encode($response->body());

            // Return response
            return response()->json([
                'status' => 'success',
                'generation_id' =>  $response->json()['id'] ?? 'N/A',
            ]);
        } else {
            Log::error('Failed response from Stability AI API.', [
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process the image.',
            ], 500);
        }
    } catch (\Exception $e) {
        // Log exception
        Log::error('Exception occurred during Stability AI Replace Background API call.', [
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred during the image processing.',
        ], 500);
    }
}

public function checkGenerationStatus(Request $request)
    {
        // Validate the request
        $request->validate([
            'generation_id' => 'required|string',
        ]);

        $generationId = $request->input('generation_id');
        $configapiKey = config('services.stable_diffusion.api_key');

        Log::info('inside check generation function 496' . $generationId);

        // Prepare the URL for the API request
        $url = "https://api.stability.ai/v2beta/results/{$generationId}";

        try {
            // Log the request to monitor progress
            Log::info('Checking generation status for generation ID: ' . $generationId);

            // Make the GET request to check the status of the generation
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $configapiKey,
                'Accept' => 'application/json' ,  // Or 'application/json' for base64 JSON
            ])->get($url);

    // Log the full response for debugging purposes
    Log::debug('Response status code', ['status_code' => $response->status()]);
    Log::debug('Response body', ['body' => $response->body()]);

    // Handle different status codes
    if ($response->status() === 202) {
        Log::info('Generation is still in progress', ['generation_id' => $generationId]);

        return response()->json([
            'status' => 'in-progress',
            'message' => 'Generation is still in progress.',
        ], 200);
    } elseif ($response->status() === 200) {
        $responseData = $response->json();

        // Safely extract keys
        $result = $responseData['result'] ?? null;
        $seed = $responseData['seed'] ?? null;

        if ($result && $seed) {
            Log::info('Generation completed successfully', ['seed' => $seed]);

            return response()->json([
                'status' => 'success',
                'seed' => $seed, // Include the seed in the response
                'image_url' => 'data:image/webp;base64,' . $result,
            ]);
        } else {
            Log::warning('Expected keys missing in completed generation response', ['response' => $responseData]);

            return response()->json([
                'status' => 'error',
                'message' => 'Generation completed, but result or seed is missing.',
            ], 500);
        }
    } else {
        // Handle unexpected status codes
        Log::error('Unexpected status code from API', [
            'status_code' => $response->status(),
            'body' => $response->body(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'Unexpected response from the server.',
        ], 500);
    }

         
        } catch (\Exception $e) {
            // Catch any exception that occurs during the request
            Log::error('Error occurred while checking generation status.', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while checking the generation status.',
            ], 500);
        }
    }


// Search and Recolor

public function WithoutAsyncEditForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_edit.edit_without_async_form',compact('apiKey'));
}

public function WithoutAsyncEdit(Request $request)
{
    
    // Log request data
    Log::info('Edit Background Request Data:', $request->all());

    // Validate input
    $request->validate([
        'subject_image' => 'required|file|mimes:jpeg,png,jpg',
        'prompt' => 'required|string',
        'select_prompt' => 'required|string',
        'output_format' => 'required|string|in:webp,jpeg,png',
    ]);

    // Extract inputs
    $subjectImage = $request->file('subject_image');
    $prompt = $request->input('prompt');
    $selectPrompt = $request->input('select_prompt');
    $outputFormat = $request->input('output_format');
    $configapiKey = config('services.stable_diffusion.api_key');

    // API endpoint and key
    $url = "https://api.stability.ai/v2beta/stable-image/edit/search-and-recolor";

    try {

              // Send API request
              $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $configapiKey,
                'accept' => 'image/*',  // Requesting image response
            ])->timeout(60)->attach(
                'image',
                file_get_contents($subjectImage->getRealPath()),
                $subjectImage->getClientOriginalName()
            )->asMultipart()->post($url, [
                'prompt' => $prompt,
                'select_prompt' => $selectPrompt,
                'output_format' => $outputFormat,
            ]);
    
            Log::info('Response from API', [
                'status_code' => $response->status(),
                'response_body' => $response->body(),
            ]);

        // Log response
        if ($response->successful()) {
         
            // Assuming the response body contains the raw image data
            $responseBody = $response->body();

            $filename = Str::random(10) . '.' . $outputFormat;

            // Save the image to the public/images directory
            Storage::disk('public')->put("images/{$filename}", $responseBody);

            // Convert the raw binary image data to Base64
            $imageData = base64_encode($responseBody);

            // Return the response as JSON with the Base64 encoded image
            return response()->json([
                'status' => 'success',
                'image_data' => $imageData
            ]);
        } else {
            Log::error('Failed response from Stability AI API.', [
                'status' => $response->status(),
                'response_body' => $response->body(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process the image.',
            ], 500);
        }
    } catch (\Exception $e) {
        // Log exception
        Log::error('Exception occurred during Stability AI Replace Background API call.', [
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred during the image processing.',
        ], 500);
    }
}


// STABLE EDIT
public function RemoveBgForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_edit.remove_bg_form',compact('apiKey'));
}

public function editRemoveBackground(Request $request)
    {
        $configapiKey = config('services.stable_diffusion.api_key');
     
        // Validate the form inputs
        $request->validate([
            'subject_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // max 5MB
            'output_format' => 'required|in:webp,png,jpg',
        ]);

        try {
            // Get the uploaded file
            $file = $request->file('subject_image');
            $outputFormat = $request->input('output_format');

            // Call Stability AI API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $configapiKey,
                'accept' => 'image/*',
            ])->attach(
                'image', 
                file_get_contents($file->getRealPath()), 
                $file->getClientOriginalName()
            )->post(
                'https://api.stability.ai/v2beta/stable-image/edit/remove-background', 
                [
                    'output_format' => $outputFormat,
                ]
            );

            if ($response->status() === 200) {
                // Save the generated image locally
                $outputFileName = 'output_' . uniqid() . '.' . $outputFormat;
                $outputFilePath = storage_path('app/public/' . $outputFileName);
                file_put_contents($outputFilePath, $response->body());

                // Return the file path for frontend use
                return response()->json([
                    'success' => true,
                    'image_url' => asset('storage/' . $outputFileName),
                ]);
            } else {
                // Handle API errors
                return response()->json([
                    'success' => false,
                    'message' => $response->json()['message'] ?? 'An error occurred while processing the image.',
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


// Stable Search and Replace
public function SearchReplaceForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_edit.search_and_replace_form',compact('apiKey'));
}

public function searchAndReplace(Request $request)
    {

        $configapiKey = config('services.stable_diffusion.api_key');
    
        // Validate the form inputs
        $request->validate([
            'subject_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // max 5MB
            'prompt' => 'required|string',
            'search_prompt' => 'required|string',
            'output_format' => 'required|in:webp,png,jpg',
        ]);

        try {
            // Get the uploaded file and form data
            $file = $request->file('subject_image');
            $prompt = $request->input('prompt');
            $searchPrompt = $request->input('search_prompt');
            $outputFormat = $request->input('output_format');

            // Call Stability AI API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $configapiKey,
                'accept' => 'image/*',
            ])->attach(
                'image',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post(
                'https://api.stability.ai/v2beta/stable-image/edit/search-and-replace',
                [
                    'prompt' => $prompt,
                    'search_prompt' => $searchPrompt,
                    'output_format' => $outputFormat,
                ]
            );

            Log::info('Received response from Stability AI API', [
                'response' => $response,
            ]);

            if ($response->status() === 200) {
                // Save the generated image locally
                $outputFileName = 'output_' . uniqid() . '.' . $outputFormat;
                $outputFilePath = storage_path('app/public/' . $outputFileName);
                file_put_contents($outputFilePath, $response->body());

                $imageData = $response->body();
                return response($imageData, 200)
                    ->header('Content-Type', 'image/' . $outputFormat);
            } else {
                // Handle API errors
                return response()->json([
                    'success' => false,
                    'message' => $response->json()['message'] ?? 'An error occurred while processing the image.',
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    // Stable Edit Erase

    public function eraseForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_edit.erase_form',compact('apiKey'));
}

    public function erase(Request $request)
{
    $configapiKey = config('services.stable_diffusion.api_key');
  
    $url = 'https://api.stability.ai/v2beta/stable-image/edit/erase';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $configapiKey,
        'accept' => 'image/*',
    ])->attach(
        'image',
        file_get_contents($request->file('image')->getRealPath()),
        $request->file('image')->getClientOriginalName()
    )->attach(
        'mask',
        file_get_contents($request->file('mask')->getRealPath()),
        $request->file('mask')->getClientOriginalName()
    )->post($url, [
        'output_format' => $request->input('output_format', 'webp'),
    ]);

    Log::info('Received response from Stability AI API', [
        'response' => $response,
    ]);


    if ($response->ok()) {
        // Return the binary image data
        return response($response->body(), 200)
            ->header('Content-Type', 'image/webp')
            ->header('Content-Disposition', 'inline');
    }

    // Handle errors
    return response()->json(['error' => $response->json()], $response->status());
}



// Edit Inpaint
public function inpaintForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_edit.inpaint_form',compact('apiKey'));
}

public function inpaint(Request $request)
{
    $configapiKey = config('services.stable_diffusion.api_key');

    $request->validate([
        'image' => 'required|file|mimes:png,jpg,jpeg',
        'mask' => 'nullable|file|mimes:png,jpg,jpeg',
        'prompt' => 'required|string',
        'output_format' => 'required|in:webp,png,jpg',
    ]);

    $image = $request->file('image');
    $mask = $request->file('mask');
    $prompt = $request->input('prompt');
    $outputFormat = $request->input('output_format');

    $apiKey = 'your-api-key-here';

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $configapiKey,
        'Accept' => 'image/*',
    ])->attach(
        'image',
        file_get_contents($image->getRealPath()),
        $image->getClientOriginalName()
    )->attach(
        'mask',
        file_get_contents($mask->getRealPath()),
        $mask->getClientOriginalName()
    )->post(
        'https://api.stability.ai/v2beta/stable-image/edit/inpaint',
        [
            'prompt' => $prompt,
            'output_format' => $outputFormat,
        ]
    );

    if ($response->ok()) {
        return response($response->body(), 200)
            ->header('Content-Type', 'image/' . $outputFormat)
            ->header('Content-Disposition', 'inline');
    } else {
        return response()->json([
            'success' => false,
            'message' => $response->json(),
        ], $response->status());
    }
}


// Edit Outpaint
public function outpaintForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_edit.outpaint_form',compact('apiKey'));
}


public function outpaint(Request $request)
{
    $configapiKey = config('services.stable_diffusion.api_key');

    $request->validate([
        'image' => 'required|file|mimes:png,jpg,jpeg',
        'output_format' => 'required|in:webp,png,jpg',
    ]);

    // Sanitize and validate directional inputs
    $left = intval($request->input('left', 0)); // Default to 0 if not provided
    $right = intval($request->input('right', 0)); // Default to 0 if not provided
    $up = intval($request->input('up', 0)); // Default to 0 if not provided
    $down = intval($request->input('down', 0)); // Default to 0 if not provided

    // Check if all inputs are zero
    if ($left === 0 && $right === 0 && $up === 0 && $down === 0) {
        return response()->json([
            'success' => false,
            'message' => 'At least one of the inputs (left, right, up, or down) is required.',
        ], 422);
    }

    $image = $request->file('image');
    $outputFormat = $request->input('output_format');

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $configapiKey,
        'Accept' => 'image/*',
    ])->attach(
        'image',
        file_get_contents($image->getRealPath()),
        $image->getClientOriginalName()
    )->post(
        'https://api.stability.ai/v2beta/stable-image/edit/outpaint',
        [
            'left' => $left,
            'right' => $right,
            'up' => $up,
            'down' => $down,
            'output_format' => $outputFormat,
        ]
    );

    if ($response->ok()) {
        return response($response->body(), 200)
            ->header('Content-Type', 'image/' . $outputFormat)
            ->header('Content-Disposition', 'inline');
    } else {
        return response()->json([
            'success' => false,
            'message' => $response->json(),
        ], $response->status());
    }
}


// SD Control (Sketch)
public function controlSketchForm()
{
    $apiKey = config('services.stable_diffusion.api_key');
    return view('backend.stable_control.sketch_form',compact('apiKey'));
}

public function controlSketch(Request $request)
{
    $configapiKey = config('services.stable_diffusion.api_key');

    $request->validate([
        'image' => 'required|file|mimes:png,jpg,jpeg',
        'prompt' => 'required|string',
        'control_strength' => 'required|numeric|min:0|max:1',
        'output_format' => 'required|in:webp,png,jpg',
        'control_type' => 'required|in:sketch,structure',
    ]);

    $image = $request->file('image');
    $prompt = $request->input('prompt');
    $controlStrength = $request->input('control_strength');
    $outputFormat = $request->input('output_format');
    $controlType = $request->input('control_type');

    // Dynamically select the endpoint
    $endpoints = [
        'sketch' => 'https://api.stability.ai/v2beta/stable-image/control/sketch',
        'structure' => 'https://api.stability.ai/v2beta/stable-image/control/structure',
    ];

    $apiEndpoint = $endpoints[$controlType] ?? null;

     // Log the selected control type and endpoint
     Log::info('Control type selected:', ['control_type' => $controlType]);
     Log::info('API Endpoint being used:', ['endpoint' => $apiEndpoint]);

    if (!$apiEndpoint) {
        return response()->json(['success' => false, 'message' => 'Invalid control type'], 400);
    }

     // Log the request payload before calling the API
     Log::info('API Request Payload:', [
        'prompt' => $prompt,
        'control_strength' => $controlStrength,
        'output_format' => $outputFormat,
        'image_name' => $image->getClientOriginalName()
    ]);

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $configapiKey,
        'Accept' => 'image/*',
    ])->attach(
        'image',
        file_get_contents($image->getRealPath()),
        $image->getClientOriginalName()
    )->post($apiEndpoint, [
        'prompt' => $prompt,
        'control_strength' => $controlStrength,
        'output_format' => $outputFormat,
    ]);

     // Log the response status and data
     Log::info('API Response Status:', ['status' => $response->status()]);
     Log::debug('API Response Body:', ['body' => $response->body()]);

    if ($response->ok()) {
        return response($response->body(), 200)
            ->header('Content-Type', 'image/' . $outputFormat)
            ->header('Content-Disposition', 'inline');
    } else {
        Log::error('API Request Failed:', ['response' => $response->json()]);
        return response()->json([
            'success' => false,
            'message' => $response->json(),
        ], $response->status());
    }
}



}
