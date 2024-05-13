<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DalleImageGenerate;
use App\Models\Job;
use App\Models\NewsLetter;
use Illuminate\Support\Carbon;
use App\Models\PrivacyPolicy;
use App\Models\TermsConditions;
use Illuminate\Support\Facades\Mail;


class HomeController extends Controller
{
    //Image Gallery Front End Page
    public function AIImageGallery()
    {
        $images = DalleImageGenerate::latest()->paginate(20);

        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        if (request()->ajax()) {
            return view('frontend.image_gallery_partial', compact('images'))->render();
        }

        return view('frontend.ai_image_gallery', compact('images'));
    }




    //Image Gallery Front End Page
    public function ContactUs()
    {
        return view('frontend.contact');
    }

    //All Jobs Front End Page
    public function AllJobs()
    {
        return view('frontend.job.all_jobs');
    }

    public function detailsJob($slug)
    {
        $job = Job::where('slug', $slug)->firstOrFail();
        return view('backend.job.job_detail', compact('job'));
    }


    //Privacy Policy Front End Page
    public function PrivacyPolicy()
    {
        return view('frontend.privacy_policy');
    }


    //Terms & Conditions Front End Page
    public function TermsConditions()
    {
        return view('frontend.terms_conditions');
    }


    // Mail
    public function sendEmail(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('comments')
        ];

        Mail::send('admin.layouts.email_test', $data, function ($message) use ($data) {
            $message->to('clevercreatorai@gmail.com')
                ->subject($data['subject']);
        });

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function NewsLetterStore(Request $request)
    {
        // Attempt to retrieve user's IP address from request headers
        $ipAddress = $request->ip();

        // If the IP address is not found in the request headers, use fallback
        if ($ipAddress == '127.0.0.1') {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        }

        // Insert user's subscription details into the database
        $NewsLetter = NewsLetter::insertGetId([
            'email' => $request->email,
            'name' => $request->name,
            'ipaddress' => $ipAddress, // Store IP address
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Subscribed Successfully');
    } // end method


    // PRIVACY POLICY BACKEND
    public function ManagePrivacyPolicy()
    {
        $privacy_policy = PrivacyPolicy::orderBy('id', 'asc')->get();
        return view('backend.privacy_policy.manage_privacy_policy', compact('privacy_policy'));
    }

    public function StorePrivacyPolicy(Request $request)
    {

        $privacy_policy = PrivacyPolicy::create([
            'details' => $request->details
        ]);

        return redirect()->back();
    }

    public function EditPrivacyPolicy($id)
    {
        $privacy_policy = PrivacyPolicy::orderBy('id', 'asc')->get();
        $privacy_policys = PrivacyPolicy::findOrFail($id);

        return view('backend.privacy_policy.edit_privacy_policy', compact('privacy_policy', 'privacy_policys'));
    }


    public function UpdatePrivacyPolicy(Request $request)
    {

        PrivacyPolicy::findOrFail($request->id)->update([
            'details' => $request->details,
        ]);

        $notification = array(
            'message' => 'Details Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect(route('manage.privacy.policy'))->with($notification);
    }

    public function DeletePrivacyPolicy($id)
    {

        PrivacyPolicy::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Policy Delectd Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);
    } // end method



    // TERMS & CONDITIONS BACKEND
    public function ManageTermsCondition()
    {
        $terms_condition = TermsConditions::orderBy('id', 'asc')->get();
        return view('backend.terms_conditions.manage_terms_conditions', compact('terms_condition'));
    }

    public function StoreTermsCondition(Request $request)
    {

        $privacy_policy = TermsConditions::create([
            'details' => $request->details
        ]);

        return redirect()->back();
    }

    public function EditTermsCondition($id)
    {
        $terms_condition = TermsConditions::orderBy('id', 'asc')->get();
        $terms_conditions = TermsConditions::findOrFail($id);

        return view('backend.terms_conditions.edit_terms_conditions', compact('terms_condition', 'terms_conditions'));
    }


    public function UpdateTermsCondition(Request $request)
    {

        TermsConditions::findOrFail($request->id)->update([
            'details' => $request->details,
        ]);

        $notification = array(
            'message' => 'Conditions Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect(route('manage.terms.condition'))->with($notification);
    }

    public function DeleteTermsCondition($id)
    {

        TermsConditions::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Conditions Delectd Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);
    } // end method
}
