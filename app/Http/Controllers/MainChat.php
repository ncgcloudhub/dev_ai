<?php

namespace App\Http\Controllers;

use App\Models\AISettings;
use App\Models\Message;
use App\Models\Session;
use Illuminate\Http\Request;
use App\Models\Session as ModelsSession;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use OpenAI\Laravel\Facades\OpenAI;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\ListItem;
use Symfony\Component\HttpFoundation\StreamedResponse;


class MainChat extends Controller
{
    public function MainChatForm()
    {
        $userId = auth()->id(); // Get the authenticated user's ID
        $sessions = Session::with('messages') // Eager load the related messages
            ->where('user_id', $userId)
            ->get();

            return view('backend.chattermate.chat_main', [
                'seenTourSteps' => $user->tour_progress ?? [],
                'sessions' => $sessions,
            ]);
    }

    // NEW SESSION
    public function MainNewSession(Request $request)
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
        Log::info('Session ID: ', ['session_id' => session('session_id')]);

        return response()->json(['success' => true, 'session_id' => $session->id]);
    }



    // Dashboard Chat Admin
    public function send(Request $request)
    {
        $userMessage = $request->input('message');
        $file = $request->file('file');
        $title = $request->input('title');
        $sessionId = session('session_id');

        Log::info('Inside Message Session ID: ', ['session_id' => session('session_id')]);

        // Get the currently authenticated user
        $user = auth()->user();
        // Retrieve the selected model from the `selected_model` field
        $openaiModel = $user->selected_model;
        Log::info('before: ' . $openaiModel);

        // Retrieve the session and its messages from the database
        $session = ModelsSession::with('messages')->find($sessionId);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $uploadedFiles = session('uploaded_files', []);
        Log::info('Uploaded files: ', $uploadedFiles);

        $pastedImages = session('pasted_images', []);
        Log::info('Pasted images: ', $pastedImages);

        $conversationHistory = session('conversation_history', []);
        Log::info('Conversation history: ', $conversationHistory);

        $context = session('context', []);
        Log::info('Context: ', $context);

        if ($file) {
            $request->validate([
                'file' => 'mimes:txt,pdf,doc,docx,jpg,jpeg,png|max:20480',
            ]);
        
            // Get the original filename and extension
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
        
             // Determine the file path and save the file
            if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                // Rename image file to include timestamp or any custom logic
                $newFilename = $originalFilename . '_' . time() . '.' . $extension;
                $filePath = $file->storeAs('uploads', $newFilename);
            } else {
                // Keep original name for other files
                $filePath = $file->storeAs('uploads', $file->getClientOriginalName());
            }
        
            Log::info('File stored at: ', ['path' => $filePath, 'extension' => $extension]);
        
            $fileContent = '';
            if (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
                $fileContent = $this->readFileContent(storage_path('app/' . $filePath), $extension);
            } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $base64Image = $this->encodeImage(storage_path('app/' . $filePath));
                $response = $this->callOpenAIImageAPI($base64Image);
                $fileContent = $response['choices'][0]['message']['content'];
            }

            Log::info('File content: ', ['content' => $fileContent]);
        
            // Update session variables and context
            $uploadedFiles[$filePath] = $fileContent;
            session(['uploaded_files' => $uploadedFiles]);
    
            $context['file_content'] = $fileContent;
            session(['context' => $context]);
        
            // Provide default message if userMessage is empty
            if (empty($userMessage)) {
                $userMessage = 'Summarize this in 3 lines';
            }
    
            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            session(['conversation_history' => $conversationHistory]);
        }        

        // Handle image processing for generated similar images
        if ($file && in_array($file->getMimeType(), ['image/png', 'image/jpg', 'image/jpeg'])) {
            $filePath = $file->store('uploads', 'public');
            $base64Image = $this->encodeImage(storage_path('app/public/' . $filePath));
            $response = $this->callOpenAIImageAPI($base64Image);
            $imageContent = $response['choices'][0]['message']['content'];

            $pastedImages[$filePath] = $imageContent;
            session(['pasted_images' => $pastedImages]);
            $context['pasted_image_content'] = $imageContent;
            session(['context' => $context]);

            if (empty($userMessage)) {
                $userMessage = 'Summarize this in 3 lines';
            }

            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            session(['conversation_history' => $conversationHistory]);
        }

        if (!empty($userMessage)) {
            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            session(['conversation_history' => $conversationHistory]);
    
            // Check if the message is a command to generate an image similar to an attached image
            if (strpos(strtolower($userMessage), 'generate image similar to') !== false) {
                
                $promptImage = array_key_last($pastedImages);
                if ($promptImage) {
                    $base64Image = $this->encodeImage(storage_path('app/public/' . $promptImage));
                    $response = $this->callOpenAIImageAPI($base64Image);
                    $generatedPrompt = $response['choices'][0]['message']['content'];
    
                    // Generate similar image based on the extracted prompt
                    $imageURL = $this->generateImage($generatedPrompt);
    
                    // Add the generated image to the conversation history
                    $conversationHistory[] = [
                        'role' => 'assistant',
                        'content' => '
                            <div>
                                <a href="' . $imageURL . '" target="_blank">
                                    <img src="' . $imageURL . '" alt="Generated Image" style="width: 200px; height: 200px; cursor: pointer;">
                                </a>
                                <br>
                                <a href="' . $imageURL . '" download="generated-image.png" class="btn btn-primary" style="margin-top: 10px;">Download Image</a>
                            </div>'
                    ];
                    session(['conversation_history' => $conversationHistory]);
    
                    // Save AI response with image
                    Message::create([
                        'session_id' => $sessionId,
                        'user_id' => Auth::id(),
                        'message' => $userMessage,
                        'reply' => '
                            <div>
                                <a href="' . $imageURL . '" target="_blank">
                                    <img src="' . $imageURL . '" alt="Generated Image" style="width: 200px; height: 200px; cursor: pointer;">
                                </a>
                                <br>
                                <a href="' . $imageURL . '" download="generated-image.png" class="btn btn-primary" style="margin-top: 10px;">Download Image</a>
                            </div>'
                    ]);
    
                    return response()->json([
                        'message' => '
                            <div>
                                <a href="' . $imageURL . '" target="_blank">
                                    <img src="' . $imageURL . '" alt="Generated Image" style="width: 200px; height: 200px; cursor: pointer;">
                                </a>
                                <br>
                                <a href="' . $imageURL . '" download="generated-image.png" class="btn btn-primary" style="margin-top: 10px;">Download Image</a>
                            </div>',
                        'title' => $title,
                    ]);
                }
            }
    
            // Check if the message is a command to generate an image based on description
            if (strpos(strtolower($userMessage), 'generate image of') !== false) {
                $imageDescription = str_replace('generate image of', '', strtolower($userMessage));
                $imageURL = $this->generateImage($imageDescription);
    
                // Add the generated image to the conversation history
                $conversationHistory[] = [
                    'role' => 'assistant',
                    'content' => '
                        <div>
                            <a href="' . $imageURL . '" target="_blank">
                                <img src="' . $imageURL . '" alt="Generated Image" style="width: 200px; height: 200px; cursor: pointer;">
                            </a>
                            <br>
                            <a href="' . $imageURL . '" download="generated-image.png" class="btn btn-primary" style="margin-top: 10px;">Download Image</a>
                        </div>'
                ];
                session(['conversation_history' => $conversationHistory]);
    
                // Save AI response with image
                Message::create([
                    'session_id' => $sessionId,
                    'user_id' => Auth::id(),
                    'message' => $userMessage,
                    'reply' => null,
                ]);

                // Save AI response with image
                Message::create([
                    'session_id' => $sessionId,
                    'user_id' => Auth::id(),
                    'message' => null,
                        'reply' => '
                            <div>
                                <a href="' . $imageURL . '" target="_blank">
                                    <img src="' . $imageURL . '" alt="Generated Image" style="width: 200px; height: 200px; cursor: pointer;">
                                </a>
                                <br>
                                <a href="' . $imageURL . '" download="generated-image.png" class="btn btn-primary" style="margin-top: 10px;">Download Image</a>
                            </div>'
                    ]);
        
                    return response()->json([
                        'message' => '
                            <div>
                                <a href="' . $imageURL . '" target="_blank">
                                    <img src="' . $imageURL . '" alt="Generated Image" style="width: 200px; height: 200px; cursor: pointer;">
                                </a>
                                <br>
                                <a href="' . $imageURL . '" download="generated-image.png" class="btn btn-primary" style="margin-top: 10px;">Download Image</a>
                            </div>',
                        'title' => $title,
                    ]);
                }
        

            $context['latest_message'] = $userMessage;
            session(['context' => $context]);
            Log::info('Updated context with message: ', $context);

             // Check if $filePath is set and not empty
            $filePath = !empty($filePath) ? $filePath : null;

            Message::create([
                'session_id' => $sessionId,
                'user_id' => Auth::id(),
                'message' => $userMessage,
                'reply' => null,
                'file_path' => $filePath,
            ]);
            Log::info('File Path: ', ['pathss' => $filePath]);

            // Save context to database
            $session->context = json_encode($context);
            $session->save();
        } else {
            $conversationHistory = json_decode($session->messages->pluck('message')->toJson(), true);
        }

        // Update session data
        session([
            'conversation_history' => $conversationHistory,
            'context' => $context,
        ]);

        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ];

        // Add file content if available
        if (!empty($context['file_content'])) {
            $messages[] = ['role' => 'user', 'content' => $context['file_content']];
        }

        // Add pasted image content if available
        if (!empty($context['pasted_image_content'])) {
            $messages[] = ['role' => 'user', 'content' => $context['pasted_image_content']];
        }

        // Add all conversation history messages
        foreach ($conversationHistory as $message) {
            if (is_array($message) && isset($message['content']) && isset($message['role'])) {
                if (!is_null($message['content'])) {
                    $messages[] = ['role' => $message['role'], 'content' => $message['content']];
                }
            } else {
                Log::warning('Invalid message structure:', ['message' => $message]);
            }
        }


        array_walk_recursive($messages, function (&$item, $key) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', mb_detect_encoding($item));
            }
        });

        Log::info('Messages to send to API: ', $messages);

        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $openaiModel,
                'messages' => $messages,
                'stream' => true,
            ],
            'stream' => true,
        ]);
        
        $user = Auth::user();
        $sessionId = session('session_id');
        
        // Log the full response for debugging purposes
        Log::info('OpenAI API Streaming Response Started');
        
        // Return a StreamedResponse to send data incrementally to the client
        return new StreamedResponse(function() use ($response, $user, $sessionId, $title) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            
            $body = $response->getBody();
            $messageContent = '';
            $buffer = '';
        
            while (!$body->eof()) {
                $chunk = $body->read(1024);
                $buffer .= $chunk;
        
                $lines = explode("\n", $buffer);
                $buffer = array_pop($lines);
        
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (strpos($line, 'data:') === 0) {
                        $data = trim(substr($line, strlen('data:')));
                        if ($data === '[DONE]') {
                            // Save the AI's reply to the database
                            Message::create([
                                'session_id' => $sessionId,
                                'user_id' => $user->id,
                                'message' => null,
                                'reply' => $messageContent, // Encode newlines and special characters
                            ]);
        
                            if ($title) {
                                $session = ModelsSession::find($sessionId);
                                if (empty($session->title)) {
                                    $session->title = $title;
                                    $session->save();
                                }
                            }
        
                            echo "event: done\n";
                            echo "data: [DONE]\n\n";
                            ob_flush();
                            flush();
                            break 2;
                        } else {
                            try {
                                $parsedData = json_decode($data, true);
                                if (isset($parsedData['choices'][0]['delta']['content'])) {
                                    $content = $parsedData['choices'][0]['delta']['content'];
                                    $messageContent .= $content;
        
                                    echo "data: " . json_encode($content) . "\n\n";
                                    ob_flush();
                                    flush();
                                }
                            } catch (\Exception $e) {
                                Log::error('Error parsing JSON data: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
             

    }


    private function generateImage($description)
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/images/generations', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'dall-e-3', 
                'prompt' => $description,
                'size' => '1024x1024', 
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $imageUrl = $data['data'][0]['url']; // Adjust according to actual API response structure

        return $imageUrl;
    }



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
        return base64_encode(file_get_contents($filePath));
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
            'max_tokens' => 300,
        ]);

        return $response;
    }


    // DELETE
    public function delete(Request $request)
    {
        $sessionId = $request->input('session_id');

        try {
            $session = Session::findOrFail($sessionId);
            $session->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // DELETE
    public function TitleEdit(Request $request)
    {

        $sessionId = $request->input('session_id');
        $newTitle = $request->input('new_title');

        try {
            $session = Session::findOrFail($sessionId);
            $session->title = $newTitle;
            $session->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
