<?php

namespace App\Http\Controllers;

use App\Models\ButtonDesign;
use App\Models\ButtonStyle;
use Illuminate\Http\Request;

class ButtonStyleController extends Controller
{
    public function index()
    {
        $buttonStyles = ButtonDesign::all();
        return view('backend.dynamic_buttons.dynamic_button_manage_news', compact('buttonStyles'));
    }

 // Save button style
 public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'button_type' => 'required|string',
            'icon' => 'required|string',
            'classes' => 'required|json',
        ]);

        // Save or update the button style
        $buttonStyle = ButtonDesign::updateOrCreate(
            ['button_type' => $request->button_type],
            [
                'icon' => $request->icon,
                'classes' => $request->classes,
            ]
        );

        // Return a JSON response
        return response()->json([
            'message' => 'Button style saved successfully!',
            'data' => $buttonStyle,
        ]);
    }

}
