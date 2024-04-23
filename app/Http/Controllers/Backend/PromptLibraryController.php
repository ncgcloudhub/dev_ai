<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\PromptLibraryCategory;
use App\Models\PromptLibrary;

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

    public function PromptAdd()
    {
        $categories = PromptLibraryCategory::latest()->get();
        return view('backend.prompt_library.prompt_library_add', compact('categories'));
    }

    public function PromptStore(Request $request)
    {

        $slug = Str::slug($request->prompt_name);

        $prompt_library = PromptLibrary::insertGetId([

            'prompt_name' => $request->prompt_name,
            'icon' => $request->icon,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'actual_prompt' => $request->actual_prompt,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Prompt Library Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function PromptManage()
    {
        $prompt_library = PromptLibrary::orderby('id', 'asc')->get();
        $prompt_library_category = PromptLibraryCategory::latest()->get();
        return view('backend.prompt_library.prompt_library_manage', compact('prompt_library', 'prompt_library_category'));
    }

    public function PromptView($slug)
    {
        // Find the template by slug
        $prompt_library = PromptLibrary::where('slug', $slug)->firstOrFail();

        return view('backend.prompt_library.prompt_library_view', compact('prompt_library'));
    }
}
