<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use App\Models\PromptLibrary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use App\Providers\AppServiceProvider;

class GenerateImagesController extends Controller
{
    public function AIGenerateImageView()
    {
        $user_id = Auth::user()->id;
        $images = ModelsDalleImageGenerate::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        $prompt_library = PromptLibrary::orderby('id', 'asc')->limit(50)->get();

        $check_user = Auth::user()->role;

        if ($check_user == 'admin') {
            $get_user = User::where('role', 'admin')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        } else {
            $get_user = User::where('role', 'user')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        }

        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        return view('backend.image_generate.generate_image', compact('images', 'get_user', 'prompt_library'));
    }



    public function generateImage(Request $request)
    {

        $id = Auth::user()->id;
        $imagesLeft = Auth::user()->images_left;

        $apiKey = config('app.openai_api_key');
        $size = '1024x1024';
        $style = 'vivid';
        $quality = 'standard';
        $n = 1;

        $response = null;

        if ($request->dall_e_2) {

            if ($request->quality) {
                $quality = $request->quality;
            }

            if ($request->image_res) {
                $size = $request->image_res;
            }

            if ($request->no_of_result) {
                $n = $request->no_of_result;
                $n = intval($n);
                if ($n > $imagesLeft) {
                    return response()->json(['error' => 'Failed to generate image'], 500);
                }
            }


            if ($imagesLeft >= 1) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/images/generations', [
                    'prompt' => $request->prompt . ' and the style should be ' . $request->style,
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

            if ($request->quality) {
                $quality = $request->quality;
            }

            if ($request->image_res) {
                $size = $request->image_res;
            }

            if ($request->no_of_result) {
                $n = $request->no_of_result;
                $n = intval($n);
                if ($n > $imagesLeft) {
                    return response()->json(['error' => 'Failed to generate image'], 500);
                }
            }

            if ($imagesLeft >= 1) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/images/generations', [
                    'model' => 'dall-e-3',
                    'prompt' => $request->prompt . ' and the style should be ' . $request->style,
                    'size' => $size,
                    'style' => $style,
                    'quality' => $quality,
                    'n' => $n,
                ]);
            } else {
                return response()->json(['error' => 'Failed to generate image'], 500);
            }
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
                    imagejpeg($targetImage, null, 60); // Compress and output the image as JPEG
                    $imageDataBinaryCompressed = ob_get_contents(); // Get the compressed image data
                    ob_end_clean(); // Turn off output buffering

                    // Save the compressed image to Azure Blob Storage
                    $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));

                    $imageName = time() . '-' . uniqid() . '.jpg';
                    $containerName = config('filesystems.disks.azure.container');
                    $blobClient->createBlockBlob($containerName, $imageName, $imageDataBinaryCompressed, new CreateBlockBlobOptions());

                    $imagePath = $imageName;

                    // Save image information to database
                    $imageModel = new ModelsDalleImageGenerate;
                    $imageModel->image = $imagePath;
                    $imageModel->user_id = auth()->user()->id;
                    $imageModel->status = 'inactive';
                    $imageModel->prompt = $request->prompt;
                    $imageModel->resolution = $size;
                    $imageModel->save();
                }


                $credits = calculateCredits($size, $quality);

                // return $credits;

                User::where('id', $id)->update([
                    'images_generated' => DB::raw('images_generated + ' . $credits),
                    'images_left' => DB::raw('images_left - ' . $credits),
                ]);

                $newImagesLeft = Auth::user()->images_left - $credits;
                $responseData['images_left'] = $newImagesLeft;

                return  $responseData;
            } else {
                return response()->json(['error' => 'Failed to generate image'], 500);
            }
        } else {
            return response()->json(['error' => 'No condition met'], 500);
        }
    }


    // Admin Manage Dalle Image
    public function DalleImageManageAdmin()
    {
        $images = ModelsDalleImageGenerate::latest()->get();

        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        return view('backend.image_generate.manage_admin_dalle_image', compact('images'));
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
    public function EidCard()
    {
        $user_id = Auth::user()->id;
        $images = ModelsDalleImageGenerate::where('user_id', $user_id)->where('festival', 'yes')->get();

        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }


        $check_user = Auth::user()->role;

        if ($check_user == 'admin') {
            $get_user = User::where('role', 'admin')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        } else {
            $get_user = User::where('role', 'user')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        }

        if ($images_count > 500) {
            return redirect()->route('all.package');
        } else {
            return view('backend.image_generate.eid_card', compact('images', 'get_user'));
        }
    }


    public function EidCardGenerate(Request $request)
    {

        $id = Auth::user()->id;
        $user = Auth::user();
        $imagesLeft = Auth::user()->images_left;

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

        if ($imagesLeft >= 1) {

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

                    $imageName = time() . '-' . uniqid() . '.jpg';
                    $containerName = config('filesystems.disks.azure.container');
                    $blobClient->createBlockBlob($containerName, $imageName, $imageDataBinaryCompressed, new CreateBlockBlobOptions());

                    $imagePath = $imageName;

                    // Save image information to database
                    $imageModel = new ModelsDalleImageGenerate;
                    $imageModel->image = $imagePath;
                    $imageModel->user_id = auth()->user()->id; // Assuming you have a logged-in user
                    $imageModel->status = 'inactive'; // Set the status as per your requirements
                    $imageModel->prompt = $finalPrompt; // Set the prompt if needed
                    $imageModel->resolution = $size; // Set the resolution if needed
                    $imageModel->festival = 'yes'; // Set Festive Status
                    $imageModel->save();
                }

                $credits = calculateCredits($size, $quality);

                User::where('id', $id)->update([
                    'images_generated' => DB::raw('images_generated + ' . $credits),
                    'images_left' => DB::raw('images_left - ' . $credits),
                ]);

                $newImagesLeft = Auth::user()->images_left - $credits;
                $responseData['images_left'] = $newImagesLeft;

                return  $responseData;
            } else {
                return response()->json(['error' => 'Failed to generate image'], 500);
            }
        } else {
            return response()->json(['error' => 'No condition met'], 500);
        }
    }
}
