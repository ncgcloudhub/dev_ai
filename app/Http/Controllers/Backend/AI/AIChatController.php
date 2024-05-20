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
            'model' => 'gpt-4o', // Use the appropriate model name
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


    // Dashboard Chat Admin
    public function send(Request $request)
{
    $userMessage = $request->input('message');
$file = $request->file('file');

// Check if file was uploaded and has content
if ($file && $file->isValid()) {
    // Read file content and store it in the session
    $fileContent = file_get_contents($file->getRealPath());
    session(['file_content' => $fileContent]);
} else {
    // If no file uploaded or invalid, retrieve file content from session
    $fileContent = session('file_content', '');
}

// Define the messages array with the dynamic user input
$messages = [
    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    ['role' => 'user', 'content' => $userMessage],
];

// Add file content to messages if it exists
if ($fileContent) {
    $messages[] = ['role' => 'user', 'content' => $fileContent];
}

// Add user message to conversation history
$conversationHistory = session('conversation_history', []);
$conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
session(['conversation_history' => $conversationHistory]);

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

$message = $data['choices'][0]['message']['content'];

// Return the response
return response()->json([
    'message' => $message
]);
}

    
}
    // END Dashboard Chat Admin
