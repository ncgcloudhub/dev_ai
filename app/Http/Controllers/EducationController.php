<?php

namespace App\Http\Controllers;

use App\Models\educationContent;
use App\Models\GradeClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use OpenAI;
use Illuminate\Support\Facades\Log;


class EducationController extends Controller
{
    // USER SECTION
    public function educationForm()
    {
        $classes = GradeClass::with('subjects')->get();

        return view('backend.education.create_content', [
            'classes' => $classes,
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
        $contents = EducationContent::where('user_id', $userId)
            ->where('subject_id', $subjectId)
            ->with('gradeClass', 'subject')
            ->get();

        return response()->json([
            'contents' => $contents,
        ]);
    }

    public function educationContent(Request $request)
    {
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
        $prompt = 'I need to create questions and answers for my students. It is for ' . $gradeName . ' and the subject is ' . $subjectName . ' for students of age ' . $request->age . '. The question difficulty is ' . $request->difficulty_level . ' with ' . $request->tone . ' tone and the persona is ' . $request->persona;
    
        $prompt .= ' The question topic is ' . $request->topic;
    
        if ($request->additional_details) {
            $prompt .= ', these are the additional points that should be included: ' . $request->additional_details;
        }
    
        if ($request->examples) {
            $prompt .= ', I am providing some examples for better understanding: ' . $request->examples;
        }
    
        // Generate the content using OpenAI API
        $response = $client->chat()->create([
            "model" => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
    
        $content = $response['choices'][0]['message']['content'];
    
        // Save to the database
        $educationContent = EducationContent::create([
            'grade_id' => $gradeId,
            'subject_id' => $subjectId,
            'user_id' => $user->id,
            'age' => $request->input('age'),
            'difficulty_level' => $request->input('difficulty_level'),
            'tone' => $request->input('tone'),
            'persona' => $request->input('persona'),
            'topic' => $request->input('topic'),
            'additional_details' => $request->input('additional_details'),
            'example' => $request->input('examples'),
            'reference' => $request->input('reference'),
            'generated_content' => $content,
            'prompt' => $prompt,
            'status' => 'generated' // or any default status you want
        ]);
    
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
    
    

    public function getSubjects($gradeId)   
    {
        $subjects = Subject::where('grade_id', $gradeId)->get();
        return response()->json($subjects);
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
    

}
