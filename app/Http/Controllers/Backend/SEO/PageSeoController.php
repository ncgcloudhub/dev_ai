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

    public function storePageSeo(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'route_name' => 'required|string',
            'title' => 'required|string',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Create a new Page instance
        $page = new Page();

        // Fill the Page instance with the validated data
        $page->route_name = $validatedData['route_name'];
        $page->title = $validatedData['title'];
        $page->keywords = $validatedData['keywords'];
        $page->description = $validatedData['description'];

        // Save the Page instance to the database
        $page->save();

        // Redirect the user to a success page or back to the form with a success message
        return redirect()->back()->with('success', 'Page SEO added successfully!');
    }
}
