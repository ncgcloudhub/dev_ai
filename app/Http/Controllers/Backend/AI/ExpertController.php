<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expert;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\AiChat;
use App\Models\AiChatMessage;
use App\Models\AISettings;
use Illuminate\Support\Facades\Auth;


class ExpertController extends Controller
{
    public function ExpertAdd()
    {
        $experts = Expert::latest()->get();
        return view('backend.expert.expert_add', compact('experts'));
    }

    public function ExpertStore(Request $request)
    {

        $imageName = '';
        if ($image = $request->file('image')) {
            $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('backend/uploads/expert', $imageName);
        }

        // Expert::create([
        //     'image'=>$imageName,
        // ]);

        $slug = Str::slug($request->expert_name);

        $expert_id = Expert::insertGetId([

            'expert_name' => $request->expert_name,
            'character_name' => $request->character_name,
            'slug' => $slug,
            'description' => $request->description,
            'role' => $request->role,
            'expertise' => $request->expertise,
            'active' => '1',
            'image' => $imageName,
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
        $experts = Expert::latest()->get();
        return view('backend.expert.expert_manage', compact('experts'));
    }

    public function ExpertChat($slug)
    {
        $expert_selected = Expert::where('slug', $slug)->firstOrFail();
        $expert_selected_id = $expert_selected->id;
        $experts = Expert::latest()->get();
        return view('backend.expert.chat1', compact('experts', 'expert_selected_id', 'expert_selected'));
    }


    public function sendMessages(Request $request)
    {
        // Get user input from the request
        $userInput = $request->input('message');

        // Fetch expert details
        $expertId = $request->input('expert');
        $expert = Expert::findOrFail($expertId);
        $expertRole = $expert->role;
        $expertImage = $expert->image;
        // Fetch the system instruction from the database
        $expertInstruction = $expert->expertise;


        // Fetch OpenAI settings
        $setting = AISettings::find(1);
        $openaiModel = $setting->openaimodel;

        // Check if the expert instruction is empty
        if (empty($expertInstruction)) {
            // If no instruction is provided, use the default instruction
            $expertInstruction = "You are now playing the role of $expertRole. As an expert in $expertRole for the past 40 years, I need your help. Please answer this: \"$userInput\". If anyone asks any questions outside of $expertRole, please reply as I am not programmed to respond.";
        }


        // Define the messages array with the dynamic user input
        $messages = [
            ['role' => 'system', 'content' => $expertInstruction],
            ['role' => 'user', 'content' => $userInput]
        ];

        // Make API request to OpenAI
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('app.openai_api_key'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' =>  $openaiModel, // Use the appropriate model name
            'messages' => $messages,
            'temperature' => 0,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,

        ]);

        // Extract completion from API response
        // $content = $response->json('choices.0.text');
        $content = $response->json('choices.0.message.content');


        // TEST SAVE CHAT
        // Save chat data to the database
        $aiChat = new AiChat();
        $aiChat->user_id = Auth::id(); // Assuming you are using authentication
        $aiChat->expert_id = $expertId;
        $aiChat->title = $userInput; // You may want to change this
        $aiChat->total_words = str_word_count($userInput);
        $aiChat->save();

        $aiChatMessage = new AiChatMessage();
        $aiChatMessage->ai_chat_id = $aiChat->id;
        $aiChatMessage->user_id = Auth::id(); // Assuming you are using authentication
        $aiChatMessage->prompt = $expertInstruction;
        $aiChatMessage->response = $content;
        $aiChatMessage->words = str_word_count($content);
        $aiChatMessage->save();
        // TEST SAVE CHAT END

        // Return response to the client
        return response()->json([
            'prompt' => $expertInstruction,
            'content' => $content,
            'expert_image' => $expertImage
        ]);
    }


    public function ExpertEdit($id)
    {
        $expert = Expert::findOrFail($id);
        return view('backend.expert.expert_edit', compact('expert'));
    }

    public function ExpertUpdate(Request $request, $id)
    {
        $expert = Expert::findOrFail($id);

        $imageName = $expert->image;
        if ($image = $request->file('image')) {
            $oldImagePath = 'backend/uploads/expert/' . $expert->image;
            if (!empty($expert->image) && file_exists($oldImagePath) && is_file($oldImagePath)) {
                unlink($oldImagePath); // Delete the old image
            }
            $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('backend/uploads/expert', $imageName);
        }

        $slug = Str::slug($request->expert_name);

        $expert->update([
            'expert_name' => $request->expert_name,
            'character_name' => $request->character_name,
            'slug' => $slug,
            'description' => $request->description,
            'role' => $request->role,
            'expertise' => $request->expertise,
            'image' => $imageName,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Expert Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('chat')->with($notification);
    }

    public function ExpertDelete($id)
    {
        $expert = Expert::findOrFail($id);

        if (file_exists('backend/uploads/expert/' . $expert->image)) {
            unlink('backend/uploads/expert/' . $expert->image); // Delete the image
        }

        $expert->delete();

        $notification = array(
            'message' => 'Expert Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


}
