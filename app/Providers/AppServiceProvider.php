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
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
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
}
