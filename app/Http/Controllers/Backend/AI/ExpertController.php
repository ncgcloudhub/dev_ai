<?php

namespace App\Http\Controllers\Backend\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expert;
use Illuminate\Support\Carbon;

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


        $expert_id = Expert::insertGetId([
            
            'expert_name' => $request->expert_name,
            'character_name' => $request->character_name,
            'slug' => $request->expert_name,
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

    public function ExpertChat($id)
    {
        $expert_selected = Expert::findOrFail($id);
        $expert_selected_id = $id;
        $experts = Expert::latest()->get();
        return view('backend.expert.chat1', compact('experts','expert_selected_id','expert_selected'));
    }
}
