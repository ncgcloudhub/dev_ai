<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DalleImageGenerate;
use Illuminate\Support\Facades\Mail;


class HomeController extends Controller
{
    //Image Gallery Front End Page
    public function AIImageGallery()
    {
        $images = DalleImageGenerate::latest()->get();

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
}
