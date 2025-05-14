<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatConversation;
use App\Models\ChatMessage;

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
            'conversation' => 'nullable|array',
            'conversation_id' => 'nullable|integer'
        ]);

        $messages = $request->conversation ?? [];
        $messages[] = ['role' => 'user', 'content' => $request->message];

        Log::debug('Sending to OpenAI', ['messages' => $messages]);

        $response = OpenAI::chat()->createStreamed([
            'model' => 'gpt-4.1-nano',
            'messages' => $messages,
            'stream' => true,
        ]);

        return new StreamedResponse(function () use ($response, $request) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            $chunkCount = 0;
            $fullContent = '';
            
            foreach ($response as $chunk) {
                $content = $chunk->choices[0]->delta->content ?? '';
                $fullContent .= $content;
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

            // Save the conversation to database
            if (Auth::check() && $request->conversation_id) {
                $conversation = ChatConversation::where('id', $request->conversation_id)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($conversation) {
                    // Only save if this is NOT the first message
                    // Save user message
                    if (count($request->conversation ?? []) > 0) {
                        // Save user message
                        ChatMessage::create([
                            'conversation_id' => $conversation->id,
                            'role' => 'user',
                            'content' => $request->message
                        ]);

                        // Save assistant message
                        ChatMessage::create([
                            'conversation_id' => $conversation->id,
                            'role' => 'assistant',
                            'content' => $fullContent
                        ]);
                    }

                    // Update conversation title if it's the first message
                    if ($conversation->title === 'New Chat') {
                        $title = substr($request->message, 0, 50);
                        if (strlen($request->message) > 50) {
                            $title .= '...';
                        }
                        $conversation->update(['title' => $title]);
                    }
                }
            }

            Log::info('Stream completed', ['total_chunks' => $chunkCount]);
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function saveChat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'messages' => 'nullable|array'
        ]);

        $conversation = ChatConversation::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
        ]);

        if ($request->messages) {
            foreach ($request->messages as $message) {
                ChatMessage::create([
                    'conversation_id' => $conversation->id,
                    'role' => $message['role'],
                    'content' => $message['content']
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id
        ]);
    }

    public function getChats(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversations = ChatConversation::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'title', 'created_at', 'updated_at']);

        return response()->json($conversations);
    }

    public function getConversation($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = ChatConversation::with('messages')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        return response()->json([
            'title' => $conversation->title,
            'messages' => $conversation->messages->map(function ($message) {
                return [
                    'role' => $message->role,
                    'content' => $message->content
                ];
            })
        ]);
    }

// DELETE CONVERSATION
    public function deleteConversation($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversation = ChatConversation::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        $conversation->delete();

        return response()->json(['success' => true]);
    }

}