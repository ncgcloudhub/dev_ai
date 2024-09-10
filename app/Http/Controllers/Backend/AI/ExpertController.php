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
use App\Models\AISettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


use App\Models\Message;
use App\Models\Session;

use App\Models\Session as ModelsSession;
use App\Models\User;

use Illuminate\Support\Facades\DB;

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


        // Fetch OpenAI settings
        $setting = AISettings::find(1);
        $openaiModel = $setting->openaimodel;

        // Check if the expert instruction is empty
        if (empty($expertInstruction)) {
            // If no instruction is provided, use the default instruction
            $expertInstruction = "You are now playing the role of $expertRole. As an expert in $expertRole for the past 40 years, I need your help. Please answer this: \"$userInput\". If anyone asks any questions outside of $expertRole, please reply as I am not programmed to respond.";
        }

        $uploadedFiles = session('uploaded_files', []);
        Log::info('Uploaded files: ', $uploadedFiles);

        $pastedImages = session('pasted_images', []);
        Log::info('Pasted images: ', $pastedImages);

      

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
    
        
            // Provide default message if userMessage is empty
            if (empty($userInput)) {
                $userInput = 'Summarize this in 3 lines';
            }
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
          
        }
         
        // Define the messages array with the dynamic user input
        $messages = [
            ['role' => 'system', 'content' => $expertInstruction],
            ['role' => 'user', 'content' => $userInput]
        ];

        // Add file content if available
        if (!empty($context['file_content'])) {
            $messages[] = ['role' => 'user', 'content' => $context['file_content']];
        }

        // Add pasted image content if available
        if (!empty($context['pasted_image_content'])) {
            $messages[] = ['role' => 'user', 'content' => $context['pasted_image_content']];
        }

        array_walk_recursive($messages, function (&$item, $key) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8', mb_detect_encoding($item));
            }
        });
        

        Log::info('Messages to send to API: ', $messages);

        // Make API request to OpenAI
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('app.openai_api_key'),
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' =>  $openaiModel, // Use the appropriate model name
            'messages' => $messages,
        ]);

        $data = json_decode($response->getBody(), true);
        Log::info('Response: ', $data);

        // Extract completion from API response
        // $content = $response->json('choices.0.text');
        if (isset($response['choices'][0]['message']['content'])) {
            $messageContent = $response['choices'][0]['message']['content'];
        } else {
            $messageContent = 'Error in response';
        }

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
        $aiChatMessage->response = $messageContent;
        $aiChatMessage->words = str_word_count($messageContent);
        $aiChatMessage->save();
        // TEST SAVE CHAT END

        // Return response to the client
        return response()->json([
            'prompt' => $expertInstruction,
            'content' => $messageContent,
            'expert_image' => $expertImage
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
