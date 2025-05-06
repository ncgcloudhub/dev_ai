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
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\ListItem;
use App\Models\AISettings;
use App\Models\Message;
use App\Models\Session as ModelsSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;



class AIChatController extends Controller
{



    // Dashboard Chat Admin
    public function send(Request $request)
    {
        $userMessage = $request->input('message');
        $file = $request->file('file');
        $openaiModel = $request->input('ai_model'); // Get the selected AI model

        // If no model is selected, use the default model from settings
        if (!$openaiModel) {
           
            $openaiModel = Auth::user()->selected_model;
        }

        // Initialize or retrieve uploaded files from session
        $uploadedFiles = session('uploaded_files', []);
        Log::info('Uploaded files: ', $uploadedFiles);

        // Initialize or retrieve pasted images from session
        $pastedImages = session('pasted_images', []);
        Log::info('Pasted images: ', $pastedImages);

        // Initialize or retrieve conversation history from session
        $conversationHistory = session('conversation_history', []);
        Log::info('Conversation history: ', $conversationHistory);

        // Initialize or retrieve context from session
        $context = session('context', []);
        Log::info('Context: ', $context);

        // Validate and handle the uploaded file
        if ($file) {
            $request->validate([
                'file' => 'mimes:txt,pdf,doc,docx,jpg,jpeg,png|max:20480', // Adjust the allowed file types and size as needed
            ]);

            // Store the file
            $filePath = $file->store('uploads');
            $extension = $file->getClientOriginalExtension();
            Log::info('File stored at: ', ['path' => $filePath, 'extension' => $extension]);

            // Read file content
            $fileContent = '';
            if (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
                $fileContent = $this->readFileContent(storage_path('app/' . $filePath), $extension);
            } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $base64Image = $this->encodeImage(storage_path('app/' . $filePath));
                $response = $this->callOpenAIImageAPI($base64Image);
                $fileContent = $response['choices'][0]['message']['content']; // Update fileContent with image analysis result
            }
            Log::info('File content: ', ['content' => $fileContent]);

            // Add or update uploaded file content in the session
            $uploadedFiles[$filePath] = $fileContent;
            session(['uploaded_files' => $uploadedFiles]);

            // Update context with the latest file content
            $context['file_content'] = $fileContent;
            session(['context' => $context]);
            Log::info('Updated context with file: ', $context);
        }

        // Handle pasted images (currently only handling PNG for example)
        if ($file && $file->getMimeType() === 'image/png') {
            $filePath = $file->store('pasted', 'public');
            $base64Image = $this->encodeImage(storage_path('app/public/' . $filePath));
            $response = $this->callOpenAIImageAPI($base64Image);
            $imageContent = $response['choices'][0]['message']['content'];

            $pastedImages[$filePath] = $imageContent;
            session(['pasted_images' => $pastedImages]);

            // Update context with the latest pasted image content
            $context['pasted_image_content'] = $imageContent;
            session(['context' => $context]);
        }

        // Add the user message to the conversation history
        if (!empty($userMessage)) {
            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            session(['conversation_history' => $conversationHistory]);

            // Update context with the latest message
            $context['latest_message'] = $userMessage;
            session(['context' => $context]);
            Log::info('Updated context with message: ', $context);

            $sessionId = Session::get('session_id');

            // Save user message to the database
            Message::create([
                'session_id' => $sessionId,
                'user_id' => Auth::id(),
                'message' => $userMessage,
                'reply' => null,
            ]);
        }


        // Define the messages array with the dynamic user input
        // $messages = [
        //     ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        // ];

        // Add file content if it exists
        if (!empty($context['file_content'])) {
            $messages[] = 
            [
                'role' => 'user', 
                'content' => $context['file_content']
            ];
        }

        // Add pasted image content if it exists
        if (!empty($context['pasted_image_content'])) {
            $messages[] = 
            [
                'role' => 'user', 
                'content' => $context['pasted_image_content']
            ];
        }


        // Add conversation history to messages array
        foreach ($conversationHistory as $message) {
            if (!is_null($message['content'])) {
                $messages[] = 
                [
                    'role' => $message['role'],
                    'content' => $message['content']
                ];
            }
        }

        // $messages = [];


        // // Prepare messages array with the latest user message and conversation history
        // $historyToSend = array_slice($conversationHistory, -10); // Send the last 10 messages to limit token usage
        // $messages = array_merge($messages, $historyToSend);

        // Ensure UTF-8 encoding for all message contents
        array_walk_recursive($messages, function (&$item, $key) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', mb_detect_encoding($item));
            }
        });

        Log::info('Messages to send to API: ', $messages);

        // Make API call
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $openaiModel, // Use the appropriate model name
                'messages' => $messages,
            ],
        ]);

        $user = Auth::user();

        Log::info('OpenAI API Response: ' . $response->getBody()->getContents());

        $data = json_decode($response->getBody(), true);
        $messageContent = $data['choices'][0]['message']['content'];
        $totalTokens = $data['usage']['total_tokens'];
        $sessionId = Session::get('session_id');

        // Words Increment
        User::where('id', $user->id)->update([
            'tokens_used' => DB::raw('tokens_used + ' . $totalTokens),
            'tokens_left' => DB::raw('tokens_left - ' . $totalTokens),
        ]);

        Log::info('AI Response: ', ['content' => $messageContent]);

        // Save the AI's reply to the database
        Message::create([
            'session_id' => $sessionId,
            'user_id' => Auth::id(),
            'message' => null,
            'reply' => $messageContent,
        ]);

        // Return the response
        return response()->json([
            'message' => $messageContent,
        ]);
    }



    // GET MESSAGES TEST
    public function getSessionMessages($id)
    {
        // Fetch the session with its messages
        $session = \App\Models\Session::with(['messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->find($id);
    
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }
    
        // Store the session ID in the Laravel session
        session(['session_id' => $id]);
    
        // Format messages to include role and file information
        $formattedMessages = $session->messages->map(function ($message) {
            $filePath = $message->file_path ?? null;
            $isImage = $filePath && preg_match('/\.(jpg|jpeg|png|gif)$/i', $filePath);
    
            return [
                'content' => $message->message ?? $message->reply,
                'file_path' => $filePath,
                'is_image' => $isImage,
                'role' => $message->message ? 'user' : 'assistant',
                'created_at' => $message->created_at->toDateTimeString(),
                'id' => $message->id,
            ];
        })->toArray();
    
        // Store the conversation history in the session
        $conversationHistory = array_map(function ($message) {
            return ['role' => $message['role'], 'content' => $message['content']];
        }, $formattedMessages);
        session(['conversation_history' => $conversationHistory]);
    
        // Retrieve context from the session
        $context = json_decode($session->context, true) ?? [
            'uploaded_files' => [],
            'pasted_images' => [],
            'conversation_history' => [],
            'context' => [],
        ];
    
        // Clear uploaded files and pasted images in the session
        session(['uploaded_files' => []]);
        session(['pasted_images' => []]);
        session(['context' => []]);
    
        // Save the updated context back to the session
        $session->context = json_encode($context);
        $session->save();
    
        // Return the formatted messages and context in JSON format
        return response()->json([
            'messages' => $formattedMessages,
            'context' => $context,
        ]);
    }
    

    // NEW SESSION
    public function newSession(Request $request)
    {
        // Clear session data
        session()->forget(['uploaded_files', 'conversation_history', 'context', 'pasted_images']);

        // Generate a new session ID and store it
        $token = bin2hex(random_bytes(16)); // Generate a unique session ID

        // Log the new session in the database
        $session = ModelsSession::create([
            'session_token' => $token,
            'user_id' => Auth::id(),
            'session_start' => now(),
            'created_at' => now(),
        ]);

        // Store the session ID in the Laravel session
        session(['session_id' => $session->id]);

        return response()->json(['success' => true, 'session_id' => $session->id]);
    }

    public function checkUserSession(Request $request)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();

            // Query the sessions table to find any active session for the authenticated user
            $session = ModelsSession::where('user_id', $userId)->first();

            if ($session) {
                // User has an active session in the database
                return response()->json(['hasSession' => true, 'userId' => $userId]);
            } else {
                // User does not have an active session
                return response()->json(['hasSession' => false]);
            }
        } else {
            // User is not authenticated
            return response()->json(['hasSession' => false]);
        }
    }

    // public function clearSession(Request $request)
    // {
    //     Session::forget('uploaded_files');
    //     Session::forget('conversation_history');

    //     return redirect()->back(); // Redirect back to the previous page
    // }


    private function readFileContent($filePath, $extension)
    {
        if ($extension == 'pdf') {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            $content = $pdf->getText();
        } elseif (in_array($extension, ['doc', 'docx'])) {
            $phpWord = WordIOFactory::load($filePath);
            $sections = $phpWord->getSections();
            $content = '';
            foreach ($sections as $section) {
                $elements = $section->getElements();
                foreach ($elements as $element) {
                    if ($element instanceof Text) {
                        $content .= $element->getText() . "\n";
                    } elseif ($element instanceof TextRun) {
                        foreach ($element->getElements() as $textElement) {
                            if ($textElement instanceof Text) {
                                $content .= $textElement->getText() . "\n";
                            }
                        }
                    } elseif ($element instanceof Title) {
                        $content .= $element->getText() . "\n";
                    } elseif ($element instanceof Table) {
                        foreach ($element->getRows() as $row) {
                            foreach ($row->getCells() as $cell) {
                                foreach ($cell->getElements() as $cellElement) {
                                    if ($cellElement instanceof Text) {
                                        $content .= $cellElement->getText() . "\t";
                                    } elseif ($cellElement instanceof TextRun) {
                                        foreach ($cellElement->getElements() as $textElement) {
                                            if ($textElement instanceof Text) {
                                                $content .= $textElement->getText() . "\t";
                                            }
                                        }
                                    } elseif ($cellElement instanceof ListItem) {
                                        $content .= $cellElement->getText() . "\n";
                                    }
                                }
                                $content .= "\n";
                            }
                        }
                    } elseif ($element instanceof ListItem) {
                        $content .= $element->getText() . "\n";
                    }
                }
            }
        } else {
            $content = file_get_contents($filePath);
        }

        return $content;
    }

    private function encodeImage($filePath)
    {
        $imageContent = file_get_contents($filePath);
        return base64_encode($imageContent);
    }

    private function callOpenAIImageAPI($base64Image)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => 'What’s in this image?'],
                        ['type' => 'image_url', 'image_url' => [
                            'url' => 'data:image/jpeg;base64,' . $base64Image,
                        ]],
                    ],
                ],
            ],
            'max_completion_tokens' => 300,
        ]);

        return $response;
    }
}
    // END Dashboard Chat Admin
