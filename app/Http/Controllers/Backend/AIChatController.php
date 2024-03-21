<?php

namespace App\Http\Controllers\Backend;

use App\Models\AIChat;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AISettings;
use OpenAI;
use Illuminate\Support\Carbon;

class AIChatController extends Controller
{
    public function ExpertAdd(){
        $experts = AIChat::latest()->get();
        return view('backend.expert.expert_add', compact('experts'));
    }

    public function ExpertStore(Request $request)
    {

        $imageName = '';
        if ($image = $request->file('image')){
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('backend/uploads/expert', $imageName);
        }

        // Expert::create([
        //     'image'=>$imageName,
        // ]);


        $expert_id = AIChat::insertGetId([
            
            'expert_name' => $request->expert_name,
            'character_name' => $request->character_name,
            'slug' => $request->expert_name,
            'description' => $request->description,
            'role' => $request->role,
            'expertise' => $request->expertise,
            'active' => '1',
            'image'=>$imageName,
            'created_at' => Carbon::now(),   
    
          ]);

        $notification = array(
            'message' => 'Expert Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }


    // CHAT
    public function index()
    {
        $experts = AIChat::latest()->get();
        return view('backend.expert.expert_manage', compact('experts'));
    }

    public function ExpertChat($id)
    {
        $expert_selected = AIChat::findOrFail($id);
        $expert_selected_id = $id;
        $experts = AIChat::latest()->get();
        return view('backend.expert.chat1', compact('experts','expert_selected_id','expert_selected'));
    }


    public function SendMessages(Request $request){

       
            $search = $request->input('message');
            $expert = $request->input('expert');
            $expert_id = AIChat::findOrFail($expert);
            $expert_image = $expert_id->image;

            $setting = AISettings::find(1);
            $apiKey = config('app.openai_api_key');
            $client = OpenAI::client($apiKey);
            $user_input =  $search;
           
            $prompt = "You will now play a character and respond as that character (You will never break character). I want you to act as a $expert_id->role. With your vast expertise in the $expert_id->role for past 40 years, I need your help. As a $expert_id->role please answer this, $user_input. If anyone asks any questions outside of $expert_id->role, please respond with 'I am not programmed to respond to those inquiries.'";

    
    $result = $client->completions()->create([
                "model" => $setting->openaimodel,
                "temperature" => 0,
                "top_p" => 1,
                "frequency_penalty" => 0,
                "presence_penalty" =>0,
                'max_tokens' => 500,
                'prompt' => $prompt,
            ]);
        
            $content = trim($result['choices'][0]['text']);
    
            // return view('backend.custom_template.template_view', compact('title', 'content'));
            return array('prompt' => $prompt, 'content' => $content, 'expert_image' => $expert_image);
            // return $content;
    }
}
