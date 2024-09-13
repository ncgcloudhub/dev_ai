<?php

namespace App\Http\Controllers;

use App\Models\GradeClass;
use App\Models\Subject;
use Illuminate\Http\Request;

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
        logger($request->all());
        
        return response()->json([
            'message' => 'Your order is being processed!',
            'details' => 'You will receive an email with your order details shortly.'
        ]);
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
