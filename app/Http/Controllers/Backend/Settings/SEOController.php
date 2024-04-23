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
}
