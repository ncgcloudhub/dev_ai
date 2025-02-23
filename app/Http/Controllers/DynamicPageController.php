<?php

namespace App\Http\Controllers;

use App\Models\DynamicPage;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class DynamicPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('admin.permission:managePage.menu')->only('index');
        $this->middleware('admin.permission:managePage.add')->only(['create', 'store']);
        $this->middleware('admin.permission:managePage.edit')->only(['edit', 'update']);
        $this->middleware('admin.permission:managePage.delete')->only('destroy');
    }

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
        $template_categories = TemplateCategory::latest()->get();
        return view('backend.dynamic_pages.dynamic_page_create', compact('template_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Normalize the route
        $data['route'] = Str::lower(trim($data['route'], '/'));

        // Check if the route conflicts with existing routes in web.php
        $allRoutes = collect(Route::getRoutes())->pluck('uri')->toArray();
        if (in_array($data['route'], $allRoutes)) {
            return redirect()->back()
                ->withErrors(['route' => 'The route conflicts with an existing route in the application.'])
                ->withInput();
        }

        // Check if the route already exists in the database
        if (DynamicPage::where('route', $data['route'])->exists()) {
            return redirect()->back()
                ->withErrors(['route' => 'The route name already exists.'])
                ->withInput();
        }

        // Handle file uploads
        $thumbnailPath = $request->file('thumbnail_image') ? 
            $request->file('thumbnail_image')->store('dynamic-pages/thumbnails', 'public') : null;

        $bannerPath = $request->file('banner_image') ? 
            $request->file('banner_image')->store('dynamic-pages/banners', 'public') : null;

        // Handle multiple attached files
        $attachedFiles = [];
        if ($request->hasFile('attached_files')) {
            foreach ($request->file('attached_files') as $file) {
                // Get the original filename
                $filename = $file->getClientOriginalName();
                // Store the file with its original name in the specified directory
                $path = $file->storeAs('dynamic-pages/attachments', $filename, 'public');
                // Add the path to the array
                $attachedFiles[] = $path;
            }
        }

        // Get the base URL from .env
        $baseUrl = config('app.custom_url');

        // Ensure all URLs in content are absolute
        $content = $data['content'];
        $content = preg_replace_callback('/href="([^"]*)"/', function ($matches) use ($baseUrl) {
            $url = $matches[1];
            // If the URL is relative, prepend the base URL
            if (!preg_match('/^https?:\/\//', $url)) {
                $url = $baseUrl . '/' . ltrim($url, '/');
            }
            return 'href="' . $url . '"';
        }, $content);

        // Save the page to the database
        $dynamicPage = DynamicPage::create([
            'title' => $data['title'],
            'route' => $data['route'],
            'thumbnail_image' => $thumbnailPath,
            'banner_image' => $bannerPath,
            'content' => $content, // Use the modified content
            'category' => $data['category'],
            'social' => $request->has('social') ? 1 : 0, // ✅ FIX: Handle checkbox correctly
            'page_status' => $data['page_status'],
            'seo_title' => $data['seo_title'],
            'keywords' => $data['keywords'],
            'description' => $data['description'],
            'tags' => $data['tags'],
            'attached_files' => json_encode($attachedFiles), // Store as JSON
        ]);

        return redirect()->route('dynamic-pages.index')->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($route)
    {
        // Find the dynamic page by route
        $page = DynamicPage::where('route', $route)->where('page_status', 'completed')->firstOrFail();

        if (!$page) {
            return view('errors.404');
        }

        $recents = DynamicPage::where('page_status', 'completed')->limit(5)->get();

        $relatedPages = DynamicPage::where('category', $page->category)
        ->where('id', '!=', $page->id) // Exclude the current page
        ->where('page_status', 'completed')
        ->limit(5)
        ->get();

        $categories = DynamicPage::where('page_status', 'completed')
        ->select('category')
        ->distinct()
        ->limit(5) // Limiting to 5 categories
        ->get();

        // Render the view for the dynamic page
        return view('backend.dynamic_pages.dynamic_page', [
            'page' => $page,
            'recents' => $recents,
            'relatedPages' => $relatedPages,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dynamicPage = DynamicPage::findOrFail($id);
       // Decode the attached files (if any)
        $attachments = json_decode($dynamicPage->attached_files, true); 
        $template_categories = TemplateCategory::latest()->get();

    // Check if attachments are an array (in case it's empty or not set)
    $attachments = is_array($attachments) ? $attachments : [];
        return view('backend.dynamic_pages.dynamic_page_edit', compact('dynamicPage','attachments','template_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the page by its ID
        $dynamicPage = DynamicPage::findOrFail($id);
    
        $data = $request->all();
    
        // Normalize the route
        $data['route'] = Str::lower(trim($data['route'], '/'));
    
        // Check if the route conflicts with existing routes in web.php
        $allRoutes = collect(Route::getRoutes())->pluck('uri')->toArray();
        if (in_array($data['route'], $allRoutes) && $dynamicPage->route != $data['route']) {
            return redirect()->back()
                ->withErrors(['route' => 'The route conflicts with an existing route in the application.'])
                ->withInput();
        }
    
        // Check if the route already exists in the database (other than the current one)
        if (DynamicPage::where('route', $data['route'])->where('id', '!=', $id)->exists()) {
            return redirect()->back()
                ->withErrors(['route' => 'The route name already exists.'])
                ->withInput();
        }
    
        // Handle file uploads
        if ($request->hasFile('thumbnail_image')) {
            // Delete the old thumbnail if it exists
            if ($dynamicPage->thumbnail_image && file_exists(storage_path('app/public/' . $dynamicPage->thumbnail_image))) {
                unlink(storage_path('app/public/' . $dynamicPage->thumbnail_image));
            }
            // Store the new thumbnail
            $thumbnailPath = $request->file('thumbnail_image')->store('dynamic-pages/thumbnails', 'public');
            $data['thumbnail_image'] = $thumbnailPath;
        }
    
        if ($request->hasFile('banner_image')) {
            // Delete the old banner if it exists
            if ($dynamicPage->banner_image && file_exists(storage_path('app/public/' . $dynamicPage->banner_image))) {
                unlink(storage_path('app/public/' . $dynamicPage->banner_image));
            }
            // Store the new banner
            $bannerPath = $request->file('banner_image')->store('dynamic-pages/banners', 'public');
            $data['banner_image'] = $bannerPath;
        }
    
        // Handle multiple attached files (if any)
        if ($request->hasFile('attached_files')) {
            // Delete old attached files
            if ($dynamicPage->attached_files) {
                $oldFiles = json_decode($dynamicPage->attached_files, true);
                foreach ($oldFiles as $file) {
                    if (file_exists(storage_path('app/public/' . $file))) {
                        unlink(storage_path('app/public/' . $file));
                    }
                }
            }

            $attachedFiles = [];
            foreach ($request->file('attached_files') as $file) {
                // Get the original filename
                $filename = $file->getClientOriginalName();
                
                // Store the file with its original name in the specified directory
                $path = $file->storeAs('dynamic-pages/attachments', $filename, 'public');
                
                // Add the path to the array
                $attachedFiles[] = $path;
            }
            
            // Save the attached files as a JSON-encoded string
            $data['attached_files'] = json_encode($attachedFiles);
        }

    
        // Update the page in the database
        $dynamicPage->update([
            'title' => $data['title'],
            'route' => $data['route'],
            'thumbnail_image' => $data['thumbnail_image'] ?? $dynamicPage->thumbnail_image,
            'banner_image' => $data['banner_image'] ?? $dynamicPage->banner_image,
            'content' => $data['content'],
            'page_status' => $data['page_status'],
            'seo_title' => $data['seo_title'],
            'keywords' => $data['keywords'],
            'category' => $data['category'],
            'social' => $request->has('social') ? 1 : 0,
            'description' => $data['description'],
            'tags' => $data['tags'],
            'attached_files' => $data['attached_files'] ?? $dynamicPage->attached_files, // Keep existing files if none uploaded
        ]);
    
        return redirect()->route('dynamic-pages.index')->with('success', 'Page updated successfully.');
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

    public function checkRouteAvailability(Request $request)
{
    $route = Str::lower(trim($request->get('route', ''), '/')); // Sanitize input

    // Check if route exists in the database
    $existsInDatabase = DynamicPage::where('route', $route)->exists();

    // Check if route exists in web.php
    $existsInRoutes = collect(Route::getRoutes()->getRoutes())->contains(function ($routeObj) use ($route) {
        return $routeObj->uri() === $route;
    });

    if ($existsInDatabase) {
        return response()->json([
            'status' => 'taken',
            'message' => 'This route is already taken.',
        ]);
    }

    if ($existsInRoutes) {
        return response()->json([
            'status' => 'declared',
            'message' => 'This route is already declared in route files (web.php).',
        ]);
    }

    return response()->json([
        'status' => 'available',
        'message' => 'This route is available.',
    ]);
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
                    "content" => "Generate an SEO title not more than 60 characters, make sure the title doesn't have any special characters except 'dash', for description not more than 160 characters, and 20 comma-separated tags for the title: $title. The title, description, and tags should be SEO optimized. The header for the Title should be '**SEO Title:**', the header for Description should be '**SEO Description:**', and the header for the Tags should be '**Tags:**'"
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
