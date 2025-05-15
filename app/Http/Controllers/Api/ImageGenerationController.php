<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use App\Models\User;
use OpenAI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use App\Models\SiteSettings;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use App\Models\EducationTools;
use App\Models\EducationToolsCategory;
use App\Models\GradeClass;
use App\Models\ToolGeneratedContent;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use Parsedown;

class ImageGenerationController extends Controller
{
    protected $siteSettings;

    public function __construct()
    {
        $this->siteSettings = SiteSettings::find(1);
    }

    public function generateImage(Request $request)
    {
        $id = Auth::user()->id;
        $creditsLeft = Auth::user()->credits_left;

        $apiKey = config('app.openai_api_key');
        $size = '1024x1024';
        $style = 'vivid';
        $userStyles = $request->input('style', []);
        $quality = 'standard';
        $n = 1;

        // Convert array to string
        $userStyleImplode = implode(', ', $userStyles);

        $response = null;

        // Handle image-based generation
        $prompt = null;

        if ($request->hasFile('custom_image') && $request->file('custom_image')->isValid()) {
            // Get and encode the image
            $imageFile = $request->file('custom_image');
            $base64Image = base64_encode(file_get_contents($imageFile));
            // Extract prompt from the image
            $response = $this->callOpenAIImageAPI($base64Image);

            // Decode or access the content as an array
            $responseArray = json_decode(json_encode($response), true);

            // Log the response in a way that PHP can handle
            Log::info('Response as array: ' . json_encode($responseArray));

            if (isset($responseArray['choices'][0]['message']['content'])) {
                $extractedPrompt = $responseArray['choices'][0]['message']['content'];
                $prompt = $extractedPrompt;  // Use the prompt extracted from the image

                Log::info('Extracted prompt: ' . $prompt);
            } else {
                Log::error('Failed to extract prompt from image analysis response');
                return response()->json(['error' => 'Failed to extract prompt'], 500);
            }
        } else {
            Log::info('No image detected in the request line 97');
            // Handle user text input
            $prompt = $request->prompt;

            if ($userStyleImplode) {
                $prompt .= ' and the style should be: ' . $userStyleImplode . '. ';
            }
        }

        if ($request->dall_e_2) {
            Log::info($request->all());
            Log::info('Inside Dalle 2 prompt: ' . $prompt);

            if ($request->quality) {
                $quality = $request->quality;
            }

            if ($request->image_res) {
                $size = $request->image_res;
            }

            if ($request->no_of_result) {
                $n = $request->no_of_result;
                $n = intval($n);
                if ($n > $creditsLeft) {
                    return response()->json(['error' => 'Failed to generate image'], 500);
                }
            }

            $prompt = checkOptimizePrompt($request->prompt, $request);

            if ($creditsLeft >= 1) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/images/generations', [
                    'prompt' => $prompt . ' and the style should be ' . $request->userStyleImplode,
                    'size' => $size,
                    'style' => $style,
                    'quality' => $quality,
                    'n' => $n,
                ]);
            } else {
                return response()->json(['error' => 'Failed to generate image'], 500);
            }

            // return $response;
        }
        // DAll-e 2 End

        if ($request->dall_e_3) {
            Log::info($request->all());
            Log::info('Inside Dalle 3 prompt: ' . $prompt);
            if ($request->quality) {
                $quality = $request->quality;
            }

            if ($request->image_res) {
                $size = $request->image_res;
            }

            if ($request->no_of_result) {
                $n = $request->no_of_result;
                $n = intval($n);
                if ($n > $creditsLeft) {
                    return response()->json(['error' => 'Failed to generate image'], 500);
                }
            }

            $prompt = checkOptimizePrompt($prompt, $request);

            if ($creditsLeft >= 1) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/images/generations', [
                    'model' => 'dall-e-3',
                    'prompt' => $prompt, // Use the correct prompt based on the input type
                    'size' => $size,
                    'style' => $style,
                    'quality' => $quality,
                    'n' => $n,
                ]);

                Log::info('Response Dalle 3 prompt: ' . $response);
            } else {
                return response()->json(['error' => 'Failed to generate image'], 500);
            }
        }
        // DAll-e 3 End

        if ($response && $response->successful()) {
               Log::info('Dalle API Response received', ['response' => $response->json()]);

    $responseData = $response->json();

    foreach ($responseData['data'] as $imageData) {
        Log::info('Processing image', ['image_url' => $imageData['url']]);

        $imageDataBinary = file_get_contents($imageData['url']);
        Log::info('Image data loaded from URL', ['url' => $imageData['url']]);

        try {
            // Create image from blob
            $sourceImage = Image::make($imageDataBinary);
            Log::info('Image successfully loaded into memory');
        } catch (\Exception $e) {
            Log::error('Error loading image from binary', ['exception' => $e->getMessage()]);
            continue; // Skip this iteration if image loading fails
        }

        // Load watermark
        $watermarkPath = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $this->siteSettings->watermark; // Path to your watermark image
        Log::info('Loading watermark', ['watermark_path' => $watermarkPath]);

        try {
            $watermark = Image::make($watermarkPath);
            Log::info('Watermark successfully loaded');
        } catch (\Exception $e) {
            Log::error('Error loading watermark', ['exception' => $e->getMessage()]);
            continue; // Skip if watermark loading fails
        }

        // Insert watermark at bottom-right corner with a 10px offset
        $sourceImage->insert($watermark, 'bottom-right', 10, 10);
        Log::info('Watermark inserted into image');

        // Resize and compress the image
        $compressedImage = $sourceImage->encode('webp', 80); // Compress and output as webp format
        Log::info('Image compressed to webp format', ['quality' => 80]);

        // Save the compressed watermarked image to Azure Blob Storage
        try {
            $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
            $imageName = time() . '-' . uniqid() . '.webp';
            $containerName = config('filesystems.disks.azure.container');
            $blobClient->createBlockBlob($containerName, $imageName, $compressedImage->__toString(), new CreateBlockBlobOptions());

            Log::info('Image successfully uploaded to Azure Blob Storage', ['image_name' => $imageName, 'container' => $containerName]);
        } catch (\Exception $e) {
            Log::error('Error uploading image to Azure Blob Storage', ['exception' => $e->getMessage()]);
            continue; // Skip if upload fails
        }

        $imagePath = $imageName;

        // Save image information to the database
        try {
            $imageModel = new ModelsDalleImageGenerate;
            $imageModel->image = $imagePath;
            $imageModel->user_id = auth()->user()->id;
            $imageModel->status = 'inactive';
            $imageModel->prompt = $prompt;
            $imageModel->resolution = $size;
            $imageModel->style = $userStyleImplode;
            $imageModel->save();
            
            Log::info('Image information successfully saved to the database', ['image_model' => $imageModel]);
        } catch (\Exception $e) {
            Log::error('Error saving image information to the database', ['exception' => $e->getMessage()]);
        }
    }
            // Deduct credits and update the user information
            deductUserTokensAndCredits(0, calculateCredits($size, $quality));
            logActivity('Image Generation', 'Image generated using ' . ($request->dall_e_2 ? 'DALL-E 2' : 'DALL-E 3'));
            return $responseData;
        } else {
            logActivity('Image Generation Error', 'Failed to generate image using ' . ($request->dall_e_2 ? 'DALL-E 2' : 'DALL-E 3'));
            return response()->json(['error' => 'Failed to generate image'], 500);
        }
    }



    public function showTool($id)
    {
        $userId = auth()->id();

        // Retrieve the tool by ID
        $tool = EducationTools::findOrFail($id);

        // Log the activity with the Education Tools name
        logActivity('Education Tools', 'Accessed Education Tools View for Tool: ' . $tool->name);

        $classes = GradeClass::with('subjects')->get();
        $similarTools = EducationTools::where('category', $tool->category)
            ->where('id', '!=', $id)->inRandomOrder()
            ->limit(5)->get();

        $toolContent = ToolGeneratedContent::where('tool_id', $id)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Format content with Parsedown
        $parsedown = new Parsedown();
        foreach ($toolContent as $content) {
            $content->formatted_content = $parsedown->text($content->content);
        }

        // Get all content generated by the user across all tools
        $allToolContent = ToolGeneratedContent::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Format allToolContent with Parsedown
        foreach ($allToolContent as $content) {
            $content->formatted_content = $parsedown->text($content->content);
        }

        $categories = EducationToolsCategory::orderBy('id', 'ASC')->get();

        // Return the data as JSON
        return response()->json([
            'tool' => $tool,
            'classes' => $classes,
            'similarTools' => $similarTools,
            'toolContent' => $toolContent,
            'allToolContent' => $allToolContent,
            'categories' => $categories,
        ]);
    }

    public function ToolsGenerateContent(Request $request)
    {
        Log::info('Inside ToolsGenerateContent API function');
      
        $user = $request->user(); // Get the authenticated user
        if (!$user) {
            Log::info('User not authenticated');
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        $toolId = $request->input('tool_id');
        $tool = EducationTools::find($toolId);
    
        if (!$tool) {
            return response()->json(['error' => 'Tool not found'], 404);
        }
        Log::info('line 314');
        $apiKey = config('app.openai_api_key');
        $client = OpenAI::client($apiKey);
    
        $savedPrompt = $tool->prompt;
        $prompt = $savedPrompt . " ";
        Log::info('line 320');
        if ($gradeId = $request->input('grade_id')) {
            $grade = GradeClass::find($gradeId);
            if ($grade) {
                $prompt .= "Grade: " . $grade->grade . ". ";
            }
        }

        Log::info('line 328');
        foreach ($request->except(['_token', 'tool_id', 'grade_id']) as $key => $value) {
            if (!empty($value)) {
                $escapedValue = addslashes($value);
                $prompt .= ucfirst(str_replace('_', ' ', $key)) . ": $escapedValue. ";
            }
        }
        Log::info('line 334');

        $response = $client->chat()->create([
            "model" => 'o3-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
        Log::info('line 343');
    
        $content = $response['choices'][0]['message']['content'];
        $totalTokens = $response['usage']['total_tokens'];
        
        Log::info('Generated content API: ' . $content);

        // Deduct tokens and credits
        $deductionResult = deductUserTokensAndCreditsAPI($user, $totalTokens, 0); // Pass the user object
        if ($deductionResult === "no-credits") {
            return response()->json(['error' => 'Insufficient credits'], 400);
        }
        Log::info('line 356');
    
        $toolContent = new ToolGeneratedContent();
        $toolContent->tool_id = $toolId;
        $toolContent->user_id = $user->id;
        $toolContent->prompt = $prompt;
        $toolContent->content = $content;
        $toolContent->save();
    
        return response()->json([
            'success' => true,
            'content' => $content,
            'total_tokens' => $totalTokens,
        ]);
    }
    
}
