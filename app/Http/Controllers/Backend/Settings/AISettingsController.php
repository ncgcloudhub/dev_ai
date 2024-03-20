<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AISettings;
use Illuminate\Support\Carbon;

class AISettingsController extends Controller
{
    public function AIsettingsAdd(){
      
        return view('admin.ai_settings.ai_settings_add');
    }

    public function AIsettingsStore(Request $request){

      $openai_settings = AISettings::insertGetId([
      	
		'openaimodel' => $request->openaimodel,
		
      	'created_at' => Carbon::now(),   

      ]);

       $notification = array(
			'message' => 'Settings Changed Successfully',
			'alert-type' => 'success'
		);

		// return redirect()->route('manage-product')->with($notification);
		return redirect()->back()->with($notification);

	} // end method
}
