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
use App\Models\ExpertConversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Symfony\Component\HttpFoundation\StreamedResponse;

use GuzzleHttp\Client;
use OpenAI\Laravel\Facades\OpenAI;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\Title;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\ListItem;


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

        $file = $request->file('file');

        // Fetch expert details
        $expertId = $request->input('expert');
        $expert = Expert::findOrFail($expertId);
        $expertRole = $expert->role;
        $expertImage = $expert->image;
        // Fetch the system instruction from the database
        $expertInstruction = $expert->expertise;


        $openaiModel = Auth::user()->selected_model;
        

        // Check if the expert instruction is empty
        if (empty($expertInstruction)) {
            // If no instruction is provided, use the default instruction
            $expertInstruction = "You are now playing the role of $expertRole. As an expert in $expertRole for the past 40 years, I need your help. Please answer this: \"$userInput\". If anyone asks any questions outside of $expertRole, please reply as I am not programmed to respond.";
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
                $filePath = $file->storeAs('expertChat', $newFilename);
            } else {
                // Keep original name for other files
                $filePath = $file->storeAs('expertChat', $file->getClientOriginalName());
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
            if (empty($userInput)) {
                $userInput = 'Summarize this in 3 lines';
            }
    
            $conversationHistory[] = ['role' => 'user', 'content' => $userInput];
            session(['conversation_history' => $conversationHistory]);
        }
        
         // Handle image processing for generated similar images
         if ($file && in_array($file->getMimeType(), ['image/png', 'image/jpg', 'image/jpeg'])) {
            $filePath = $file->store('expertChat', 'public');
            $base64Image = $this->encodeImage(storage_path('app/public/' . $filePath));
            $response = $this->callOpenAIImageAPI($base64Image);
            $imageContent = $response['choices'][0]['message']['content'];

            $pastedImages[$filePath] = $imageContent;
            session(['pasted_images' => $pastedImages]);
            $context['pasted_image_content'] = $imageContent;
            session(['context' => $context]);

            if (empty($userInput)) {
                $userInput = 'Summarize this in 3 lines';
            }

            $conversationHistory[] = ['role' => 'user', 'content' => $userInput];
            session(['conversation_history' => $conversationHistory]);
        }


        if (!empty($userInput)) {
            $conversationHistory[] = ['role' => 'user', 'content' => $userInput];
            session(['conversation_history' => $conversationHistory]);
    
            $context['latest_message'] = $userInput;
            session(['context' => $context]);
            Log::info('Updated context with message: ', $context);

             // Check if $filePath is set and not empty
            $filePath = !empty($filePath) ? $filePath : null;
       
            Log::info('File Path: ', ['pathss' => $filePath]);
        } 

          // Update session data
          session([
            'conversation_history' => $conversationHistory,
            'context' => $context,
        ]);


        // Define the messages array with the dynamic user input
        $messages = 
        [
            [
                'role' => 'system', 
                'content' => $expertInstruction
            ],
        ];

        // Add file content if available
        if (!empty($context['file_content'])) {
            $messages[] = 
            [
                'role' => 'user', 
                'content' => $context['file_content']
            ];
        }

        // Add pasted image content if available
        if (!empty($context['pasted_image_content'])) {
            $messages[] = 
            [
                'role' => 'user', 
                'content' => $context['pasted_image_content']
            ];
        }

        // Add all conversation history messages
        foreach ($conversationHistory as $message) {
            if (is_array($message) && isset($message['content']) && isset($message['role'])) {
                if (!is_null($message['content'])) {
                    $messages[] = 
                    [
                        'role' => $message['role'], 
                        'content' => $message['content']
                    ];
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

        // Initialize HTTP client
    $client = new Client();

    // Make a request to the OpenAI API with streaming enabled
    $response = $client->post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . config('app.openai_api_key'),
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'model' => $openaiModel, // Use the appropriate model name
            'messages' => $messages,
            'temperature' => 0,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'stream' => true,
        ],
        'stream' => true,
    ]);

    // Log the start of streaming
    Log::info('Expert Chat Streaming Response Started', [
        'model' => $openaiModel,
        'messages' => $messages,
        'expert_id' => $expertId,
    ]);

    // Return a StreamedResponse to send data incrementally to the client
    return new StreamedResponse(function() use ($response, $expertId, $userInput, $expertInstruction, $expertImage) {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        $body = $response->getBody();
        $messageContent = '';
        $buffer = '';

        Log::info('Streaming Response Initialized for Expert Chat', [
            'expert_id' => $expertId,
        ]);

        // Stream the response chunk by chunk
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
                        ExpertConversation::create([
                            'user_id' => Auth::id(),
                            'expert_id' => $expertId,
                            'role' => 'user',
                            'message' => $userInput,
                            'file_path' => $filePath ?? null,
                        ]);
                    
                        // After receiving the expert's reply, save it
                        Log::info('Final expert reply:', ['reply' => $messageContent]);  // Log the final reply
                    
                        ExpertConversation::create([
                            'user_id' => Auth::id(),
                            'expert_id' => $expertId,
                            'role' => 'expert',
                            'message' => $messageContent,  // Save the reply
                        ]);

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

    public function getConversation($expertId)
    {
        session()->forget(['conversation_history', 'context', 'uploaded_files', 'pasted_images']);
        $messages = ExpertConversation::where('expert_id', $expertId)->get();
        return response()->json(['messages' => $messages]);
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
