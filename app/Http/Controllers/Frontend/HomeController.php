<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DalleImageGenerate;
use App\Models\NewsLetter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;


class HomeController extends Controller
{
    //Image Gallery Front End Page
    public function AIImageGallery()
    {
        $images = DalleImageGenerate::latest()->get();

        // Generate Azure Blob Storage URL for each image with SAS token
        foreach ($images as $image) {
            $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
        }

        return view('frontend.ai_image_gallery', compact('images'));
    }

    //Image Gallery Front End Page
    public function ContactUs()
    {
        return view('frontend.contact');
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
}
