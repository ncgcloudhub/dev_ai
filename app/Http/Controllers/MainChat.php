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

class MainChat extends Controller
{
    public function MainChatForm()
    {
        $userId = auth()->id(); // Get the authenticated user's ID
        $sessions = Session::with('messages') // Eager load the related messages
            ->where('user_id', $userId)
            ->get();

        return view('admin.test_chat.chat_main', compact('sessions'));
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

        $setting = AISettings::find(1);
        $openaiModel = $setting->openaimodel;

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

            $filePath = $file->store('uploads');
            $extension = $file->getClientOriginalExtension();
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

        if ($file && $file->getMimeType() === 'image/png') {
            $filePath = $file->store('pasted', 'public');
            $base64Image = $this->encodeImage(storage_path('app/public/' . $filePath));
            $response = $this->callOpenAIImageAPI($base64Image);
            $imageContent = $response['choices'][0]['message']['content'];

            // Update session variables and context
            $pastedImages[$filePath] = $imageContent;
            session(['pasted_images' => $pastedImages]);
            $context['pasted_image_content'] = $imageContent;
            session(['context' => $context]);

            // Provide default message if userMessage is empty
            if (empty($userMessage)) {
                $userMessage = 'Summarize this in 3 lines';
            }

            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            session(['conversation_history' => $conversationHistory]);
        }

        if (!empty($userMessage)) {
            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            session(['conversation_history' => $conversationHistory]);

            $context['latest_message'] = $userMessage;
            session(['context' => $context]);
            Log::info('Updated context with message: ', $context);

            Message::create([
                'session_id' => $sessionId,
                'user_id' => Auth::id(),
                'message' => $userMessage,
                'reply' => null,
            ]);

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
            ],
        ]);

        $user = Auth::user();

        Log::info('OpenAI API Response: ' . $response->getBody()->getContents());

        $data = json_decode($response->getBody(), true);
        $messageContent = $data['choices'][0]['message']['content'];
        $totalTokens = $data['usage']['total_tokens'];

        User::where('id', $user->id)->update([
            'tokens_used' => DB::raw('tokens_used + ' . $totalTokens),
            'tokens_left' => DB::raw('tokens_left - ' . $totalTokens),
        ]);

        Log::info('AI Response: ', ['content' => $messageContent]);

        // Save AI response to conversation history
        $conversationHistory[] = ['role' => 'assistant', 'content' => $messageContent];
        session(['conversation_history' => $conversationHistory]);

        $sessionId = session('session_id');

        Message::create([
            'session_id' => $sessionId,
            'user_id' => Auth::id(),
            'message' => null,
            'reply' => $messageContent,
        ]);

        if ($title) {
            $session = ModelsSession::find($sessionId);
            if (empty($session->title)) {
                $session->title = $title;
                $session->save();
            }
        }

        return response()->json([
            'message' => $messageContent,
            'title' => $title,
        ]);
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
