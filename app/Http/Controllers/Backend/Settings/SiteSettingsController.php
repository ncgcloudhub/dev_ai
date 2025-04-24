<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\ButtonStyle;
use Illuminate\Http\Request;
use App\Models\SiteSettings;
use App\Notifications\TokenRenewed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SiteSettingsController extends Controller
{
    public function SitesettingsAdd()
    {
        $setting = SiteSettings::find(1);
        $buttonStyles = ButtonStyle::all();

        return view('admin.site_settings.site_settings_add', compact('setting','buttonStyles'));
    }

    public function SitesettingsStore(Request $request)
    {
        $updateData = [];

        if ($request->hasFile('favicon')) {
            // Get current favicon from the DB
            $siteSettings = SiteSettings::findOrFail(1);
            $oldFavicon = $siteSettings->favicon;
        
            // Safely delete the old favicon if it exists
            $oldFaviconPath = public_path('backend/uploads/site/' . $oldFavicon);
            if ($oldFavicon && file_exists($oldFaviconPath)) {
                @unlink($oldFaviconPath);
            }
        
            // Upload new favicon
            $favicon = $request->file('favicon');
            $faviconName = time() . '-' . uniqid() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('backend/uploads/site'), $faviconName);
        
            $updateData['favicon'] = $faviconName;
        }
        
        if ($request->hasFile('watermark')) {
            $oldwatermark = SiteSettings::findOrFail(1)->watermark;
            if ($oldwatermark) {
                unlink(public_path('backend/uploads/site/' . $oldwatermark));
            }

            $watermark = $request->file('watermark');
            $watermark_Name = time() . '-' . uniqid() . '.' . $watermark->getClientOriginalExtension();
            $watermark->move('backend/uploads/site', $watermark_Name);
            $updateData['watermark'] = $watermark_Name;
        }

        if ($request->hasFile('header_logo_dark')) {
            $oldHeaderLogoDark = SiteSettings::findOrFail(1)->header_logo_dark;
            if ($oldHeaderLogoDark) {
                unlink(public_path('backend/uploads/site/' . $oldHeaderLogoDark));
            }

            $header_logo_dark = $request->file('header_logo_dark');
            $header_logo_dark_Name = time() . '-' . uniqid() . '.' . $header_logo_dark->getClientOriginalExtension();
            $header_logo_dark->move('backend/uploads/site', $header_logo_dark_Name);
            $updateData['header_logo_dark'] = $header_logo_dark_Name;
        }

        if ($request->hasFile('header_logo_light')) {
            // Retrieve the old header logo name from the database
            $oldHeaderLogoLight = SiteSettings::findOrFail(1)->header_logo_light;
        
            // Check if the old header logo exists before trying to unlink it
            if ($oldHeaderLogoLight && file_exists(public_path('backend/uploads/site/' . $oldHeaderLogoLight))) {
                unlink(public_path('backend/uploads/site/' . $oldHeaderLogoLight));
            }
        
            $header_logo_light = $request->file('header_logo_light');
            $header_logo_light_Name = time() . '-' . uniqid() . '.' . $header_logo_light->getClientOriginalExtension();
            $header_logo_light->move('backend/uploads/site', $header_logo_light_Name);
        
            $updateData['header_logo_light'] = $header_logo_light_Name;
        }
        

        if ($request->hasFile('banner_img')) {
            $oldBannerImg = SiteSettings::findOrFail(1)->banner_img;
            if ($oldBannerImg) {
                unlink(public_path('backend/uploads/site/' . $oldBannerImg));
            }

            $banner_img = $request->file('banner_img');
            $banner_img_Name = time() . '-' . uniqid() . '.' . $banner_img->getClientOriginalExtension();
            $banner_img->move('backend/uploads/site', $banner_img_Name);
            $updateData['banner_img'] = $banner_img_Name;
        }

        if ($request->hasFile('magic_ball')) {
            $oldmagic_ball = SiteSettings::findOrFail(1)->magic_ball;
            if ($oldmagic_ball) {
                unlink(public_path('backend/uploads/site/' . $oldmagic_ball));
            }
            $magic_ball = $request->file('magic_ball');
            $magic_ball_Name = time() . '-' . uniqid() . '.' . $magic_ball->getClientOriginalExtension();
            $magic_ball->move('backend/uploads/site', $magic_ball_Name);
            $updateData['magic_ball'] = $magic_ball_Name;
        }

        if ($request->hasFile('footer_logo')) {
            $oldFooterLogo = SiteSettings::findOrFail(1)->footer_logo;
            if ($oldFooterLogo) {
                unlink(public_path('backend/uploads/site/' . $oldFooterLogo));
            }
            $footer_logo = $request->file('footer_logo');
            $footer_logo_Name = time() . '-' . uniqid() . '.' . $footer_logo->getClientOriginalExtension();
            $footer_logo->move('backend/uploads/site', $footer_logo_Name);
            $updateData['footer_logo'] = $footer_logo_Name;
        }

        if ($request->filled('title')) {
            $updateData['title'] = $request->title;
        }

        if ($request->filled('banner_text')) {
            $updateData['banner_text'] = $request->banner_text;
        }

        if ($request->filled('footer_text')) {
            $updateData['footer_text'] = $request->footer_text;
        }

        if ($request->filled('facebook')) {
            $updateData['facebook'] = $request->facebook;
        }

        if ($request->filled('instagram')) {
            $updateData['instagram'] = $request->instagram;
        }

        if ($request->filled('youtube')) {
            $updateData['youtube'] = $request->youtube;
        }

        if ($request->filled('linkedin')) {
            $updateData['linkedin'] = $request->linkedin;
        }

        if ($request->filled('twitter')) {
            $updateData['twitter'] = $request->twitter;
        }

         // Add these new blocks for timestamp fields
        if ($request->filled('domain')) {
            $updateData['domain'] = $request->domain;
        }

        if ($request->filled('hosting')) {
            $updateData['hosting'] = $request->hosting;
        }

        if ($request->filled('ssl')) {
            $updateData['ssl'] = $request->ssl;
        }

        SiteSettings::findOrFail(1)->update($updateData);

        return redirect()->back()->with('success', 'Settings updated Successfully');
    }

    // HEX STore
    public function storeHex(Request $request)
    {
        $user = auth()->user();

        $user->notify(new TokenRenewed(3, 4));
        return redirect()->back()->with('success', 'Noti successfullyyyyyy!');

        // Validate input
        $request->validate([
            'hex_pass' => 'required|string|size:16',
        ]);

        // Save hex_pass to the database
        $siteSettings = SiteSettings::first(); // Or create a new record if needed
        $siteSettings->hex_pass = $request->input('hex_pass');
        $siteSettings->save();

       // Update .env file safely
    $envPath = base_path('.env');
    $envContent = File::get($envPath);

    if (strpos($envContent, 'API_HEX_KEY=') !== false) {
        // Replace existing API_HEX_KEY value
        $envContent = preg_replace(
            "/API_HEX_KEY=.*/",
            "API_HEX_KEY=" . $request->input('hex_pass'),
            $envContent
        );
    } else {
        // Append the key if it doesn't exist
        $envContent .= "\nAPI_HEX_KEY=" . $request->input('hex_pass');
    }

    File::put($envPath, $envContent);

    // Clear configuration cache so new .env values are used immediately
    Artisan::call('config:clear');

    return redirect()->back()->with('success', 'Hex Password generated and saved successfully!');
    }
}
