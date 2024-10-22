<?php

namespace App\Http\Controllers;

use App\Models\educationContent;
use App\Models\EducationTools;
use App\Models\GradeClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use OpenAI;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Parsedown;
use PDF;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EducationController extends Controller
{
    // USER SECTION
    public function educationForm()
    {
        $classes = GradeClass::with('subjects')->orderBy('id', 'asc')->get();

        $userId = auth()->id(); // Get the authenticated user's ID

        $educationContents = EducationContent::where('user_id', $userId)
        ->orderBy('created_at', 'desc') // Order by the latest entries
        ->limit(5) // Limit to the last 5 records
        ->get();

        return view('backend.education.create_content', [
        'classes' => $classes,
        'educationContents' => $educationContents, // Pass the contents to the view
        ]);
    }

    public function getUserContents()
    {
        $userId = auth()->id(); // Get the authenticated user's ID

        // Retrieve all unique grades and related subjects for the user
        $educationContents = EducationContent::where('user_id', $userId)
            ->with('gradeClass', 'subject')
            ->get();
    
        // Group by grade and get related subjects
        $grades = $educationContents->groupBy('grade_id');
        $gradeIds = $grades->keys(); // Get grade IDs
    
        // Get classes with subjects related to those grades
        $classes = GradeClass::with(['subjects' => function ($query) use ($gradeIds) {
            $query->whereIn('id', function ($subQuery) use ($gradeIds) {
                $subQuery->select('subject_id')
                    ->from('education_contents')
                    ->whereIn('grade_id', $gradeIds)
                    ->distinct();
            });
        }])->whereIn('id', $gradeIds)->get();
    
    return view('backend.education.user_generated_content', [
            'classes' => $classes,
        ]);
    }


    public function getContentsBySubject(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $userId = auth()->id(); // Get the authenticated user's ID

        // Retrieve contents for the selected subject
        $contents = educationContent::where('user_id', $userId)
            ->where('subject_id', $subjectId)
            ->orderBy('status', 'desc')
            ->with('gradeClass', 'subject')
            ->get();

        return response()->json([
            'contents' => $contents,
        ]);
    }

    public function getContentById(Request $request)
    {
        $contentId = $request->input('content_id');
        $content = EducationContent::findOrFail($contentId);

        $parsedown = new Parsedown();
        $content->generated_content = $parsedown->text($content->generated_content);

        return response()->json([
            'content' => $content,
        ]);
    }

    public function edit($id)
    {
        $content = EducationContent::findOrFail($id);
        $classes = GradeClass::all();

        return view('backend.education.edit_content', compact('content', 'classes'));
    }

    public function update(Request $request)
    {
       
        $educationContent = EducationContent::findOrFail($request->id);
       
        if (!$educationContent) {
            return response()->json(['error' => 'Content not found'], 404);
        }

        $user = auth()->user();
        $openaiModel = $user->selected_model;
        $apiKey = config('app.openai_api_key');
        $client = OpenAI::client($apiKey);
    
        $gradeId = $request->input('grade_id');
        $grade = GradeClass::findOrFail($gradeId);
        $gradeName = $grade->grade;
        
        $subjectId = $request->input('subject_id');
        $subject = Subject::findOrFail($subjectId);
        $subjectName = $subject->name;
    
        // Create the updated prompt for OpenAI
        $prompt = 'I need to create study contents for my students. The content type will be ' . $request->question_type . '. Give the answer in different page so when I prnt the questions, only the questions should be printed. It is for ' . $gradeName . ' and the subject is ' . $subjectName . ' for students of age ' . $request->age . '. The question difficulty is ' . $request->difficulty_level . ' with ' . $request->tone . ' tone and the persona is ' . $request->persona . '. Include ' . $request->points . ' questions.';
    
        $prompt .= ' The question topic is ' . $request->topic;
    
        if ($request->additional_details) {
            $prompt .= ', these are the additional points that should be included: ' . $request->additional_details;
        }
    
        if ($request->examples) {
            $prompt .= ', I am providing some examples for better understanding: ' . $request->examples;
        }
    
        if ($request->negative_words) {
            $prompt .= ', And also please do not include these words in the content: ' . $request->negative_words;
        }
    
        // Generate the content using OpenAI API
        $response = $client->chat()->create([
            "model" => $openaiModel,
            'messages' => 
            [
                [
                    'role' => 'system', 
                    'content' => 'You are a helpful assistant.'
                ],

                [
                    'role' => 'user', 
                    'content' => $prompt
                ],
            ],
        ]);
    
        // Get the response content
        $content = $response['choices'][0]['message']['content'];
    
        // Update the existing content in the database
        $educationContent->update([
            'grade_id' => 1,
            'subject_id' => $subjectId,
            'age' => $request->input('age'),
            'difficulty_level' => $request->input('difficulty_level'),
            'tone' => $request->input('tone'),
            'persona' => $request->input('persona'),
            'topic' => $request->input('topic'),
            'negative_words' => $request->input('negative_words'),
            'points' => $request->input('points'),
            'additional_details' => $request->input('additional_details'),
            'example' => $request->input('examples'),
            'reference' => $request->input('reference'),
            'generated_content' => $content,
            'prompt' => $prompt,
            'status' => 'updated',
        ]);
    
        // Stream the updated response
        return response()->stream(function () use ($content) {
            $chunks = explode("\n", $content);
            foreach ($chunks as $chunk) {
                echo $chunk . "<br/>";
                ob_flush();
                flush();
                sleep(1); // Simulate delay between chunks
            }
        });
    }



    public function deleteContent($id)
    {
        $content = educationContent::find($id);
    
        if (!$content) {
            return response()->json(['error' => 'Content not found'], 404);
        }
    
        // Ensure that the user has permission to delete the content
        if ($content->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $content->delete();
    
        return response()->json(['success' => 'Content deleted successfully']);
    }

    public function downloadPDF($id)
    {
        // Find the content
        $content = educationContent::findOrFail($id);

        if (!$content) {
            return redirect()->back()->with('error', 'Content not found');
        }

        // Generate the HTML for the PDF
        $pdfHtml = view('backend.education.education_pdf', ['content' => $content])->render();

        // Generate and download the PDF
        $pdf = PDF::loadHTML($pdfHtml);

        // Download the generated PDF
        return $pdf->download('content_' . $content->id . '.pdf');
    }
    
    public function markAsComplete(Request $request, $id)
    {
        Log::info('Received request to mark content as completed', ['id' => $id]);
    
        $content = educationContent::find($id);
    
        if (!$content) {
            return response()->json(['error' => 'Content not found'], 404);
        }
    
        if ($content->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
       // Toggle the content status
        if ($content->status === 'completed') {
            $content->status = 'generated';
            $message = 'Content marked as incomplete';
        } else {
            $content->status = 'completed';
            $message = 'Content marked as completed';
        }

        $content->save();
    
        return response()->json(['success' => true, 'status' => $content->status, 'message' => $message]);
    }
    

    public function educationContent(Request $request)
    {

        set_time_limit(0);

        $user = auth()->user();
        $openaiModel = $user->selected_model;
        $apiKey = config('app.openai_api_key');
        $client = OpenAI::client($apiKey);
    
        $gradeId = $request->input('grade_id');
        $grade = GradeClass::find($gradeId);
        $gradeName = $grade->grade;
    
        $subjectId = $request->input('subject_id');
        $subject = Subject::find($subjectId);
        $subjectName = $subject->name;
    
        // Basic Info
        $prompt = 'I need to create educational content for my students. Here are the details: ';

        // Add Grade/Class
        if ($request->grade_id) {
           
            $prompt .= 'The content is for Grade/Class: ' . $gradeName . '. ';
        }

        // Add Subject
        if ($request->subject_id) {
            // Assuming you have a way to retrieve the subject name based on subject_id
            $subject = 'Subject: ' . $subjectName . '. ';
            $prompt .= $subject;
        }

        // Add Age Group
        if ($request->age) {
            $prompt .= 'Target age group: ' . $request->age . '. ';
        }

        // Add Difficulty Level
        if ($request->difficulty_level) {
            $prompt .= 'Content Difficulty Level: ' . $request->difficulty_level . '. ';
        }

        // Add Tone
        if ($request->tone) {
            $prompt .= 'Tone: ' . $request->tone . '. ';
        }

        // Add Persona
        if ($request->persona) {
            $prompt .= 'Persona: ' . $request->persona . '. ';
        }

        // Add Topic
        if ($request->topic) {
            $prompt .= 'Topic: ' . $request->topic . '. ';
        }

        // Add Topic Description
        if ($request->additional_details) {
            $prompt .= 'Description: ' . $request->additional_details . '. ';
        }

        // Add Content Type
        if ($request->content_type) {
            $prompt .= 'Content Type: ' . $request->content_type . '. ';
        }

        // Add Language Style
        if ($request->language_style) {
            $prompt .= 'Language Style: ' . $request->language_style . '. ';
        }

        // Add Desired Length
        if ($request->desired_length) {
            $prompt .= 'Desired Length: ' . $request->desired_length . '. ';
        }

        // Add Negative Words
        if ($request->negative_word) {
            $prompt .= 'Please avoid the following negative words: ' . $request->negative_word . '. ';
        }

        // Additional Instructions
        if ($request->additional_instruction) {
            $prompt .= 'Additional Instructions: ' . $request->additional_instruction . '. ';
        }

        // Add Generate Questions if checked
        if ($request->generate_questions) {
            $prompt .= 'Generate ' . $request->number_of_questions . ' questions of type ' . $request->question_type . ' with difficulty level ' . $request->question_difficulty_level . '. ';
        }

        // Add Generate Answer if checked
        if ($request->generate_answer) {
            $prompt .= 'Also, generate answers for the questions. ';
        }

        // Finalize prompt
        $prompt .= 'Please provide comprehensive content based on these details.';

        // Use the prompt to generate content

    
        // Generate the content using OpenAI API
        $response = $client->chat()->create([
            "model" => $openaiModel,
            'messages' => 
            [
                [
                    'role' => 'system', 
                    'content' => 'You are a helpful assistant.'
                ],

                [
                    'role' => 'user', 
                    'content' => $prompt
                ],
            ],
        ]);
 
        // Get the response content
        $content = $response['choices'][0]['message']['content'];

        // Initialize $firstImageUrl as null by default
        $firstImageUrl = null;

        // Check if the teacher selected to generate images
        $images = [];
        if ($request->has('generate_images') && $request->input('generate_images') == 1)
        {
            // Generate image prompt
            $imagePrompt = 'Generate images based on the following topic: ' . $request->topic;

            // Prepare additional image parameters
            $imageStyle = $request->input('image_style') ?? 'vivid';
            $imageType = $request->input('image_type') ?? 'Illustrations';
            $imagePlacement = $request->input('image_placement') ?? 'Throughout the content';
            $numberOfImages = $request->input('number_of_images') ?? 1;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'prompt' => $imagePrompt,
                'size' => '1024x1024',
                'style' => $imageStyle,
                'quality' => 'standard',
                'n' => (int) $numberOfImages,
            ]);

            Log::info('API Response Body: ' . $response->body());

            $imageData = $response->json()['data'] ?? []; // Safely get the 'data' array
            $images = array_column($imageData, 'url');

            // For one image
            $firstImageUrl = $imageData[0]['url'] ?? null;
        }

        // Save to the database, even if no image is generated
        $educationContent = EducationContent::create([
            'grade_id' => $gradeId,
            'subject_id' => $subjectId,
            'user_id' => $user->id,
            'age' => $request->input('age'),
            'difficulty_level' => $request->input('difficulty_level'),
            'tone' => $request->input('tone'),
            'persona' => $request->input('persona'),
            'topic' => $request->input('topic'),
            'negative_words' => $request->input('negative_words'),
            'points' => $request->input('points'),
            'additional_details' => $request->input('additional_details'),
            'example' => $request->input('examples'),
            'reference' => $request->input('reference'),
            'generated_content' => $content,
            'prompt' => $prompt,
            'image_url' => $firstImageUrl, // This will be null if no image is generated
            'status' => 'generated' // or any default status you want
        ]);

        
            // Stream the response
        return response()->stream(function () use ($content, $images) {
            $chunks = explode("\n", $content);
            foreach ($chunks as $chunk) {
                echo $chunk . "<br/>";
                ob_flush();
                flush();
                sleep(1); // Simulate delay between chunks
            }

        // Display images after the content
        if (!empty($images)) {
            foreach ($images as $imageUrl) {
                echo '<img src="' . $imageUrl . '" alt="Generated Image" style="max-width:100%; height:auto;"><br/>';
            }
        }

            });
    }
   
    
    public function getSubjects($gradeId)   
    {
        $subjects = Subject::where('grade_id', $gradeId)->get();
        return response()->json($subjects);
    }

    public function manageToolsUser()
    {   
        $tools = EducationTools::get();
        return view('backend.education.education_tools_manage_user', compact('tools'));
    }


    public function ToolsGenerateContent(Request $request)
{
    set_time_limit(0);

    $user = auth()->user();
    $openaiModel = $user->selected_model;
    $apiKey = config('app.openai_api_key');
    $client = OpenAI::client($apiKey);

    // Construct the prompt based on input fields
    $prompt = 'Create educational content with the following details: ';
    foreach ($request->all() as $key => $value) {
        if (!empty($value) && !in_array($key, ['_token'])) {
            $prompt .= ucfirst(str_replace('_', ' ', $key)) . ": $value. ";
        }
    }

    // Generate content using OpenAI API
    $response = $client->chat()->create([
        "model" => $openaiModel,
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => $prompt],
        ],
    ]);

    $content = $response['choices'][0]['message']['content'];

    // Stream the response
    return response()->stream(function () use ($content) {
        $chunks = explode("\n", $content);
        foreach ($chunks as $chunk) {
            echo $chunk . "<br/>";
            ob_flush();
            flush();
            sleep(1); // Simulate delay between chunks
        }
    });
}









    // ADMIN SECTION
    public function manageGradeSubject()
    {
        $classes = GradeClass::with('subjects')->get();

        return view('backend.education.add_grade_subject', [
            'classes' => $classes,
        ]);
    }

    public function StoreGradeClass(Request $request)
    {
        // Update 'how_it_works' section if its form is submitted
        if ($request->has('grade_form')) {
            GradeClass::create(
                ['grade' => $request->grade]
            );
    
            return redirect()->back()->with('success', 'Grade/Class added successfully.');
        }
    
        // Update 'banner' section if its form is submitted
        if ($request->has('subject_form')) {
            Subject::create(
                [
                    'grade_id' => $request->grade_id, // ensure grade_id is present in request
                    'name' => $request->input('subject') // ensure subject name is present in request
                ]
            );
    
            return redirect()->back()->with('success', 'Subject added successfully.');
        }
    }

    // CREATE TOOLS
    public function manageTools()
    {   
        $tools = EducationTools::get();
        $categories = $tools->pluck('category')->unique();

        return view('backend.education.education_tools_manage', compact('tools', 'categories'));
    }

    public function showTool($id)
    {
        // Retrieve the tool by ID
        $tool = EducationTools::findOrFail($id);
        $classes = GradeClass::with('subjects')->get();

        // Pass the tool to the view
        return view('backend.education.education_tools_view', compact('tool','classes'));
    }

    

    public function AddTools()
    {
        return view('backend.education.education_tools_add');
    }

    public function StoreTools(Request $request)
{
    // dd($request);
    // Validate the incoming request
    $validatedData = $request->validate([
        'name' => 'required|string',
        'category' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'description' => 'nullable|string',
        'input_types' => 'required|array',
        'input_names' => 'required|array',
        'input_labels' => 'required|array',
        'input_placeholders' => 'required|array',
        'prompt' => 'nullable|string',
        'popular' => 'nullable|string',
    ]);

    // Create slug from the template name
    $slug = Str::slug($validatedData['name']);

    // Create new Tool instance
    $tool = new EducationTools();
    $tool->name = $validatedData['name'];
    $tool->slug = $slug;
    $tool->category = $validatedData['category']; // Add category

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('uploads/tools', 'public'); // Store image
        $tool->image = $imagePath; // Save image path in the database
    }

    $tool->popular = isset($validatedData['popular']) ? $validatedData['popular'] : null;
    $tool->description = $validatedData['description'];

    // Save input fields as JSON arrays
    $tool->input_types = json_encode($validatedData['input_types']);
    $tool->input_names = json_encode($validatedData['input_names']);
    $tool->input_labels = json_encode($validatedData['input_labels']);
    $tool->input_placeholders = json_encode($validatedData['input_placeholders']);
    
    // Save the prompt
    $tool->prompt = $validatedData['prompt'];

    // Save the Tool instance
    $tool->save();

    // Success notification
    $notification = array(
        'message' => 'Tool Added Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
}
    
}
