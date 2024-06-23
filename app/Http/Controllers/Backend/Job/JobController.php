<?php

namespace App\Http\Controllers\Backend\Job;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;


class JobController extends Controller
{
    public function addJob()
    {
        $user_id = Auth::user()->id;
        return view('backend.job.add_job');
    }

    public function storeJob(Request $request)
    {

        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_position' => 'nullable|string|max:255',
            'job_category' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'skills' => 'nullable|string',
            'responsibility' => 'nullable|string',
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

    public function detailsJob($slug)
    {
        $job = Job::where('slug', $slug)->firstOrFail();
        return view('backend.job.job_detail', compact('job'));
    }

    // Frontend Job Apply
    public function JobApplicationStore(Request $request)
    {

        $userId = Auth::id();

        // Validate the form data including the file upload
        $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cv' => 'required|file|max:10240', // Example: maximum file size of 10MB
            // Add validation rules for other fields
        ]);

        // Handle file upload to Azure Blob Storage
        $cvFile = $request->file('cv');
        $cvFileName = time() . '-' . uniqid() . '.' . $cvFile->getClientOriginalExtension();
        $cvFilePath = $cvFile->getPathname();

        // Create Blob client
        $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
        $containerName = config('filesystems.disks.azure.container_resume');

        // Upload file to Azure Blob Storage
        $blobClient->createBlockBlob($containerName, $cvFileName, fopen($cvFilePath, 'r'), new CreateBlockBlobOptions());


        // Create a new FormData instance
        $formData = new JobApplication();
        $formData->full_name = $request->input('fullName');
        $formData->email = $request->input('email');
        $formData->phone = $request->input('phone');
        $formData->address = $request->input('address');
        $formData->user_id = $userId;
        $formData->cv_path = $cvFileName; // Save the file name or URL to the database
        // Set other form fields here

        // Save the FormData instance to the database
        $formData->save();

        // Optionally, you can redirect the user to a success page
        return redirect()->back();
    }

    // Job Application
    public function manageJobApplication()
    {
        // Retrieve all jobs
        $jobApplications = JobApplication::all();

        // Pass the jobs to the view
        return view('backend.job.manage_job_applications', ['jobApplications' => $jobApplications]);
    }

    // Download CV
    public function downloadCV($id)
    {
        // Retrieve the FormData instance by ID
        $formData = JobApplication::findOrFail($id);

        // Get the CV path from the FormData instance
        $cvPath = $formData->cv_path;

        // Create Blob client
        $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
        $containerName = config('filesystems.disks.azure.container_resume');

        try {
            // Download the blob
            $blob = $blobClient->getBlob($containerName, $cvPath);
            $content = stream_get_contents($blob->getContentStream());

            // Prepare the response for download
            return response($content, 200)
                ->header('Content-Type', $blob->getProperties()->getContentType())
                ->header('Content-Disposition', 'attachment; filename="' . $cvPath . '"');
        } catch (Exception $e) {
            // Handle the error (e.g., file not found in Azure Blob Storage)
            return redirect()->back()->with('error', 'CV file not found.');
        }
    }
}
