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
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;


class SiteSettingsController extends Controller
{
    public function SitesettingsAdd()
    {
        $setting = SiteSettings::find(1);
        $buttonStyles = ButtonStyle::all();

        return view('admin.site_settings.site_settings_add', compact('setting','buttonStyles'));
    }

    private function uploadVideoToAzure($file, $azureFileName)
    {
        try {
            $mime = $file->getMimeType();

            if (!in_array($mime, ['video/webm', 'image/gif'])) {
                throw new \Exception("Invalid file type: {$mime}. Only GIF and WebM are allowed.");
            }

            $extension = $mime === 'image/gif' ? 'gif' : 'webm';

            $content = file_get_contents($file->getPathname());

            $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
            $container = config('filesystems.disks.azure.container');

            $blobClient->createBlockBlob(
                $container,
                "site-settings/{$azureFileName}.{$extension}",
                $content,
                new CreateBlockBlobOptions()
            );

            return "site-settings/{$azureFileName}.{$extension}";
        } catch (\Exception $e) {
            Log::error("Magic Ball video upload failed: " . $e->getMessage());
            return null;
        }
    }


    private function uploadImageToAzure($file, $azureFileName)
    {
        try {
            $image = Image::make($file)
                ->resize(1920, null, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                })
                ->encode('webp', 80);

            $blobClient = BlobRestProxy::createBlobService(config('filesystems.disks.azure.connection_string'));
            $container = config('filesystems.disks.azure.container');

            $blobClient->createBlockBlob(
                $container,
                "site-settings/$azureFileName.webp",
                $image->__toString(),
                new CreateBlockBlobOptions()
            );

            return "site-settings/$azureFileName.webp";
        } catch (\Exception $e) {
            Log::error("Azure upload failed for $azureFileName", ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function SitesettingsStore(Request $request)
    {
        $updateData = [];

        if ($request->hasFile('favicon')) {
            $uploadedPath = $this->uploadImageToAzure($request->file('favicon'), 'favicon');
            if ($uploadedPath) {
                $updateData['favicon'] = $uploadedPath;
            }
        }

        if ($request->hasFile('banner_img')) {
            $uploadedPath = $this->uploadImageToAzure($request->file('banner_img'), 'banner');
            if ($uploadedPath) {
                $updateData['banner_img'] = $uploadedPath;
            }
        }

        if ($request->hasFile('watermark')) {
            $uploadedPath = $this->uploadImageToAzure($request->file('watermark'), 'watermark');
            if ($uploadedPath) {
                $updateData['watermark'] = $uploadedPath;
            }
        }

        if ($request->hasFile('header_logo_dark')) {
            $uploadedPath = $this->uploadImageToAzure($request->file('header_logo_dark'), 'header_logo_dark');
            if ($uploadedPath) {
                $updateData['header_logo_dark'] = $uploadedPath;
            }
        }

        if ($request->hasFile('header_logo_light')) {
            $uploadedPath = $this->uploadImageToAzure($request->file('header_logo_light'), 'header_logo_light');
            if ($uploadedPath) {
                $updateData['header_logo_light'] = $uploadedPath;
            }
        }

        if ($request->hasFile('magic_ball')) {
            $uploadedPath = $this->uploadVideoToAzure($request->file('magic_ball'), 'magic_ball');
            if ($uploadedPath) {
                $updateData['magic_ball'] = $uploadedPath;
            }
        }

        if ($request->hasFile('footer_logo')) {
            $uploadedPath = $this->uploadImageToAzure($request->file('footer_logo'), 'footer_logo');
            if ($uploadedPath) {
                $updateData['footer_logo'] = $uploadedPath;
            }
        }

        if ($request->hasFile('generate_image_webm')) {
            $uploadedPath = $this->uploadVideoToAzure($request->file('generate_image_webm'), 'generate_image_webm');
            if ($uploadedPath) {
                $updateData['generate_image_webm'] = $uploadedPath;
            }
        }

        if ($request->hasFile('generate_content_webm')) {
            $uploadedPath = $this->uploadVideoToAzure($request->file('generate_content_webm'), 'generate_content_webm');
            if ($uploadedPath) {
                $updateData['generate_content_webm'] = $uploadedPath;
            }
        }

        if ($request->hasFile('prompt_library_webm')) {
            $uploadedPath = $this->uploadVideoToAzure($request->file('prompt_library_webm'), 'prompt_library_webm');
            if ($uploadedPath) {
                $updateData['prompt_library_webm'] = $uploadedPath;
            }
        }

        if ($request->hasFile('chat_bot_webm')) {
            $uploadedPath = $this->uploadVideoToAzure($request->file('chat_bot_webm'), 'chat_bot_webm');
            if ($uploadedPath) {
                $updateData['chat_bot_webm'] = $uploadedPath;
            }
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

    public function updateRolloverSetting(Request $request)
{
    $request->validate([
        'rollover_enabled' => 'required|in:0,1',
    ]);

    $setting = SiteSettings::first(); // or Setting::find(1) if using a specific ID

    if (!$setting) {
        return redirect()->back()->with('error', 'Settings not found.');
    }

    $setting->rollover_enabled = $request->rollover_enabled;
    $setting->save();

    return redirect()->back()->with('success', 'Rollover setting updated successfully.');
}

}
