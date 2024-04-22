<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PromptLibraryCategory;

class PromptLibraryController extends Controller
{
    // Custom Template Category
    public function PromptCategoryAdd()
    {
        $categories = PromptLibraryCategory::latest()->get();
        return view('backend.prompt_library.category', compact('categories'));
    }

    public function PromptCategoryStore(Request $request)
    {

        $PromptLibraryCategory = PromptLibraryCategory::insertGetId([

            'category_name' => $request->category_name,
            'category_icon' => $request->category_icon,
            'created_at' => Carbon::now(),

        ]);

        return redirect()->back()->with('success', 'Prompt Category Saved Successfully');
    }
}
