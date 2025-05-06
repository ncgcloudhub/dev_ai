<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AISettings;
use Illuminate\Support\Carbon;

class AISettingsController extends Controller
{
    public function AIsettingsAdd(){
      
		$models = AISettings::latest()->get();
        return view('admin.ai_settings.ai_settings_add', compact('models'));
    }

	public function AIsettingsStore(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'displayname' => 'required|string',
            'openaimodel' => 'required|string',
        ]);

        // Store the item in the database
        $item = new AISettings();
      
        $item->displayname = $validatedData['displayname'];
        $item->openaimodel = $validatedData['openaimodel'];

        $item->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'AI Model added successfully!');
    }

	public function AIsettingsEdit($id)
    {
        $models = AISettings::latest()->get();
        $model = AISettings::findOrFail($id);

        return view('admin.ai_settings.ai_settings_edit', compact('model', 'models'));
    }


    public function AIsettingsUpdate(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            
            'displayname' => 'required|string',
            'openaimodel' => 'required|string',
           
        ]);

        // Find the about us record to update
        $item = AISettings::findOrFail($request->id);

        // Update the text fields
        $item->displayname = $validatedData['displayname'];
        $item->openaimodel = $validatedData['openaimodel'];
      
        // Save the changes to the about us record
        $item->save();

        // Redirect the user with a success message
        $notification = array(
            'message' => 'Open AI Model updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function toggleStatus(Request $request)
    {
        $item = AISettings::findOrFail($request->id);
        $item->status = !$item->status; // Toggle status
        $item->save();

        return response()->json(['success' => true, 'status' => $item->status]);
    }

    public function AIsettingsDelete($id)
    {

        // Find the gallery item
        $item = AISettings::findOrFail($id);

        // Delete the gallery item from the database
        $item->delete();

        // Redirect back with notification
        $notification = array(
            'message' => 'Open AI Model Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);
    } // end method
}
