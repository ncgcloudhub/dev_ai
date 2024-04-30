<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\Request;

class SEOController extends Controller
{
    public function SeosettingsAdd()
    {
        $seo = SeoSetting::find(1);

        return view('admin.seo_settings.seo_settings_add', compact('seo'));
    }

    public function SeosettingsStore(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'description' => 'required|string',
        'keywords' => 'required|string',
        'canonical_url' => 'required|string',
        'sitemap_url' => 'required|string',
    ]);

    // Find the SEO settings record by ID
    $seo = SeoSetting::find(1);

    // If the record doesn't exist, return a response or handle it accordingly
    if (!$seo) {
        return response()->json(['message' => 'SEO settings not found'], 404);
    }

    // Update the SEO settings with the form data
    $seo->update([
        'description' => $validatedData['description'],
        'keywords' => $validatedData['keywords'],
        'canonical_url' => $validatedData['canonical_url'],
        'sitemap_url' => $validatedData['sitemap_url'],
    ]);

    // Optionally, you can return a response indicating success or redirect to a different page
    return redirect()->back()->with('success', 'SEO updated Successfully');
}
}
