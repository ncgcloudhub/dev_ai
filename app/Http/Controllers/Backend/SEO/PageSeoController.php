<?php

namespace App\Http\Controllers\Backend\SEO;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PageSeoController extends Controller
{
    public function addPageSeo()
    {
        // Get all routes
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'name' => $route->getName(),
                'url' => $route->uri()
            ];
        })->filter()->toArray();

        // Pass routes to the view
        return view('admin.seo_settings.page_seo_add', ['routes' => $routes]);
    }
    
    public function getPageSeoDetails(Request $request)
    {
        $route = $request->query('route');
        $page = Page::where('route_name', $route)->first();

        if ($page) {
            return response()->json(['success' => true, 'page' => $page]);
        }

        return response()->json(['success' => false, 'message' => 'No SEO data found for this route.']);
    }

    public function storePageSeo(Request $request)
    {
       // Validate incoming data
    $validatedData = $request->validate([
        'route_name' => 'required|string',
        'title' => 'required|string',
        'keywords' => 'nullable|string',
        'description' => 'nullable|string',
    ]);

    // Check if a page already exists for the given route_name
    $page = Page::where('route_name', $validatedData['route_name'])->first();

    if ($page) {
        // Update the existing page record
        $page->update([
            'title' => $validatedData['title'],
            'keywords' => $validatedData['keywords'],
            'description' => $validatedData['description'],
        ]);
    } else {
        // Create a new Page instance if no record exists
        $page = new Page();
        $page->route_name = $validatedData['route_name'];
        $page->title = $validatedData['title'];
        $page->keywords = $validatedData['keywords'];
        $page->description = $validatedData['description'];
        $page->save();
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Page SEO data saved successfully.');
    }
}
