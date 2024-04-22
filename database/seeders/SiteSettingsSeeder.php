<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert a single record into the site_settings table
        DB::table('site_settings')->insert([
            'favicon' => 'path_to_favicon',
            'title' => 'Your Site Title',
            'header_logo_light' => 'path_to_header_logo_light',
            'header_logo_dark' => 'path_to_header_logo_dark',
            'banner_img' => 'path_to_banner_img',
            'banner_text' => 'Your Banner Text',
            'footer_logo' => 'path_to_footer_logo',
            'footer_text' => 'Your Footer Text',
            'facebook' => 'your_facebook_url',
            'instagram' => 'your_instagram_url',
            'youtube' => 'your_youtube_url',
            'linkedin' => 'your_linkedin_url',
            'twitter' => 'your_twitter_url',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
