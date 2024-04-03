<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DalleImageGenerate;

class HomeController extends Controller
{
    //Image Gallery Front End Page
    public function AIImageGallery(){
        $images = DalleImageGenerate::latest()->get();
       
        return view('frontend.ai_image_gallery', compact('images'));
    }
}
