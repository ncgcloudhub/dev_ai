<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expert;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ExpertController extends Controller
{
    public function ExpertAdd(){
        $experts = Expert::latest()->get();
        return view('backend.expert.expert_add', compact('experts'));
    }

    public function ExpertStore(Request $request)
    {

        $imageName = '';
        if ($image = $request->file('image')){
            $imageName = time().'-'.uniqid().'.'.$image->getClientOriginalExtension();
            $image->move('backend/uploads/expert', $imageName);
        }

        // Expert::create([
        //     'image'=>$imageName,
        // ]);

        $slug = Str::slug($request->expert_name);

        $expert_id = Expert::insertGetId([
            
            'expert_name' => $request->expert_name,
            'character_name' => $request->character_name,
            'slug' => $slug,
            'description' => $request->description,
            'role' => $request->role,
            'expertise' => $request->expertise,
            'active' => '1',
            'image'=>$imageName,
            'created_at' => Carbon::now(),   
    
          ]);

        $notification = array(
            'message' => 'Expert Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }


    // CHAT
    public function index()
    {
        $experts = Expert::latest()->get();
        return view('backend.expert.expert_manage', compact('experts'));
    }

    public function ExpertChat($slug)
    {
        $expert_selected = Expert::where('slug', $slug)->firstOrFail();
        $expert_selected_id = $expert_selected->id;
        $experts = Expert::latest()->get();
        return view('backend.expert.chat1', compact('experts','expert_selected_id','expert_selected'));
    }
}
