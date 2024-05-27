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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Session;


class AIChatController extends Controller
{

    // public function sendMessages(Request $request)
    // {
    //     // Get user input from the request
    //     $userInput = $request->input('message');

    //     // Fetch expert details
    //     $expertId = $request->input('expert');
    //     $expert = Expert::findOrFail($expertId);
    //     $expertRole = $expert->role;
    //     $expertImage = $expert->image;
    //     // Fetch the system instruction from the database
    //     $expertInstruction = $expert->expertise;


    //     // Fetch OpenAI settings
    //     $setting = AISettings::find(1);
    //     $openaiModel = $setting->openaimodel;

    //     // Check if the expert instruction is empty
    //     if (empty($expertInstruction)) {
    //         // If no instruction is provided, use the default instruction
    //         $expertInstruction = "You are now playing the role of $expertRole. As an expert in $expertRole for the past 40 years, I need your help. Please answer this: \"$userInput\". If anyone asks any questions outside of $expertRole, please reply as I am not programmed to respond.";
    //     }


    //     // Define the messages array with the dynamic user input
    //     $messages = [
    //         ['role' => 'system', 'content' => $expertInstruction],
    //         ['role' => 'user', 'content' => $userInput]
    //     ];

    //     // Make API request to OpenAI
    //     $response = Http::withHeaders([
    //         'Content-Type' => 'application/json',
    //         'Authorization' => 'Bearer ' . config('app.openai_api_key'),
    //     ])->post('https://api.openai.com/v1/chat/completions', [
    //         'model' =>  $openaiModel, // Use the appropriate model name
    //         'messages' => $messages,
    //         'temperature' => 0,
    //         'top_p' => 1,
    //         'frequency_penalty' => 0,
    //         'presence_penalty' => 0,

    //     ]);

    //     // Extract completion from API response
    //     // $content = $response->json('choices.0.text');
    //     $content = $response->json('choices.0.message.content');


    //     // TEST SAVE CHAT
    //     // Save chat data to the database
    //     $aiChat = new AiChat();
    //     $aiChat->user_id = Auth::id(); // Assuming you are using authentication
    //     $aiChat->expert_id = $expertId;
    //     $aiChat->title = $userInput; // You may want to change this
    //     $aiChat->total_words = str_word_count($userInput);
    //     $aiChat->save();

    //     $aiChatMessage = new AiChatMessage();
    //     $aiChatMessage->ai_chat_id = $aiChat->id;
    //     $aiChatMessage->user_id = Auth::id(); // Assuming you are using authentication
    //     $aiChatMessage->prompt = $expertInstruction;
    //     $aiChatMessage->response = $content;
    //     $aiChatMessage->words = str_word_count($content);
    //     $aiChatMessage->save();
    //     // TEST SAVE CHAT END

    //     // Return response to the client
    //     return response()->json([
    //         'prompt' => $expertInstruction,
    //         'content' => $content,
    //         'expert_image' => $expertImage
    //     ]);
    // }

    // Dashboard Chat Admin
    public function send(Request $request)
    {
        $userMessage = $request->input('message');
        $file = $request->file('file');
        $openaiModel = $request->input('ai_model'); // Get the selected AI model

        // If no model is selected, use the default model from settings
        if (!$openaiModel) {
            $setting = AISettings::find(1);
            $openaiModel = $setting->openaimodel;
        }

        // Initialize or retrieve uploaded files from session
        $uploadedFiles = session('uploaded_files', []);

        // Initialize or retrieve conversation history from session
        $conversationHistory = session('conversation_history', []);

        // Initialize or retrieve context from session
        $context = session('context', []);

        // Validate and handle the uploaded file
        if ($file) {
            $request->validate([
                'file' => 'mimes:txt,pdf,doc,docx,jpg,jpeg,png|max:20480', // Adjust the allowed file types and size as needed
            ]);

            // Store the file
            $filePath = $file->store('uploads');
            $extension = $file->getClientOriginalExtension();

            // Read file content
            $fileContent = '';
            if (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
                $fileContent = $this->readFileContent(storage_path('app/' . $filePath), $extension);
            } elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $base64Image = $this->encodeImage(storage_path('app/' . $filePath));
                $response = $this->callOpenAIImageAPI($base64Image);
                $fileContent = $response['choices'][0]['message']['content']; // Update fileContent with image analysis result
            }

            // Add or update uploaded file content in the session
            $uploadedFiles[$filePath] = $fileContent;
            session(['uploaded_files' => $uploadedFiles]);

            // Update context with the latest file
            $context = [
                'type' => 'file',
                'timestamp' => time(),
                'content' => $fileContent,
            ];
            session(['context' => $context]);
        }

        // Retrieve conversation history from session
        $conversationHistory = session('conversation_history', []);

        // Add the user message to the conversation history
        if (!empty($userMessage)) {
            $conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            session(['conversation_history' => $conversationHistory]);

            // Update context with the latest message
            $context = [
                'type' => 'message',
                'timestamp' => time(),
                'content' => $userMessage,
            ];
            session(['context' => $context]);
        }

        // Define the messages array with the dynamic user input
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ];

        // Add conversation history to messages array
        foreach ($conversationHistory as $message) {
            $messages[] = ['role' => $message['role'], 'content' => $message['content']];
        }

        if ($context['type'] == 'file') {
            $messages[] = ['role' => 'user', 'content' => $context['content']];
        } elseif ($context['type'] == 'message') {
            $messages[] = ['role' => 'user', 'content' => $context['content']];
        }

        // If user message is not empty, include it in messages array
        if (!empty($userMessage)) {
            $messages[] = ['role' => 'user', 'content' => $userMessage];
        }

        // Add uploaded file content to messages if it exists
        foreach ($uploadedFiles as $uploadedFile => $fileContent) {
            $messages[] = ['role' => 'user', 'content' => $fileContent];
        }

        // Ensure UTF-8 encoding for all message contents
        array_walk_recursive($messages, function (&$item, $key) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', mb_detect_encoding($item));
            }
        });


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

        $data = json_decode($response->getBody(), true);
        $message = $data['choices'][0]['message']['content'];

        // Return the response
        return response()->json([
            'message' => $message,
        ]);
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
            'max_tokens' => 300,
        ]);

        return $response;
    }
}
    // END Dashboard Chat Admin
