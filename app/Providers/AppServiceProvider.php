<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\SiteSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $siteSettings = SiteSettings::find(1); // Assuming the site settings are stored in the database with ID 1
            $view->with('siteSettings', $siteSettings);
        });

        View::composer('*', function ($view) {
            // Get the current route name
            $routeName = Route::currentRouteName();
        
            // Assuming your routes are named after the pages
            $page = Page::where('route_name', $routeName)->first();
        
            // If page not found, fallback to a default page or create a default SEO record
            if (!$page) {
                $page = Page::where('route_name', 'default')->first();
            }
        
            // Pass the SEO data to the view
            $view->with('seo', $page);
        });
    }

    /**
     * Calculate Credits based on resolution.
     *
     * @param string $resolution
     * @return int
     */
    protected function calculateCredits($resolution)
    {
        switch ($resolution) {
            case '512x512':
                return 2;
            case '1024x1024':
                return 3;
            default:
                return 1;
        }
    }
}