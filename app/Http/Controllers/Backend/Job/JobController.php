<?php

namespace App\Http\Controllers\Backend\Job;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class JobController extends Controller
{
    public function addJob()
    {
        $user_id = Auth::user()->id;
        return view('backend.job.add_job');
    }

    public function storeJob(Request $request)
    {

        // dd($request);

        // Validate the form data
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_position' => 'nullable|string|max:255',
            'job_category' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'no_of_vacancy' => 'nullable|integer',
            'experience' => 'nullable|string|max:255',
            'last_date_of_apply' => 'nullable|date',
            'close_date' => 'nullable|date',
            'start_salary' => 'nullable|integer',
            'last_salary' => 'nullable|integer',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'tags' => 'nullable|max:255',
        ]);

         // Generate slug
            $slug = Str::slug($request->input('job_title'));

            // Append a unique identifier to ensure uniqueness
            $uniqueSlug = Job::where('slug', $slug)->exists() ? $slug . '-' . uniqid() : $slug;

            // Add the slug to validated data
            $validatedData['slug'] = $uniqueSlug;
            $validatedData['user_id'] = auth()->id();

            // dd($validatedData);
        // Create a new job instance
        $job = new Job();
        $job->fill($validatedData);
        $job->save();

        // Optionally, you can return a response or redirect
        return redirect()->back()->with('success', 'Job added successfully.');
    }

        public function manage()
    {
        // Retrieve all jobs
        $jobs = Job::all();

        // Pass the jobs to the view
        return view('backend.job.manage_job', ['jobs' => $jobs]);
    }
}
