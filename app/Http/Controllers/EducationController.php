<?php

namespace App\Http\Controllers;

use App\Models\educationContent;
use App\Models\EducationTools;
use App\Models\EducationToolsCategory;
use App\Models\EducationToolsFavorite;
use App\Models\GradeClass;
use App\Models\Subject;
use App\Models\ToolGeneratedContent;
use Illuminate\Http\Request;
use OpenAI;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Parsedown;
use PDF;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


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

    public function toolsLibrary()
    {
        $userId = auth()->id(); 

        $educationContents = EducationContent::where('add_to_library', true)
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
    
    return view('backend.education.education_tools_content_user', [
            'classes' => $classes,
        ]);
    }

    public function getUserContents()
    {
        $userId = auth()->id(); 

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


    public function search(Request $request)
    {
        $query = EducationContent::query();

        // Check the route or a specific flag to apply different filters
        if (request()->routeIs('education.tools.contents')) {
            // For /education/tools/library route
            $query->where('add_to_library', true); // Filter by added to library
        } elseif (request()->routeIs('user_generated_education_content')) {
            // For /education/get-content route
            $query->where('user_id', auth()->id()); // Filter by authenticated user
        }

        Log::info('Received request ');
        // Check if there is a search term
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            Log::info('line 124 ');
            // Convert the search term to lowercase
            $searchTerm = strtolower($searchTerm);

            $query->where(function ($q) use ($searchTerm) {
                // Use ILIKE to make the search case-insensitive in PostgreSQL
                $q->whereRaw('topic ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('difficulty_level ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('tone ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('persona ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('additional_details ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('example ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('reference ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('prompt ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('generated_content ILIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('status ILIKE ?', ['%' . $searchTerm . '%']);
            });

            // You can also search through related tables with case-insensitivity
            $query->orWhereHas('gradeClass', function ($q) use ($searchTerm) {
                $q->whereRaw('grade ILIKE ?', ['%' . $searchTerm . '%']);
            });

            $query->orWhereHas('subject', function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
            });

            $query->orWhereHas('user', function ($q) use ($searchTerm) {
                $q->whereRaw('name ILIKE ?', ['%' . $searchTerm . '%']);
            });
        }

        // Paginate the results
        $results = $query->with(['gradeClasss', 'subject', 'user'])->paginate(10);

        return response()->json($results);
    }

    public function getContent($id)
{
    $content = educationContent::findOrFail($id);
    return response()->json(['content' => $content->generated_content]); // Adjust according to your content field name
}

public function updateContent(Request $request, $id)
{
    $content = educationContent::findOrFail($id);
    $content->generated_content = $request->input('content'); // Adjust field name as needed
    $content->save();

    return response()->json(['success' => true]);
}

    public function getContentsBySubjectLibrary(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $userId = auth()->id(); // Get the authenticated user's ID

        // Retrieve contents for the selected subject
        $contents = educationContent::where('add_to_library', true)
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

    public function downloadPDF(Request $request, $id)
{
    $content = educationContent::findOrFail($id);

    if (!$content) {
        return redirect()->back()->with('error', 'Content not found');
    }

    // Convert markdown to plain text or formatted HTML
    $parsedown = new Parsedown();
    $formattedContent = $parsedown->text($content->generated_content);
    $content->generated_content = $formattedContent;

    // Determine which details to include
    $includeGrade = $request->query('include_grade', false);
    $includeSubject = $request->query('include_subject', false);
    $includeDate = $request->query('include_date', false);

    // Pass these flags to the Blade view
    $pdfHtml = view('backend.education.education_pdf', [
        'content' => $content,
        'includeGrade' => $includeGrade,
        'includeSubject' => $includeSubject,
        'includeDate' => $includeDate
    ])->render();

    $pdf = PDF::loadHTML($pdfHtml);

    // Format the filename
    $formattedDate = $content->created_at->format('Y_m_d');
    $subjectName = $content->subject->name ?? 'UnknownSubject';
    $maxTopicLength = 50;
    $truncatedTopic = strlen($content->topic) > $maxTopicLength 
        ? substr($content->topic, 0, $maxTopicLength) . '...' 
        : $content->topic;

    $fileName = "{$truncatedTopic}({$subjectName})_{$formattedDate}.pdf";

    return $pdf->download($fileName);
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

    public function addToLibrary(Request $request, $id)
{
    Log::info('Received request to add content to library', ['id' => $id]);

    $content = educationContent::find($id);

    if (!$content) {
        return response()->json(['error' => 'Content not found'], 404);
    }

    if ($content->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Toggle the add_to_library field
    if ($content->add_to_library) {
        // If it's already in the library, mark it as not in the library
        $content->add_to_library = false;
        $message = 'Content removed from library';
        $status = 'removed';
    } else {
        // If it's not in the library, mark it as in the library
        $content->add_to_library = true;
        $message = 'Content added to library';
        $status = 'added';
    }

    $content->save();

    return response()->json(['success' => true, 'status' => $status, 'message' => $message]);
}
    

    public function educationContent(Request $request)
    {

        set_time_limit(0);

        $data = getUserLastPackageAndModels();
        $lastPackage = $data['lastPackage'];

        $add_to_library = true;
        
        if($lastPackage){
            $add_to_library = false;
        }

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

        // Get the total tokens used
        $totalTokens = $response['usage']['total_tokens'];
        deductUserTokensAndCredits($totalTokens);

        
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

            deductUserTokensAndCredits(0, calculateCredits('1024*1024', 'standard'));

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
            'add_to_library' => $add_to_library,
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
        $user = auth()->user();
        $tools = EducationTools::with('favorites')->get();
    
        // Map the tools to include is_favorited
        $tools = $tools->map(function($tool) use ($user) {
            $tool->is_favorited = $tool->favorites->where('user_id', $user->id)->isNotEmpty();
            return $tool;
        });

        return view('backend.education.education_tools_manage_user', compact('tools'));
    }


    public function ToolsGenerateContent(Request $request)
{
    set_time_limit(0);

    $toolId = $request->input('tool_id');
    $tool = EducationTools::find($toolId);  // Assuming 'Tool' is your model

    if (!$tool) {
        return response()->json(['error' => 'Tool not found'], 404);
    }

    $user = auth()->user();
    $openaiModel = $user->selected_model;
    $apiKey = config('app.openai_api_key');
    $client = OpenAI::client($apiKey);

    $savedPrompt = $tool->prompt;

    // Start building the final prompt by appending the saved prompt from the tool
    $prompt = $savedPrompt . " "; 

    foreach ($request->all() as $key => $value) {
        if (!empty($value) && !in_array($key, ['_token', 'tool_id'])) {
            // Escape special characters to prevent malformed prompt
            $escapedValue = addslashes($value);
            $prompt .= ucfirst(str_replace('_', ' ', $key)) . ": $escapedValue. ";
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

    // Get the total tokens used
    $totalTokens = $response['usage']['total_tokens'];
    deductUserTokensAndCredits($totalTokens);

    $toolContent = new ToolGeneratedContent();  // Assuming you have a model named ToolContent
    $toolContent->tool_id = $toolId;
    $toolContent->user_id = $user->id;
    $toolContent->prompt = $prompt;
    $toolContent->content = $content;
    $toolContent->save();

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


public function toggleFavorite(Request $request)
{
    $user = Auth::user(); // Get the authenticated user
    $toolsId = $request->input('tools_id'); // ID of the tool being favorited

    // Check if the tool is already favorited by the user
    $alreadyFavorited = EducationToolsFavorite::where('user_id', $user->id)
                                 ->where('tools_id', $toolsId)
                                 ->exists();

    if ($alreadyFavorited) {
        EducationToolsFavorite::where('user_id', $user->id)
                ->where('tools_id', $toolsId)
                ->delete();
        return response()->json(['success' => true, 'action' => 'removed']);
    } else {
       
        EducationToolsFavorite::create([
            'user_id' => $user->id,
            'tools_id' => $toolsId,
        ]);
        return response()->json(['success' => true, 'action' => 'added']);
    }
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

    public function updateGrade(Request $request, $id)
{
    $gradeClass = GradeClass::findOrFail($id); // Find the grade by ID
    $gradeClass->grade = $request->input('grade'); // Update the grade field
    $gradeClass->save(); // Save the changes

    return redirect()->back()->with('success', 'Grade updated successfully.');
}


public function updateSubject(Request $request, $id)
{
    $subject = Subject::findOrFail($id); // Find the subject by ID
    $subject->name = $request->input('subject'); // Update the subject name
    $subject->save(); // Save the changes

    return redirect()->back()->with('success', 'Subject updated successfully.');
}



    // CREATE TOOLS
    public function manageTools()
    {   
        $tools = EducationTools::get();
        $categories = EducationToolsCategory::orderBy('id', 'ASC')->get();

        return view('backend.education.education_tools_manage', compact('tools', 'categories'));
    }

    public function showTool($id)
    {
        $userId = auth()->id();
        // Retrieve the tool by ID
        $tool = EducationTools::findOrFail($id);
        $classes = GradeClass::with('subjects')->get();
        $similarTools = EducationTools::where('category', $tool->category)
        ->where('id', '!=', $id) // Exclude the current tool
        ->get();

        $toolContent = ToolGeneratedContent::where('tool_id', $id)
        ->where('user_id', $userId)
        ->get();

        // Get all content generated by the user across all tools
        $allToolContent = ToolGeneratedContent::where('user_id', $userId)->get();

        // Pass the tool to the view
        return view('backend.education.education_tools_view', compact('tool','classes','similarTools','toolContent','allToolContent'));
    }

    // Education Tools Category
    
    public function EducationToolsCategoryAdd()
    {
        $categories = EducationToolsCategory::orderBy('id', 'ASC')->get();
        return view('backend.education.educationtools_category', compact('categories'));
    }

    public function EducationToolsCategoryStore(Request $request)
    {

        flash()->success('Operation completed successfully.');

        $EducationToolsCategory = EducationToolsCategory::insertGetId([

            'category_name' => $request->category_name,
            'created_at' => Carbon::now(),

        ]);

        return redirect()->back()->with('success', 'Category Saved Successfully');
    }

    public function EducationToolsCategoryEdit($id)
    {
        $categories = EducationToolsCategory::orderBy('id', 'ASC')->get();
        $category = EducationToolsCategory::findOrFail($id);
        return view('backend.education.educationtools_category_edit', compact('category', 'categories'));
    }


    public function EducationToolsCategoryUpdate(Request $request)
    {

        $id = $request->id;

        EducationToolsCategory::findOrFail($id)->update([
            'category_name' => $request->category_name,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

        // end else 

    } // end method 


    public function EducationToolsCategoryDelete($id)
    {
        $category = EducationToolsCategory::findOrFail($id);

        EducationToolsCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('education.tools.category.add')->with($notification);
    } // end method

    //Education Tools Category End

    public function AddTools()
    {
        $categories = EducationToolsCategory::latest()->get();
        return view('backend.education.education_tools_add', compact('categories'));
    }

    public function StoreTools(Request $request)
    {
        // dd($request);
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|int',
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
        $tool->category_id = $validatedData['category_id']; // Add category

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


    public function editTools($id)
    {
        $tool = EducationTools::findOrFail($id); // Fetch the tool
        $categories = EducationToolsCategory::orderBy('id', 'ASC')->get();
        return view('backend.education.education_tools_edit', compact('tool', 'categories')); // Return edit view
    }


    public function updateTools(Request $request, $id)
    {
        $tool = EducationTools::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|int',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'input_types' => 'required|array',
            'input_names' => 'required|array',
            'input_labels' => 'required|array',
            'input_placeholders' => 'required|array',
            'prompt' => 'nullable|string',
            'popular' => 'nullable|string',
        ]);

        // Update the tool's properties
        $tool->name = $validatedData['name'];
        $tool->slug = Str::slug($validatedData['name']);
        $tool->category_id = $validatedData['category_id'];

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Optionally delete the old image here
            $imagePath = $request->file('image')->store('uploads/tools', 'public');
            $tool->image = $imagePath;
        }

        $tool->description = $validatedData['description'];
        $tool->input_types = json_encode($validatedData['input_types']);
        $tool->input_names = json_encode($validatedData['input_names']);
        $tool->input_labels = json_encode($validatedData['input_labels']);
        $tool->input_placeholders = json_encode($validatedData['input_placeholders']);
        $tool->prompt = $validatedData['prompt'];
        $tool->popular = isset($validatedData['popular']) ? $validatedData['popular'] : null;

        // Save the Tool instance
        $tool->save();

        // Success notification
        $notification = [
            'message' => 'Tool Updated Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('manage.education.tools')->with($notification);
    }

    public function getToolContent($id)
    {
        $content = ToolGeneratedContent::findOrFail($id);
        return response()->json(['content' => $content->content]); // Ensure 'content' matches
    }


    public function updateToolContent(Request $request, $id)
    {
        $content = ToolGeneratedContent::findOrFail($id);
        $content->content = $request->input('content');
        $content->save();

        return response()->json(['success' => true]);
    }

    public function destroyTools($id)
    {
        $tool = EducationTools::findOrFail($id);
        $tool->delete();

        // Success notification
        $notification = [
            'message' => 'Tool Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->route('manage.education.tools')->with($notification);
    }

    public function deleteGrade($id)
    {
        $gradeClass = GradeClass::findOrFail($id); // Find the grade by ID
        $gradeClass->delete(); // Delete the grade

        return redirect()->back()->with('success', 'Grade deleted successfully.');
    }


    public function deleteSubject($id)
    {
        $subject = Subject::findOrFail($id); // Find the subject by ID
        $subject->delete(); // Delete the subject

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }

}
