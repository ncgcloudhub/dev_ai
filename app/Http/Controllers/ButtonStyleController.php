<?php

namespace App\Http\Controllers;

use App\Models\ButtonStyle;
use Illuminate\Http\Request;

class ButtonStyleController extends Controller
{
    public function index()
    {
        $buttonStyles = ButtonStyle::all();
        return view('admin.button_styles.index', compact('buttonStyles'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'button_type' => 'required|string',
            'class_name' => 'required|string'
        ]);

        ButtonStyle::where('button_type', $request->button_type)->update(['is_selected' => false]);

        ButtonStyle::where([
            'button_type' => $request->button_type,
            'class_name' => $request->class_name
        ])->update(['is_selected' => true]);

        return back()->with('success', 'Button design updated successfully.');
    }

    public function getSelectedButtonStyles()
    {
        return ButtonStyle::where('is_selected', true)->get();
    }
}
