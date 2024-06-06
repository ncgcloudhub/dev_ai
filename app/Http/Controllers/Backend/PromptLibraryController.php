<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PromptLibraryExportt;
use App\Http\Controllers\Controller;
use App\Imports\PromptLibraryImport;
use App\Models\AISettings;
use App\Models\PromptExample;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\PromptLibraryCategory;
use App\Models\PromptLibrary;
use App\Models\PromptLibrarySubCategory;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;

class PromptLibraryController extends Controller
{
    // Prompt Category
    public function PromptCategoryAdd()
    {
        $categories = PromptLibraryCategory::orderBy('id', 'ASC')->get();
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


    public function PromptCategoryEdit($id)
    {
        $categories = PromptLibraryCategory::orderBy('id', 'ASC')->get();
        $category = PromptLibraryCategory::findOrFail($id);
        return view('backend.prompt_library.category_edit', compact('category', 'categories'));
    }


    public function PromptCategoryUpdate(Request $request)
    {

        $id = $request->id;

        PromptLibraryCategory::findOrFail($id)->update([
            'category_name' => $request->category_name,
            'category_icon' => $request->category_icon,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

        // end else 

    } // end method 


    public function PromptCategoryDelete($id)
    {
        $category = PromptLibraryCategory::findOrFail($id);

        PromptLibraryCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Delectd Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('prompt.category.add')->with($notification);
    } // end method



    // Prompt Sub Category
    public function PromptSubCategoryAdd()
    {
        $categories = PromptLibraryCategory::orderBy('id', 'ASC')->get();
        $subcategories = PromptLibrarySubCategory::orderBy('id', 'ASC')->get();
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

    public function PromptSubCategoryEdit($id)
    {
        $categories = PromptLibraryCategory::orderBy('id', 'ASC')->get();
        $subcategories = PromptLibrarySubCategory::orderBy('id', 'ASC')->get();
        $subcategory = PromptLibrarySubCategory::findOrFail($id);
        return view('backend.prompt_library.sub_category_edit', compact('categories', 'subcategories', 'subcategory'));
    }


    public function PromptSubCategoryUpdate(Request $request)
    {

        $id = $request->id;

        PromptLibrarySubCategory::findOrFail($id)->update([
            'category_id' => $request->category_id,
            'sub_category_name' => $request->sub_category_name,
            'sub_category_instruction' => $request->sub_category_instruction,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Customer Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

        // end else 

    } // end method 


    public function PromptSubCategoryDelete($id)
    {
        $category = PromptLibrarySubCategory::findOrFail($id);

        PromptLibrarySubCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Delectd Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('prompt.subcategory.add')->with($notification);
    } // end method


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

    // ASK AI PROMPT
    public function AskAiPromptLibrary(Request $request)
    {
        $setting = AISettings::find(1);
        $openaiModel = $setting->openaimodel;

        $prompt = $request->input('message');

        // Make API call
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $openaiModel, // Use the appropriate model name
                'messages' => [
                    ['role' => 'system', 'content' => $prompt],
                ],
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        $messageContent = $data['choices'][0]['message']['content'];

        // Return the response
        return response()->json([
            'message' => $messageContent,
        ]);
    }
}
