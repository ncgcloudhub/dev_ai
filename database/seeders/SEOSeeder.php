<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SEOSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('seo_settings')->insert([
            'description' => 'path_to_favicon',
            'keywords' => 'Your Site Title',
            'canonical_url' => 'path_to_header_logo_light',
            'sitemap_url' => 'path_to_header_logo_dark',
            'created_at' => now(),
        ]);
    }
}
