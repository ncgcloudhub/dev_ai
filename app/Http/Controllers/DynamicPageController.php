<?php

namespace App\Http\Controllers;

use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        // Validate incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'route' => 'required|string|unique:dynamic_pages',
            'content' => 'required|string',
            'seo_title' => 'nullable|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        // Convert route to slug
        $validated['route'] = trim($request->input('route'), '/'); 

        // Create a new dynamic page
        $dynamicPage = DynamicPage::create($validated);

        // Redirect to a relevant page after creation
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
}
