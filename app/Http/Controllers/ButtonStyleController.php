<?php

namespace App\Http\Controllers;

use App\Models\ButtonStyle;
use Illuminate\Http\Request;

class ButtonStyleController extends Controller
{
    public function index()
    {
        $buttonStyles = ButtonStyle::all();
        return view('backend.dynamic_buttons.dynamic_button_manage', compact('buttonStyles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'button_type' => 'required|string',
            'class_name' => 'required|string'
        ]);

        ButtonStyle::create([
            'button_type' => $request->button_type,
            'class_name' => $request->class_name,
            'is_selected' => false, // Newly added buttons won't be selected by default
        ]);

        return back()->with('success', 'New button added successfully.');
    }

    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:button_styles,id',
            'class_name' => 'required|string'
        ]);

        $button = ButtonStyle::findOrFail($request->id);
        $button->update([
            'class_name' => $request->class_name
        ]);

        return back()->with('success', 'Button style updated successfully.');
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

    public function destroy($id)
    {
        $button = ButtonStyle::findOrFail($id);
        $button->delete();

        return back()->with('success', 'Button style deleted successfully.');
    }

}
