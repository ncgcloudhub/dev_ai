<?php

namespace App\Http\Controllers;

use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DynamicPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dynamicPage = DynamicPage::latest()->get(); // Fetch a specific dynamic page or however you retrieve it

        return view('backend.dynamic_pages.all_dynamic_pages', [
            'dynamicPage' => $dynamicPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.dynamic_pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'route' => [
                'required',
                'string',
                'max:255',
                'unique:dynamic_pages,route', // Ensure route is unique
            ],
            'content' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
    
        // Convert route to lowercase and remove leading/trailing slashes
        $validated['route'] = Str::lower(trim($validated['route'], '/'));
    
        // Check if the route already exists (redundant due to validation, but for clarity)
        $existingPage = DynamicPage::where('route', $validated['route'])->first();
        if ($existingPage) {
            return redirect()->back()
                ->withErrors(['route' => 'The route name already exists.'])
                ->withInput();
        }
    
        // Create a new dynamic page
        $dynamicPage = DynamicPage::create($validated);
    
        // Redirect to the index or details page with success message
        return redirect()->route('dynamic-pages.index', ['dynamic_page' => $dynamicPage->route])
            ->with('success', 'Dynamic page created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show($route)
    {
        // Find the dynamic page by route
        $page = DynamicPage::where('route', $route)->firstOrFail();

        // Render the view for the dynamic page
        return view('backend.dynamic_pages.dynamic_page', ['page' => $page]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dynamicPage = DynamicPage::findOrFail($id);
        return view('backend.dynamic_pages.dynamic_page_edit', compact('dynamicPage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'required|string|unique:dynamic_pages,route,' . $id,
            'content' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Find the dynamic page by ID and update it
        $dynamicPage = DynamicPage::findOrFail($id);
        $dynamicPage->update($validated);

        // Redirect to a relevant page after update
        return redirect()->route('dynamic-pages.index')
            ->with('success', 'Dynamic page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = DynamicPage::findOrFail($id);
        $page->delete();

        $notification = array(
            'message' => 'Dynamic Page Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('dynamic-pages.index')->with($notification);
    }


    public function generateSeoContent(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
    ]);

    $title = $request->input('title');
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
                    "content" => "You are an SEO assistant."
                ],
                [
                    "role" => "user",
                    "content" => "Generate an SEO title not more than 60 characters, description not more than 160 characters, and 20 comma-separated tags for the title: $title."
                ]
            ],
        ],
    ]);

    $responseBody = json_decode($response->getBody()->getContents(), true);
    $assistantContent = $responseBody['choices'][0]['message']['content'] ?? '';

    preg_match('/\*\*SEO Title:\*\*\s*(.*)/', $assistantContent, $titleMatches);
    preg_match('/\*\*SEO Description:\*\*\s*(.*)/', $assistantContent, $descriptionMatches);
    preg_match('/\*\*Tags:\*\*\s*(.*)$/s', $assistantContent, $tagsMatches);


    $seoTitle = $titleMatches[1] ?? 'No title generated';
    $seoDescription = $descriptionMatches[1] ?? 'No description generated';
    $seoTags = $tagsMatches[1] ?? 'No tags generated';


    return response()->json([
        'success' => true,
        'seo_title' => $seoTitle,
        'seo_description' => $seoDescription,
        'seo_tags' => $seoTags,
    ]);
}

}
