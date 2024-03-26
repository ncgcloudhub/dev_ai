<?php

namespace App\Http\Controllers\Backend\AI;

use App\Models\Expert;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AISettings;
use Illuminate\Support\Carbon;
use OpenAI\Laravel\Facades\OpenAI;

class AIChatController extends Controller
{
    
    public function sendMessages(Request $request) {
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
    
        // Return response to the client
        return response()->json([
            'prompt' => $prompt,
            'content' => $content,
            'expert_image' => $expertImage
        ]);
    }
    
}
