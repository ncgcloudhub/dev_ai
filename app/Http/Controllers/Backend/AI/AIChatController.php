<?php

namespace App\Http\Controllers\Backend\AI;

use App\Models\Expert;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\AiChat;
use App\Models\AiChatMessage;
use Illuminate\Http\Request;
use App\Models\AISettings;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class AIChatController extends Controller
{

    public function sendMessages(Request $request)
    {
        // Get user input from the request
        $userInput = $request->input('message');

        // Fetch expert details
        $expertId = $request->input('expert');
        $expert = Expert::findOrFail($expertId);
        $expertRole = $expert->role;
        $expertImage = $expert->image;

        // Fetch OpenAI settings
        $setting = AISettings::find(1);
        $openaiModel = $setting->openaimodel;

        // Create prompt based on user input and expert role
        $prompt = "You are now playing the role of $expertRole. As an expert in $expertRole for the past 40 years, I need your help. Please answer this: \"$userInput\". If anyone ask any questions outside of $expertRole, please reply as I am not program to response. ";

        // Make API request to OpenAI
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('app.openai_api_key'),
        ])->post('https://api.openai.com/v1/completions', [
            'model' => $openaiModel,
            'temperature' => 0,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'max_tokens' => 500,
            'prompt' => $prompt,
        ]);

        // Extract completion from API response
        $content = $response->json('choices.0.text');


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
        $aiChatMessage->prompt = $prompt;
        $aiChatMessage->response = $content;
        $aiChatMessage->words = str_word_count($content);
        $aiChatMessage->save();
        // TEST SAVE CHAT END

        // Return response to the client
        return response()->json([
            'prompt' => $prompt,
            'content' => $content,
            'expert_image' => $expertImage
        ]);
    }


    // Dashboard Chat Admin
    public function send(Request $request)
    {
       
               // Get input messages
               $messages = [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => 'Hello!']
            ];
    
            // Make API call
            $client = new Client();
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o', // Use the appropriate model name
                    'messages' => $messages,
                ],
            ]);
    
            $data = json_decode($response->getBody(), true);
    
            // Return the response
            return response()->json([
                'message' => $data['choices'][0]['message']['content']
            ]);
        }
    }
    // END Dashboard Chat Admin
    
