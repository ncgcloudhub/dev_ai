<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSettings;
use Illuminate\Support\Carbon;

class SiteSettingsController extends Controller
{
    public function SitesettingsAdd(){
        $setting = SiteSettings::find(1);
      
        return view('admin.site_settings.site_settings_add', compact('setting'));
    }

    public function SitesettingsStore(Request $request)
    {

        $updateData = [];

        if ($request->hasFile('favicon')) {
            // Unlink the old favicon if it exists
            $oldFavicon = SiteSettings::findOrFail(1)->favicon;
            if ($oldFavicon) {
                unlink(public_path('backend/uploads/site/' . $oldFavicon));
            }

            $favicon = $request->file('favicon');
            $faviconName = time().'-'.uniqid().'.'.$favicon->getClientOriginalExtension();
            $favicon->move('backend/uploads/site', $faviconName);
       
            $updateData['favicon'] = $faviconName;
        }

        if ($request->hasFile('header_logo_light')) {
             $oldHeaderLogoLight = SiteSettings::findOrFail(1)->header_logo_light;
             if ($oldHeaderLogoLight) {
                 unlink(public_path('backend/uploads/site/' . $oldHeaderLogoLight));
             }

            $header_logo_light = $request->file('header_logo_light');
            $header_logo_light_Name = time().'-'.uniqid().'.'.$header_logo_light->getClientOriginalExtension();
            $header_logo_light->move('backend/uploads/site', $header_logo_light_Name);
            $updateData['header_logo_light'] = $header_logo_light_Name;
        }

        if ($request->hasFile('header_logo_dark')) {
            $oldHeaderLogoDark = SiteSettings::findOrFail(1)->header_logo_dark;
            if ($oldHeaderLogoDark) {
                unlink(public_path('backend/uploads/site/' . $oldHeaderLogoDark));
            }

            $header_logo_dark = $request->file('header_logo_dark');
            $header_logo_dark_Name = time().'-'.uniqid().'.'.$header_logo_dark->getClientOriginalExtension();
            $header_logo_light->move('backend/uploads/site', $header_logo_dark_Name);
            $updateData['header_logo_dark'] = $header_logo_dark_Name;
        }

        if ($request->hasFile('banner_img')) {
            $oldBannerImg = SiteSettings::findOrFail(1)->banner_img;
            if ($oldBannerImg) {
                unlink(public_path('backend/uploads/site/' . $oldBannerImg));
            }

            $banner_img = $request->file('banner_img');
            $banner_img_Name = time().'-'.uniqid().'.'.$banner_img->getClientOriginalExtension();
            $banner_img->move('backend/uploads/site', $banner_img_Name);
            $updateData['banner_img'] = $banner_img_Name;
        }

        if ($request->hasFile('footer_logo')) {
            $oldFooterLogo = SiteSettings::findOrFail(1)->footer_logo;
            if ($oldFooterLogo) {
                unlink(public_path('backend/uploads/site/' . $oldFooterLogo));
            }
            $footer_logo = $request->file('footer_logo');
            $footer_logo_Name = time().'-'.uniqid().'.'.$footer_logo->getClientOriginalExtension();
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

        SiteSettings::findOrFail(1)->update($updateData);

        return redirect()->back()->with('success', 'Settings updated Successfully');

    }
}
