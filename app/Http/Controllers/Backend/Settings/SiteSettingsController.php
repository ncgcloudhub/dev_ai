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

        $faviconName = '';
        if ($favicon = $request->file('favicon')){
            $faviconName = time().'-'.uniqid().'.'.$favicon->getClientOriginalExtension();
            $favicon->move('backend/uploads/site', $faviconName);
        }

        $header_logo_light_Name = '';
        if ($header_logo_light = $request->file('header_logo_light')){
            $header_logo_light_Name = time().'-'.uniqid().'.'.$header_logo_light->getClientOriginalExtension();
            $header_logo_light->move('backend/uploads/site', $header_logo_light_Name);
        }

        $header_logo_dark_Name = '';
        if ($header_logo_dark = $request->file('header_logo_dark')){
            $header_logo_dark_Name = time().'-'.uniqid().'.'.$header_logo_dark->getClientOriginalExtension();
            $header_logo_dark->move('backend/uploads/site', $header_logo_dark_Name);
        }

        $banner_img_Name = '';
        if ($banner_img = $request->file('banner_img')){
            $banner_img_Name = time().'-'.uniqid().'.'.$banner_img->getClientOriginalExtension();
            $banner_img->move('backend/uploads/site', $banner_img_Name);
        }

        $footer_logo_Name = '';
        if ($footer_logo = $request->file('footer_logo')){
            $footer_logo_Name = time().'-'.uniqid().'.'.$footer_logo->getClientOriginalExtension();
            $footer_logo->move('backend/uploads/site', $footer_logo_Name);
        }

        $expert_id = SiteSettings::insertGetId([
            
            'favicon'=>$faviconName,
            'title' => $request->title,
            'header_logo_light'=>$header_logo_light_Name,
            'header_logo_dark'=>$header_logo_dark_Name,
            'banner_img'=>$banner_img_Name,
            'banner_text' => $request->banner_text,
            'footer_logo'=>$footer_logo_Name,
            'footer_text' => $request->footer_text,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,
            
            'created_at' => Carbon::now(),   
    
          ]);

        $notification = array(
            'message' => 'Expert Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }
}
