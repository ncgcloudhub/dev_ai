<?php

namespace App\Providers;

use App\Models\ButtonDesign;
use App\Models\Page;
use App\Models\SiteSettings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

        // Package WISE AI Model
        View::composer('*', function ($view) {
            $data = getUserLastPackageAndModels();
            $selectedAiModel = Session::get('selectedAiModel');
            $view->with(array_merge($data, compact('selectedAiModel')));
        });

      // Pass dynamic buttons and icons to all views
View::composer('*', function ($view) {
    $buttons = ButtonDesign::all(); // Fetch all buttons from the database
    
    // Generate the icon map
    $buttonIcons = [];
    foreach ($buttons as $button) {
        $buttonIcons[$button->button_type] = ($button->icon ?? '');
    }

    // Share both $buttons and $buttonIcons to all views
    $view->with([
        'buttons' => $buttons,
        'buttonIcons' => $buttonIcons
    ]);
});


    }
}