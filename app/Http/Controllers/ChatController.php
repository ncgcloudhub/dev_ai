<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{

     public function chat(Request $request)
{
    Log::info('Chat endpoint hit', [
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'message' => $request->message
    ]);

    $request->validate([
        'message' => 'required|string',
        'conversation' => 'nullable|array'
    ]);

    $messages = $request->conversation ?? [];
    $messages[] = ['role' => 'user', 'content' => $request->message];

    Log::debug('Sending to OpenAI', ['messages' => $messages]);

    $response = OpenAI::chat()->createStreamed([
        'model' => 'gpt-3.5-turbo',
        'messages' => $messages,
        'stream' => true,
    ]);

    return new StreamedResponse(function () use ($response) {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        $chunkCount = 0;
        
        foreach ($response as $chunk) {
            $content = $chunk->choices[0]->delta->content ?? '';
            $chunkCount++;
            
            Log::debug("Sending chunk {$chunkCount}", [
                'content' => $content,
                'chunk_size' => strlen($content)
            ]);

            if (connection_aborted()) {
                Log::warning('Client disconnected early');
                break;
            }
            
            echo "data: " . json_encode(['content' => $content]) . "\n\n";
            ob_flush();
            flush();
            
            usleep(50000);
        }

        Log::info('Stream completed', ['total_chunks' => $chunkCount]);
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
        'X-Accel-Buffering' => 'no',
    ]);
}

}
