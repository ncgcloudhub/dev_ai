<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use App\Models\FavoriteImageDalle;
use App\Models\LikedImagesDalle;
use App\Models\PackageHistory;
use App\Models\PromptLibrary;
use App\Models\PromptLibraryCategory;
use App\Models\PromptLibrarySubCategory;
use App\Models\SiteSettings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use App\Providers\AppServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use OpenAI\Client as OpenAIClient;
use OpenAI\Laravel\Facades\OpenAI;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use App\Exports\GeneratedImagesExport;
use App\Models\GeneratedImage;
use Maatwebsite\Excel\Facades\Excel;

class GenerateImagesController extends Controller
{
    protected $siteSettings;

    public function __construct()
    {
        $this->siteSettings = SiteSettings::find(1);
    }

    public function AIGenerateImageView(Request $request)
    {
        logActivity('Dall E Image', 'accessed Dall E Image generate page');

        $user_id = Auth::user()->id;
        
        // Fetch images along with like and favorite counts
        $images = ModelsDalleImageGenerate::withCount(['likes', 'favorites'])
                    ->where('user_id', $user_id)
                    ->orderBy('id', 'desc')
                    ->get();
        
        $content = $request->query('content', ''); // Default to empty string if content not passed
        
        $prompt_library = PromptLibrary::whereHas('category', function ($query) {
            $query->where('category_name', 'Art');
        })->orderby('id', 'asc')->limit(50)->get();

        $check_user = Auth::user()->role;

        if ($check_user == 'admin') {
            $get_user = User::where('role', 'admin')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        } else {
            $get_user = User::where('role', 'user')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        }

        // Generate Azure Blob Storage URL for each image with SAS token and check like/favorite status
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image;
            
            // Check if the image is liked or favorited by the current user
            $image->liked_by_user = LikedImagesDalle::where('user_id', $user_id)->where('image_id', $image->id)->exists();
            $image->favorited_by_user = FavoriteImageDalle::where('user_id', $user_id)->where('image_id', $image->id)->exists();
        }

        // Get the last package bought by the user
        $lastPackageHistory = PackageHistory::where('user_id', $user_id)
                                ->latest()
                                ->first();
        $lastPackageId = $lastPackageHistory ? $lastPackageHistory->package_id : null;

        return view('backend.image_generate.generate_image', compact('images', 'get_user', 'prompt_library', 'lastPackageId','content'));
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

        $usedModel = null;

        if ($request->dall_e_2) {
            $usedModel = 'dall-e-2';
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
            $usedModel = 'dall-e-3';
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
            $source = 'dalle';
            $userName = Str::slug(auth()->user()->name);
            $timestamp = now()->format('YmdHis');
            $imageName = "generated-images/{$source}-{$userName}-{$timestamp}.webp";

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
            $imageModel = new GeneratedImage();
            $imageModel->image = $imagePath;
            $imageModel->user_id = auth()->user()->id;
            $imageModel->prompt = $prompt;
            $imageModel->model = $usedModel;
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

   // Extract Prompt From Image
    public function ExtractImage(Request $request)
    {
        if ($request->hasFile('custom_image') && $request->file('custom_image')->isValid()) {
            $imageFile = $request->file('custom_image');
            $base64Image = base64_encode(file_get_contents($imageFile));

            $response = callOpenAIImageAPI($base64Image);

            $responseArray = json_decode(json_encode($response), true);

            Log::info('Response as array: ' . json_encode($responseArray));

            if (isset($responseArray['choices'][0]['message']['content'])) {
                $extractedPrompt = $responseArray['choices'][0]['message']['content'];

                return response()->json([
                    'content' => $extractedPrompt,
                ]);
            } else {
                Log::error('Failed to extract prompt from image analysis response');
                return response()->json(['error' => 'Failed to extract prompt'], 500);
            }
        } else {
            return response()->json(['error' => 'Invalid or missing image'], 400);
        }
    }

    // Call OpenAI to analyze the image and extract a prompt
    private function callOpenAIImageAPI($base64Image)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => 'What’s in this image?'],
                        ['type' => 'image_url', 'image_url' => [
                            'url' => 'data:image/jpeg;base64,' . $base64Image,
                        ]],
                    ],
                ],
            ],
            'max_completion_tokens' => 300,
        ]);
    
        return $response;
    }

    // Admin Manage Dalle Image
    public function DalleImageManageAdmin()
    {
        // $images = ModelsDalleImageGenerate::latest()->limit(2)->get();

        $prompt_category = PromptLibraryCategory::where('id', 30)->get();
        $prompt_sub_categories = PromptLibrarySubCategory::where('category_id', 30)->get();

        $images = ModelsDalleImageGenerate::latest()->get();

        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        return view('backend.image_generate.generated_manage_image_test', compact('images','prompt_sub_categories', 'prompt_category'));
    }

    public function fetchImages(Request $request)
    {
        $query = ModelsDalleImageGenerate::with('user');
    
        // Handle search
        if (!empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('prompt', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('id', 'like', '%' . $search . '%');
                  });
            });
        }
    
        $totalFiltered = $query->count();
    
        $images = $query->latest()
            ->skip($request->start)
            ->take($request->length)
            ->get();
    
        $data = [];
    
        foreach ($images as $index => $item) {
            $image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $item->image . '?' . config('filesystems.disks.azure.sas_token');
    
            $data[] = [
                'checkbox' => '<input type="checkbox" class="prompt-checkbox" value="' . $item->id . '" data-prompt="' . e($item->prompt) . '">',
                'sr_no' => $request->start + $index + 1,
                'image' => '<a class="image-popup" href="' . asset($image_url) . '" title="">
                                <div class="d-flex align-items-center fw-medium">
                                    <img src="' . asset($image_url) . '" alt="" loading="lazy" class="avatar-xxs me-2">
                                </div>
                            </a>',
                'prompt' => '<a href="#" class="prompt-link" data-prompt="' . e($item->prompt) . '">' . e($item->prompt) . '</a>',
                'user' => $item->user ? $item->user->id . '/' . e($item->user->name) : 'User Not Available',
                'status' => e($item->status),
                'action' => '<div class="form-check form-switch form-switch-md" dir="ltr">
                                <input type="checkbox" class="form-check-input active_button" id="customSwitchsizemd_' . $item->id . '" data-image-id="' . $item->id . '"' . ($item->status == 'active' ? ' checked' : '') . '>
                                <label class="form-check-label" for="customSwitchsizemd_' . $item->id . '"></label>
                            </div>',
            ];
        }
    
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => ModelsDalleImageGenerate::count(),
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }
    

    public function exportImages()
    {
        return Excel::download(new GeneratedImagesExport, 'generated_images.xlsx');
    }

    public function ManageFavoriteImage()
    {
        logActivity('Favourite Image', 'Accessed Favourite Image Page');
        // Retrieve the authenticated user
        $user = Auth::user();

        // Eager load the favorited images for the user
        $images = $user->favoritedImages()->with(['user', 'favorites'])->latest()->get();

        // Modify each image to include the Azure Blob Storage URL with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        return view('backend.image_generate.manage_favorite_dalle_image', compact('images'));
    }

    public function UpdateStatus(Request $request)
    {
        // Retrieve the image ID from the request
        $imageId = $request->input('image_id');

        // Find the image by ID
        $image = ModelsDalleImageGenerate::find($imageId);

        // Update the status (assuming 'status' is a field in your 'models_dalle_image_generates' table)
        if ($image->status == 'inactive') {
            $image->status = 'active';
            $image->save();
            return response()->json(['success' => true, 'message' => 'Image status updated successfully']);
        } elseif ($image->status == 'active') {
            $image->status = 'inactive';
            $image->save();
            return response()->json(['success' => true, 'message' => 'Image status updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }
    }

    // EID CARD
    public function GreetingCard()
    {
        logActivity('Greeting Card', 'accessed Greeting Card');
    
        $user = Auth::user(); // Get the authenticated user
        $user_id = $user->id;

        // 1. Base SD images (GeneratedImage)
        $generatedImages = GeneratedImage::where('user_id', $user_id)->where('festival', 'yes')->orderBy('id', 'desc')->get();
        foreach ($generatedImages as $image) {
            $image->image_url = config('filesystems.disks.azure.url') 
                . config('filesystems.disks.azure.container') 
                . '/' . $image->image;
        }
    
        // 2. DALL·E Images
        $dalleImages = ModelsDalleImageGenerate::withCount(['likes', 'favorites'])
            ->where('user_id', $user_id)
            ->where('festival', 'yes')
            ->orderBy('id', 'desc')
            ->get();
        foreach ($dalleImages as $image) {
            $image->image_url = config('filesystems.disks.azure.url') 
                . config('filesystems.disks.azure.container') 
                . '/' . $image->image;
        }
    
        // 4. Merge all images into one collection
        $merged = collect();

        foreach ($dalleImages as $image) {
            $merged->push($image);
        }

        foreach ($generatedImages as $image) {
            $merged->push($image);
        }
    
        // Optional: sort by created_at or id descending
        $images = $merged->sortByDesc('id')->values();
    
        return view('backend.image_generate.eid_card', compact('images', 'user'));
    }
    
    public function GreetingCardGenerate(Request $request)
    {
        $id = Auth::user()->id;
        $user = Auth::user();
        $creditsLeft = Auth::user()->credits_left;

        $card_style = $request->card_select;
        $holidays = $request->holidays;
        $from = $request->from;
        $to = $request->to;

        $apiKey = config('app.openai_api_key');
        $size = '1024x1024';
        $style = 'vivid';
        $quality = 'hd';
        $promptTemplate = "Create a unique and creative greeting card for \"{holiday}\" with a \"{card_style}\" style. Ensure that the card is vivid and has a simple 'wow' design, and includes only the text for the holiday name, 'From \"{from}\"', and 'To \"{to}\"'. No other text should be present on the card. Ensure that 'From' and 'To' are displayed exactly as typed by the user at the bottom of the image. From and to should be alwaays at the bottom of the Image";
        $finalPrompt = str_replace(['{holiday}', '{card_style}', '{from}', '{to}'], [$holidays, $card_style, $from, $to], $promptTemplate);

        $n = 1;

        $response = null;

        if ($creditsLeft >= 1) {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $finalPrompt,
                'size' => $size,
                'style' => $style,
                'quality' => $quality,
                'n' => $n,
            ]);
            // return $response;
        }
        // DAll-e 3 End


        if ($response !== null) {
            if ($response->successful()) {
                $responseData = $response->json();

                foreach ($responseData['data'] as $imageData) {
                    $imageDataBinary = file_get_contents($imageData['url']);

                    // Create image from blob
                    $sourceImage = imagecreatefromstring($imageDataBinary);

                    // Get image dimensions
                    $sourceWidth = imagesx($sourceImage);
                    $sourceHeight = imagesy($sourceImage);

                    // Calculate new dimensions
                    $targetWidth = $sourceWidth;  // Keep original width
                    $targetHeight = $sourceHeight; // Keep original height

                    // Create new image with the new dimensions
                    $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

                    // Resize and compress the image
                    imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);
                    ob_start(); // Turn on output buffering
                    imagejpeg($targetImage, null, 75); // Compress and output the image as JPEG
                    $imageDataBinaryCompressed = ob_get_contents(); // Get the compressed image data
                    ob_end_clean(); // Turn off output buffering

                    // Save the compressed image to Azure Blob Storage
                    $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));

                    $source = 'dalle-greeting-card';
                    $userName = Str::slug(auth()->user()->name);
                    $timestamp = now()->format('YmdHis');
                    $imageName = "generated-images/{$source}-{$userName}-{$timestamp}.webp";

                    $containerName = config('filesystems.disks.azure.container');
                    $blobClient->createBlockBlob($containerName, $imageName, $imageDataBinaryCompressed, new CreateBlockBlobOptions());

                    $imagePath = $imageName;

                    // Save image information to database

                    $imageModel = new GeneratedImage();
                    $imageModel->image = $imagePath;
                    $imageModel->user_id = auth()->user()->id;
                    $imageModel->prompt = $finalPrompt;
                    $imageModel->model = 'dall-e-3';
                    $imageModel->resolution = $size;
                    $imageModel->style = $style;
                    $imageModel->festival = 'yes'; // Set Festive Status
                    $imageModel->save();
                }

                // $credits = calculateCredits($size, $quality);
                logActivity('Greeting Card Generate', 'generated a greeting card');
                deductUserTokensAndCredits(0, calculateCredits($size, $quality));
                // User::where('id', $id)->update([
                //     'credits_used' => DB::raw('credits_used + ' . $credits),
                //     'credits_left' => DB::raw('credits_left - ' . $credits),
                //     'images_generated' => DB::raw('images_generated + ' . $n),
                // ]);

                // $newCreditLeft = Auth::user()->credits_left - $credits;
                // $responseData['credit_left'] = $newCreditLeft;

                return  $responseData;
            } else {
                logActivity('Greeting Card Generate', 'failed to generate');
                return response()->json(['error' => 'Failed to generate image'], 500);
            }
        } else {
            logActivity('Greeting Card Generate', 'failed to generate [No condition met]');
            return response()->json(['error' => 'No condition met'], 500);
        }
    }


    // FRontend Single Image Generate
    public function generateSingleImage(Request $request)
    {
        $apiKey = config('app.openai_api_key');
        $prompt = $request->input('prompt');

        // Check if the user has already generated an image today
        if (Session::has('image_generated_at')) {
            $lastGeneratedAt = Session::get('image_generated_at');
            $today = now()->startOfDay();

            if ($lastGeneratedAt->diffInDays($today) == 0) {
                // If the user has already generated an image today, return a message
                return response()->json(['message' => 'You have already generated an image today. Please try again tomorrow.']);
            }
        }

        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/images/generations', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        // Store the timestamp of the image generation in the session
        Session::put('image_generated_at', now());

        return response()->json($data);
    }

    // Like Image
    public function toggleLike(Request $request)
    {
        // Get the image ID from the request
        $imageId = $request->input('image_id');

        // Get the current authenticated user
        $user = Auth::user();

        // Check if the user has already liked the image
        $liked = $user->likedImages()->where('image_id', $imageId)->exists();

        // Log user and image ID for debugging purposes
        //  Log::info('User ID: ' . $user->id . ', Image ID: ' . $imageId . 'liked: ' . $liked);

        if ($liked) {
            // User has already liked the image, so unlike it
            $user->likedImages()->detach($imageId);
            $liked = false;
        } else {
            // User hasn't liked the image yet, so like it
            LikedImagesDalle::create([
                'user_id' => $user->id,
                'image_id' => $imageId
            ]);
            $liked = true;
        }

        // Return response indicating success and the new like status
        return response()->json(['success' => true, 'liked' => $liked]);
    }

    // Toggle Favorite
    public function toggleFavorite(Request $request)
    {
        // Get the image ID from the request
        $imageId = $request->input('image_id');

        // Get the current authenticated user
        $user = Auth::user();

        // Check if the user has already favorited the image
        $favorited = $user->favoritedImages()->where('image_id', $imageId)->exists();

        if ($favorited) {
            // User has already favorited the image, so unfavorite it
            $user->favoritedImages()->detach($imageId);
            $favorited = false;
        } else {
            // User hasn't favorited the image yet, so favorite it
            FavoriteImageDalle::create([
                'user_id' => $user->id,
                'image_id' => $imageId
            ]);
            $favorited = true;
        }
        // Return response indicating success and the new favorite status
        return response()->json(['success' => true, 'favorited' => $favorited]);
    }

    // Bulk Image
    public function fetchBulkDetails(Request $request)
    {
        $prompts = $request->input('prompts');
        $user = auth()->user();
        $openaiModel = $user->selected_model;
        $client = new \GuzzleHttp\Client();
    
        // Fetch all subcategories under category_id = 44
        $subcategories = PromptLibrarySubCategory::where('category_id', 44)
            ->pluck('sub_category_name')
            ->toArray();
    
        // Convert the subcategories array to a comma-separated string
        $subcategoryList = implode(', ', $subcategories);
    
        $results = [];
    
        foreach ($prompts as $prompt) {
    
            // Check if the actual prompt already exists in the PromptLibrary
            $existingPrompt = PromptLibrary::where('actual_prompt', $prompt)->first();
    
            if ($existingPrompt) {
    
                // If exists, skip API call and use existing details
                $results[] = [
                    'prompt' => $prompt,
                    'details' => $existingPrompt->description,
                    'promptName' => $existingPrompt->prompt_name,
                    'subcategory' => $existingPrompt->sub_category_id
                ];
                continue; // Skip API call and move to the next prompt
            }
    
            try {
                $response = $client->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => $openaiModel,
                        'messages' => [
                            [
                                "role" => "system",
                                "content" => "You are a helpful assistant."
                            ],
                            [
                                "role" => "user",
                                "content" => "Generate a brief description, a suggested prompt name, and a subcategory for this prompt: \"$prompt\". Ensure the prompt name is concise, relevant, and SEO-friendly without special characters except 'dash'. The details should also be SEO optimized, free from special characters except 'dash', and have a header '**Details:**'. The subcategory should be one of the following: $subcategoryList. Format the response with three distinct sections: '**Prompt Name:**' followed by the name, '**Details:**' followed by the description, and '**Subcategory:**' followed by the subcategory."
                            ]
                        ],
                    ],
                ]);
    
                $responseBody = json_decode($response->getBody()->getContents(), true);
                $assistantContent = $responseBody['choices'][0]['message']['content'] ?? '';
    
                // Extract Prompt Name
                $promptNamePattern = '/\*\*Prompt Name:\*\*\s*(.*?)\s*\*\*Details:\*\*/s';
                $detailsPattern = '/\*\*Details:\*\*\s*(.+)\s*\*\*Subcategory:\*\*/s';
                $subcategoryPattern = '/\*\*Subcategory:\*\*\s*(.+)/s';
    
                $promptName = '';
                $details = '';
                $subcategory = '';
    
                if (preg_match($promptNamePattern, $assistantContent, $matches)) {
                    $promptName = trim($matches[1]);
                    $promptName = str_replace('-', ' ', $promptName);
                    $promptName = ucwords($promptName);
                }
    
                if (preg_match($detailsPattern, $assistantContent, $matches)) {
                    $details = trim($matches[1]);
                }
    
                if (preg_match($subcategoryPattern, $assistantContent, $matches)) {
                    $subcategory = trim($matches[1]);
                }
    
                // Fetch subcategory ID from the PromptLibrarySubCategory model
                // Only look for subcategories under category_id = 44
                $subcategoryRecord = PromptLibrarySubCategory::where('sub_category_name', $subcategory)
                    ->where('category_id', 44) // Filter by category_id = 44
                    ->first();
    
                $subcategoryId = $subcategoryRecord ? $subcategoryRecord->id : null;
    
                $results[] = [
                    'prompt' => $prompt,
                    'details' => $details,
                    'promptName' => $promptName,
                    'subcategory' => $subcategoryId
                ];
    
            } catch (\Exception $e) {
                Log::error("Error fetching prompt details: " . $e->getMessage());
                $results[] = [
                    'prompt' => $prompt,
                    'details' => 'Error generating details',
                    'promptName' => 'Error',
                    'subcategory' => null
                ];
            }
        }
        return response()->json($results);
    }
    
    public function saveBulkPrompts(Request $request)
    {
        $prompts = $request->input('prompts');

        foreach ($prompts as $promptData) {
            $slug = Str::slug($promptData['prompt_name']);
            $actual_prompt = ($promptData['prompt']);

            // Check if the prompt already exists by prompt_name or slug
            $existingPrompt = PromptLibrary::where('actual_prompt', $actual_prompt)
                ->first();

            // If the prompt already exists, skip the insert
            if ($existingPrompt) {
                continue;  // Skip to the next iteration
            }

            // If subcategory ID is null, skip saving this prompt
            if (is_null($promptData['subcategory'])) {
                continue;
            }

            PromptLibrary::create([
                'actual_prompt' => $promptData['prompt'],  // Matches single insert
                'category_id' => 44, // Hardcode category_id = 44
                'sub_category_id' => $promptData['subcategory'], // Use the subcategory ID from the API response
                'description' => $promptData['details'], // Fix: changed 'details' to 'description'
                'prompt_name' => $promptData['prompt_name'],
                'slug' => $slug
            ]);
        }
        return response()->json(['success' => true]);
    }
}