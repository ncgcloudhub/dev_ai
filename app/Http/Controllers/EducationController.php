<?php

namespace App\Http\Controllers;

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
        $prompt = 'I need create questions and answers for my students, it is of ' . $gradeName . ' and the subject is ' . $subjectName . ' for students of age ' . $request->age . '. The question difficulty is ' . $request->difficulty_level . ' with ' . $request->tone . ' tone and the persona is ' . $request->persona;
    
        $prompt .= ' The question topic is ' . $request->topic;
    
        if ($request->additional_details) {
            $prompt .= ', these are the additional points that should be included ' . $request->additional_details;
        }
    
        if ($request->examples) {
            $prompt .= ', I am providing some examples for better understanding ' . $request->examples;
        }
    
        // Stream the response
        return response()->stream(function () use ($client, $prompt) {
            // Initialize chat with the OpenAI API
            $response = $client->chat()->create([
                "model" => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);
    
            // Get the response content
            $content = $response['choices'][0]['message']['content'];
    
            // Stream each part of the response progressively
            $chunks = explode("\n", $content);
            foreach ($chunks as $chunk) {
                echo $chunk . "<br/>";
                ob_flush();
                flush();
                sleep(1);  // Simulate delay between chunks
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
