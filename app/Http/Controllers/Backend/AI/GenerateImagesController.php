<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GenerateImagesController extends Controller
{
    public function AIGenerateImageView()
    {
        $user_id = Auth::user()->id;
        $images = ModelsDalleImageGenerate::where('user_id', $user_id)->get();

        $check_user = Auth::user()->role;

        if ($check_user == 'admin') {
            $get_user = User::where('role', 'admin')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        } else {
            $get_user = User::where('role', 'user')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        }

        // if ($images_count <= 0) {
        //     return redirect()->route('all.package');
        // } else {

            return view('backend.image_generate.generate_image', compact('images', 'get_user'));
        // }
    }


    public function generateImage(Request $request)
    {

        $id = Auth::user()->id;
        $imagesLeft = Auth::user()->images_left;

        $apiKey = config('app.openai_api_key');
        $size = '1024x1024';
        $style = 'vivid';
        $quality = 'hd';
        $n = 1;

        $response = null;

        if ($request->dall_e_2) {

            if ($request->quality) {
                $quality = $request->quality;
            }

            if ($request->style) {
                $style = $request->style;
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

            
if($imagesLeft >= 1){
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'prompt' => $request->prompt,
                'size' => $size,
                'style' => $style,
                'quality' => $quality,
                'n' => $n,
            ]);
        }else{
            return response()->json(['error' => 'Failed to generate image'], 500);
        }

            // return $response;
        }
        // DAll-e 2 End

        if ($request->dall_e_3) {

            if ($request->quality) {
                $quality = $request->quality;
            }

            if ($request->style) {
                $style = $request->style;
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

            if($imagesLeft >= 1){
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $request->prompt,
                'size' => $size,
                'style' => $style,
                'quality' => $quality,
                'n' => $n,
            ]);
        }else{
            return response()->json(['error' => 'Failed to generate image'], 500);
        }
        }
        // DAll-e 3 End


        if ($response !== null) { // Check if $response is not null before using it
            if ($response->successful()) {
                $responseData = $response->json();

                foreach ($responseData['data'] as $imageData) {
                    // Save image to directory
                    $imageName = time() . '-' . uniqid() . '.png'; // Or whatever extension you're using
                    $imagePath = 'backend/uploads/dalle_images/' . $imageName;
                    file_put_contents($imagePath, file_get_contents($imageData['url']));

                    // Save image information to database
                    $imageModel = new ModelsDalleImageGenerate;
                    $imageModel->image = $imagePath;
                    $imageModel->user_id = auth()->user()->id; // Assuming you have a logged-in user
                    $imageModel->status = 'inactive'; // Set the status as per your requirements
                    $imageModel->prompt = $request->prompt; // Set the prompt if needed
                    $imageModel->resolution = $size; // Set the resolution if needed
                    $imageModel->save();
                }


                // Image Increment
                User::where('id', $id)->update([
                    'images_generated' => DB::raw('images_generated + ' . $n),
                    'images_left' => DB::raw('images_left - ' . $n),
                ]);

                $newImagesLeft = Auth::user()->images_left - $n;
                // Add images_left to the $responseData array
                $responseData['images_left'] = $newImagesLeft;
                // $imageURL = $responseData['data'][0]['url'];
                // return response()->json(['imageURL' => $imageURL]);
                return  $responseData;
                // return view('backend.image_generate.generate_image', ['imageURL' => $imageURL]);
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
        $images = ModelsDalleImageGenerate::where('user_id', $user_id)->where('festival','yes')->get();

        $check_user = Auth::user()->role;

        if ($check_user == 'admin') {
            $get_user = User::where('role', 'admin')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        } else {
            $get_user = User::where('role', 'user')->where('id', $user_id)->first();
            $images_count = $get_user->images_generated;
        }

        if ($images_count > 100) {
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
        $eid_text = $request->eid_text;

        $apiKey = config('app.openai_api_key');
        $size = '1024x1024';
        $style = 'vivid';
        $quality = 'hd';
        $prompt = 'Greeting eid card with exact word ' . $eid_text . ', ' . $card_style . ' style.';
        $n = 1;

        $response = null;

        if ($imagesLeft >= 1) {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'size' => $size,
                'style' => $style,
                'quality' => $quality,
                'n' => $n,
            ]);
            // return $response;
        }
        // DAll-e 3 End


        if ($response !== null) { // Check if $response is not null before using it
            if ($response->successful()) {
                $responseData = $response->json();

                foreach ($responseData['data'] as $imageData) {
                    // Save image to directory
                    $imageName = time() . '-' . uniqid() . '.png'; // Or whatever extension you're using
                    $imagePath = 'backend/uploads/dalle_images/' . $imageName;
                    file_put_contents($imagePath, file_get_contents($imageData['url']));

                    // Save image information to database
                    $imageModel = new ModelsDalleImageGenerate;
                    $imageModel->image = $imagePath;
                    $imageModel->user_id = auth()->user()->id; // Assuming you have a logged-in user
                    $imageModel->status = 'inactive'; // Set the status as per your requirements
                    $imageModel->prompt = $prompt; // Set the prompt if needed
                    $imageModel->resolution = $size; // Set the resolution if needed
                    $imageModel->festival = 'yes'; // Set Festive Status
                    $imageModel->save();
                }

                // Image Increment
                User::where('id', $id)->update([
                    'images_generated' => DB::raw('images_generated + ' . $n),
                    'images_left' => DB::raw('images_left - ' . $n),
                ]);

                $newImagesLeft = Auth::user()->images_left - $n;
                // Add images_left to the $responseData array
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
