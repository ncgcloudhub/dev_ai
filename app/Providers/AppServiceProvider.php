<?php

namespace App\Providers;

use App\Models\SiteSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
    }
}
