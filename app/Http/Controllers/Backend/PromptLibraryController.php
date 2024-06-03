<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PromptLibraryExportt;
use App\Http\Controllers\Controller;
use App\Imports\PromptLibraryImport;
use App\Models\PromptExample;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\PromptLibraryCategory;
use App\Models\PromptLibrary;
use App\Models\PromptLibrarySubCategory;
use Maatwebsite\Excel\Facades\Excel;

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
            'sub_category_id' => $request->subcategory_id,
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
        // Get the related examples
        $prompt_library_examples = $prompt_library->examples;

        return view('backend.prompt_library.prompt_library_view', compact('prompt_library', 'prompt_library_examples'));
    }

    // Export
    public function Export()
    {

        return Excel::download(new PromptLibraryExportt, 'prompt_library.xlsx');
    } // End Method 

    public function Import(Request $request)
    {

        Excel::import(new PromptLibraryImport(), $request->file('import_file'));

        $notification = array(
            'message' => 'Promopt Imported Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    } // End Method 

    public function PromptSubCategoryAdd()
    {
        $categories = PromptLibraryCategory::latest()->get();
        $subcategories = PromptLibrarySubCategory::latest()->get();
        return view('backend.prompt_library.sub_category', compact('categories', 'subcategories'));
    }

    public function PromptSubCategoryStore(Request $request)
    {

        $PromptLibrarySubCategory = PromptLibrarySubCategory::insertGetId([

            'category_id' => $request->category_id,
            'sub_category_name' => $request->sub_category_name,
            'sub_category_instruction' => $request->sub_category_instruction,
            'created_at' => Carbon::now(),

        ]);

        return redirect()->back()->with('success', 'Prompt Sub-Category Saved Successfully');
    }

    // GET SUB CATEGORY PROMPT
    public function getPromptSubCategory($category_id)
    {
        $subcategories = PromptLibrarySubCategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }


    // Example Store
    public function PromptExampleStore(Request $request, PromptLibrary $promptLibrary)
    {
        $request->validate([
            'examples' => 'required|array',
            'examples.*' => 'required|string',
        ]);

        foreach ($request->examples as $exampleText) {
            PromptExample::create([
                'prompt_id' => $promptLibrary->id,
                'example' => $exampleText,
                'active' => true, // Default to active, adjust as needed
            ]);
        }

        return redirect()->back()->with('success', 'Examples saved successfully!');
    }
}
