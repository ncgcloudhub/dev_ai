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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


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
         // Validate input data
    $validator = Validator::make($request->all(), [
        'category_name' => 'required|string|max:255',
        'category_icon' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

        // Check for duplicate category name
    $existingCategory = PromptLibraryCategory::where('category_name', $request->category_name)->first();

    if ($existingCategory) {
        return redirect()->back()->with('danger', 'Category name already exists.');
    }

    // Insert new category
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

        // Check for duplicate sub-category name under the same category
        $existingSubCategory = PromptLibrarySubCategory::where('category_id', $request->category_id)
        ->where('sub_category_name', $request->sub_category_name)
        ->first();

        if ($existingSubCategory) {
            return redirect()->back()->with('danger', 'Sub-Category name already exists under this category.');
        }

        // Insert new sub-category
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


    public function PromptEdit($id)
    {
        $categories = PromptLibraryCategory::orderBy('id', 'ASC')->get();
        $category = PromptLibrary::findOrFail($id);

        return view('backend.prompt_library.prompt_library_edit', compact('categories', 'category'));
    }


    public function PromptUpdate(Request $request)
    {

        $id = $request->id;
        $slug = Str::slug($request->prompt_name);

        PromptLibrary::findOrFail($id)->update([
            'prompt_name' => $request->prompt_name,
            'icon' => $request->icon,
            'slug' => $slug,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->subcategory_id,
            'description' => $request->description,
            'actual_prompt' => $request->actual_prompt,
            'inFrontEnd' => $request->inFrontEnd,
            'updated_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Prompt Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

        // end else 

    } // end method 

    public function PromptSEOUpdate(Request $request)
    {
        $id = $request->id;

        $validatedData = $request->validate([
            'page_title' => 'nullable|string',          
            'page_description' => 'nullable|string', 
            'page_tagging' => 'nullable|string', 
        ]);

        $template = PromptLibrary::findOrFail($id);
      
        $template->page_title = $validatedData['page_title'] ?? null;
        $template->page_description = $validatedData['page_description'] ?? null;
        $template->page_tagging = $validatedData['page_tagging'] ?? null;        
        $template->save();

        return redirect()->back()->with('success', 'Prompt SEO updated successfully');
    } // end method 


    public function PromptDelete($id)
    {
        $category = PromptLibrary::findOrFail($id);

        PromptLibrary::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Prompt Delectd Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('prompt.manage')->with($notification);
    } // end method


    public function PromptManage()
    {
        $prompt_library = PromptLibrary::orderby('id', 'asc')->get();
        $prompt_library_category = PromptLibraryCategory::orderby('id', 'asc')->get();
        $categories = PromptLibraryCategory::latest()->get();
        $count = $prompt_library->count(); 
        return view('backend.prompt_library.prompt_library_manage', compact('prompt_library', 'prompt_library_category', 'categories','count'));
    }

    public function PromptView($slug)
    {
        // Find the template by slug
        $prompt_library = PromptLibrary::where('slug', $slug)->firstOrFail();
        // Get the related examples
        $prompt_library_examples = $prompt_library->examples;

        return view('backend.prompt_library.prompt_library_view', compact('prompt_library', 'prompt_library_examples'));
    }

    // Frontend
    public function PromptFrontendView($slug)
    {
        // Find the template by slug
        $prompt_library = PromptLibrary::where('slug', $slug)->firstOrFail();
        // Get the related examples
        $prompt_library_examples = $prompt_library->examples;

        return view('frontend.prompt_library_details_frontend', compact('prompt_library', 'prompt_library_examples'));
    }

    // Catgeory Filter Prompt Library
    public function PromptCatgeoryView($id)
    {
        $prompt_library = PromptLibrary::where('category_id', $id)->get();
        $prompt_library_category = PromptLibraryCategory::orderby('id', 'asc')->get();
        $categories = PromptLibraryCategory::latest()->get();
        return view('backend.prompt_library.prompt_library_manage', compact('prompt_library', 'prompt_library_category', 'categories'));
    }

    // Sub Catgeory Filter Prompt Library
    public function PromptSubCatgeoryView($id)
    {
        $prompt_library = PromptLibrary::where('sub_category_id', $id)->get();
        $prompt_library_category = PromptLibraryCategory::orderby('id', 'asc')->get();
        $categories = PromptLibraryCategory::latest()->get();
        return view('backend.prompt_library.prompt_library_manage', compact('prompt_library', 'prompt_library_category', 'categories'));
    }

    // Export
    public function Export()
    {

        return Excel::download(new PromptLibraryExportt, 'prompt_library.xlsx');
    } // End Method 

    public function Import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv,txt',
        ]);
    
        try {
            Excel::import(new PromptLibraryImport(), $request->file('import_file'));
    
            return redirect()->back()->with('success', 'Prompt Imported Successfully');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
    
            foreach ($failures as $failure) {
                Log::error('Validation failure', [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ]);
            }
    
            return redirect()->back()->with('error', 'There was an error importing the file. Check the log for details.');
        } catch (\Exception $e) {
            Log::error('General error during import', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'There was an error importing the file: ' . $e->getMessage());
        }
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

    // Update Prompt Example
    public function updatePromptExample(Request $request, PromptExample $promptExample)
    {
        $request->validate([
            'example' => 'required|string',
        ]);

        $promptExample->update([
            'example' => $request->example,
        ]);

        return redirect()->back()->with('success', 'Example updated successfully!');
    }



    // ASK AI PROMPT
    public function AskAiPromptLibrary(Request $request)
    {
       
        $openaiModel = Auth::user()->selected_model ?? 'gpt-4o';

        if ($request->input('sub_category_instruction')) {
            $sub_category_instruction = $request->input('sub_category_instruction');
        } else {
            $sub_category_instruction = "";
        }
        
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
                'messages' => 
                [
                    [
                        'role' => 'system', 
                        'content' => $sub_category_instruction
                    ],

                    [
                        'role' => 'user', 
                        'content' => $prompt
                    ],
                ],
            ],
        ]);

       
      
        $data = json_decode($response->getBody(), true);
        $messageContent = $data['choices'][0]['message']['content'];
        $completionTokens = $data['usage']['completion_tokens'] ?? 0;
        $totalTokens = $data['usage']['total_tokens'] ?? 0;

        // Deduct user tokens only if the user is logged in
        if (Auth::check()) {
            deductUserTokensAndCredits($totalTokens);
        }

        // Return the response
        return response()->json([
            'message' => $messageContent,
            'completionTokens' => $completionTokens,
            'totalTokens' => $totalTokens,
        ]);
    }

    // Filter
    public function filterPrompts(Request $request)
    {
        $query = PromptLibrary::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('subcategory_id')) {
            $query->where('sub_category_id', $request->subcategory_id);
        }

        $prompt_library = $query->with(['category', 'subcategory'])->get();
        $count = $query->count();

        return response()->json([
                'data' => $prompt_library,
                'count' => $count
            ]);
    }

    // DELETE
    public function delete(PromptExample $example)
    {

        $example->delete();

        return redirect()->back()->with('success', 'Example deleted successfully.');
    }

     // SEO AUTO FILL with AI
     public function fetchPrompt(Request $request, $id)
     { 
         // Fetch the template details by ID
         $prompt = PromptLibrary::find($id);
         $prompt_name = $prompt->prompt_name;
         $description = $prompt->description;
        
         $user = auth()->user();
         $openaiModel = $user->selected_model;
 
         $client = new Client();
               $response = $client->post('https://api.openai.com/v1/chat/completions', [
                   'headers' => [
                       'Authorization' => 'Bearer ' . config('app.openai_api_key'),
                       'Content-Type' => 'application/json',
                   ],
                   'json' => [
                       'model' => $openaiModel,
                       'messages' => [  // Corrected here
                          [
                             "role" => "system", 
                             "content" => "You are an SEO assistant."
                         ],
                         [
                             "role" => "user", 
                             "content" => "Generate an SEO title not more than 60 characters, description not more than 160 characters and relevant 20 tags which are comma separated for $prompt_name and to describe it is $description. The header for the Title should be '**SEO Title:**', the header for Description should be '**SEO Description:**', and the header for the Tags should be '**Relevant Tags:**'"
                         ]
                     ],
                   ],
               ]);
 
             // Decode the response to get a more readable format
             $responseBody = json_decode($response->getBody()->getContents(), true);

             $totalTokens = $responseBody['usage']['total_tokens'];
             deductUserTokensAndCredits($totalTokens);
 
             // Log the entire raw API response for debugging
             Log::info('OpenAI API Full Response: ', ['response' => $responseBody]);
 
             // Correctly extract the assistant's response content
             $assistantContent = $responseBody['choices'][0]['message']['content'] ?? 'No content generated';
 
             // Log the raw content generated by the assistant
             Log::info('Assistant Content: ', ['content' => $assistantContent]);
 
             // Update the regex to correctly match the title and description
             preg_match('/\*\*SEO Title:\*\*\s*(.*)/', $assistantContent, $titleMatches);
             preg_match('/\*\*SEO Description:\*\*\s*(.*)/', $assistantContent, $descriptionMatches);
 
             // For tags, match the tag section after "Relevant Tags"
             preg_match('/\*\*Relevant Tags:\*\*\s*(.*)$/s', $assistantContent, $tagsMatches);
 
             // Get the title, description, and tags if matches are found
             $seoTitle = $titleMatches[1] ?? 'No title generated';
             $seoDescription = $descriptionMatches[1] ?? 'No description generated';
 
             // For tags, keep them as a comma-separated string without trimming spaces
             $seoTags = $tagsMatches[1] ?? 'No tags generated';
 
             // Log the parsed title, description, and tags for debugging
             Log::info('Parsed SEO Title: ', ['title' => $seoTitle]);
             Log::info('Parsed SEO Description: ', ['description' => $seoDescription]);
             Log::info('Parsed SEO Tags: ', ['tags' => $seoTags]);
 
 
 
                 // Return the response with title, description, and tags
                 return response()->json([
                     'success' => true,
                     'seo_title' => $seoTitle,
                     'seo_description' => $seoDescription,
                     'seo_tags' => $seoTags,
                 ]);
     }


    //  AI GENERATE PROMPT (Images)
    public function GenerateDetails(Request $request)
{
    $prompt = $request->input('prompt');

    $user = auth()->user();
    $openaiModel = $user->selected_model;

    $client = new Client();
    $response = $client->post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . config('app.openai_api_key'),
            'Content-Type' => 'application/json',
        ],
        'json' => [
            'model' => $openaiModel,
            'messages' => [
                [
                    "role" => "system",
                    "content" => "You are a helpfull assistant."
                ],
                [
                    "role" => "user",
                   "content" => "Generate a brief description and a suggested prompt name for this prompt: \"$prompt\". Ensure the prompt name is concise, relevant, and SEO-friendly without special characters except 'dash'. The details should also be SEO optimized, free from special characters except 'dash', and have a header '**Details:**'. Format the response with two distinct sections: '**Prompt Name:**' followed by the name and '**Details:**' followed by the description."

                ]
            ],
        ],
    ]);

    $responseBody = json_decode($response->getBody()->getContents(), true);
    $assistantContent = $responseBody['choices'][0]['message']['content'] ?? '';
    
    $promptNamePattern = '/\*\*Prompt Name:\*\*\s*(.*?)\s*\*\*Details:\*\*/s';  // Extract everything before **Details:**
    $detailsPattern = '/\*\*Details:\*\*\s*(.+)/s';  // Extract everything after **Details:**
    
    $promptName = '';
    $details = '';
    
    if (preg_match($promptNamePattern, $assistantContent, $matches)) {
        $promptName = trim($matches[1]);  // Extract prompt name
        $promptName = str_replace('-', ' ', $promptName);  // Replace dashes with spaces
        $promptName = ucwords($promptName);  // Convert to title case
    }
    
    if (preg_match($detailsPattern, $assistantContent, $matches)) {
        $details = trim($matches[1]);  // Extract details after **Details:**
    }
    
    // Log the results
    Log::info("Prompt Name: " . $promptName);
    Log::info("Details: " . $details);
    
    
    return response()->json([
        'promptName' => $promptName,
        'details' => $details,
    ]);
    

}


public function saveToLibrary(Request $request)
{
    // Generate slug from prompt name
    $slug = Str::slug($request->prompt_name);
    
    // Validate the incoming request
    $validated = $request->validate([
        'prompt_name' => 'required|string',
        'prompt' => 'required|string',
        'category' => 'required|string',
        'subcategory' => 'required|string',
        'details' => 'nullable|string',
    ]);

    // Create a new instance of PromptLibrary
    $library = new PromptLibrary();
    $library->prompt_name = $validated['prompt_name'];         // Save the prompt name
    $library->category_id = $validated['category'];       // Save the category
    $library->sub_category_id = $validated['subcategory']; // Save the subcategory
    $library->description = $validated['details'];        // Save the details
    $library->slug = $slug;                               // Save the generated slug
    $library->actual_prompt = $validated['prompt'];    // Save the actual prompt

    // Save the new record in the database
    $library->save();

    // Return a success response
    return response()->json(['message' => 'Prompt saved successfully!']);
}



}
