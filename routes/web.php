<?php

use App\Models\DalleImageGenerate;
use App\Models\Template;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\AI\CustomTemplateController;
use App\Http\Controllers\Backend\AI\TemplateController;
use App\Http\Controllers\Backend\AI\AIChatController;
use App\Http\Controllers\Backend\AI\ExpertController;
use App\Http\Controllers\Backend\AI\GenerateImagesController;
use App\Http\Controllers\Backend\ProfileEditController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\Settings\AISettingsController;

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $images = DalleImageGenerate::where('status', 'active')->get();
    $templates = Template::orderby('id', 'asc')->limit(8)->get();
    return view('frontend.index', compact('images','templates'));
})->name('home');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin Middleware
Route::middleware(['auth', 'role:admin'])->group(function(){

    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');

    // AI Settings
    Route::prefix('settings/OpenAI')->group(function(){

        Route::get('/add', [AISettingsController::class, 'AIsettingsAdd'])->name('ai.settings.add');

        Route::post('/store', [AISettingsController::class, 'AIsettingsStore'])->name('ai.settings.store');
        
    });


    // Templates
Route::prefix('template')->group(function(){

    Route::get('/category/add', [TemplateController::class, 'TemplateCategoryAdd'])->name('template.category.add');
    
    Route::post('/category/store', [TemplateController::class, 'TemplateCategoryStore'])->name('template.category.store');
    
    Route::get('/add', [TemplateController::class, 'TemplateAdd'])->name('template.add');

    Route::post('store', [TemplateController::class, 'TemplateStore'])->name('template.store');
    
    
    
    });



    // Dalle Manage Image
    Route::get('/image/manage', [GenerateImagesController::class, 'DalleImageManageAdmin'])->name('manage.dalle.image.admin');

    Route::post('/update/image/status', [GenerateImagesController::class, 'UpdateStatus'])->name('update.status.dalle.image.admin');


});//End Admin Middleware

Route::post('/update/image/status', [GenerateImagesController::class, 'UpdateStatus'])->name('update.status.dalle.image.admin');

// User Middleware
Route::middleware(['auth', 'role:user'])->group(function(){

    // User Routes
    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

});//End User Middleware


// Custom Templates
Route::prefix('custom/template')->group(function(){

    Route::get('/category/add', [CustomTemplateController::class, 'CustomTemplateCategoryAdd'])->name('custom.template.category.add');
    
    Route::post('/category/store', [CustomTemplateController::class, 'CustomTemplateCategoryStore'])->name('custom.template.category.store');
    
    Route::get('/add', [CustomTemplateController::class, 'CustomTemplateAdd'])->name('custom.template.add');

    Route::post('store', [CustomTemplateController::class, 'CustomTemplateStore'])->name('custom.template.store');
    
    Route::get('/manage', [CustomTemplateController::class, 'CustomTemplateManage'])->name('custom.template.manage');
    
    Route::get('/view/{id}', [CustomTemplateController::class, 'CustomTemplateView'])->name('custom.template.view');

    Route::post('/generate', [CustomTemplateController::class, 'customtemplategenerate'])->name('custom.template.generate');
    
    });

Route::prefix('chat')->group(function(){

        // CHAT
    Route::get('/expert/add', [ExpertController::class, 'ExpertAdd'])->name('expert.add');
    Route::post('/expert/store', [ExpertController::class, 'ExpertStore'])->name('expert.store');



    // TEST CHAT
    Route::get('/expert/view', [ExpertController::class, 'index'])->name('chat');
    Route::get('/expert/{id}', [ExpertController::class, 'ExpertChat'])->name('expert.chat');
    Route::post('/reply', [AIChatController::class, 'SendMessages']);
        
    });


Route::prefix('generate')->group(function() {
        Route::get('/image/view', [GenerateImagesController::class, 'AIGenerateImageView'])->name('generate.image.view');
        Route::post('/image', [GenerateImagesController::class, 'generateImage'])->name('generate.image');
        });


//Profile 
    Route::prefix('profile')->group(function() {
        Route::get('/edit', [ProfileEditController::class, 'ProfileEdit'])->name('edit.profile');
        Route::post('/update', [ProfileEditController::class, 'ProfileUpdate'])->name('update.profile');
    });

//AI Image Gallery Page
    Route::get('/ai/image/gallery', [HomeController::class, 'AIImageGallery'])->name('ai.image.gallery');

    
//Fixed Templates 

Route::get('template/manage', [TemplateController::class, 'TemplateManage'])->name('template.manage');
    
Route::get('template/view/{id}', [TemplateController::class, 'TemplateView'])->name('template.view');

Route::post('template/generate', [TemplateController::class, 'templategenerate'])->name('template.generate');


// GOOGLE SOCIALITE
Route::get('google/login', [TemplateController::class, 'provider'])->name('google.login');
Route::get('google/callback', [TemplateController::class, 'callbackHandel'])->name('google.login.callback');


// GITHUB SOCIALITE
Route::get('github/login', [TemplateController::class, 'githubprovider'])->name('github.login');
Route::get('github/callback', [TemplateController::class, 'githubcallbackHandel'])->name('github.login.callback');