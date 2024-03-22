<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DallEImageGenerate extends Controller
{
    public function AIGenerateImageView(){
        // $brands = Brand::latest()->get();
        return view('backend.image_generate.generate_image');
    }


    public function generateImage(Request $request) {
        
       
		$apiKey = config('app.openai_api_key');
        $size = '1024x1024';
        $style = 'vivid';
		$quality = 'standard';
		$n = 1;
      
        $response = null;


     if($request->dall_e_2){

        if($request->quality){
            $quality = $request->quality;
        }

        if($request->style){
            $style = $request->style;
        }

        if($request->image_res){
            $size = $request->image_res;
        }

        if($request->no_of_result){
            $n = $request->no_of_result;
            $n = intval($n);
        }

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

        // return $response;
    }
    // DAll-e 2 End

    if($request->dall_e_3){
       
        if($request->quality){
            $quality = $request->quality;
        }

        if($request->style){
            $style = $request->style;
        }

        if($request->image_res){
            $size = $request->image_res;
        }

        if($request->no_of_result){
            $n = $request->no_of_result;
            $n = intval($n);
        }

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
                $imageModel->status = 'active'; // Set the status as per your requirements
                $imageModel->prompt = $request->prompt; // Set the prompt if needed
                $imageModel->resolution = $size; // Set the resolution if needed
                $imageModel->save();
            }
    



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
}
