<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\CustomTemplateController;
use App\Http\Controllers\Backend\AIChatController;
use App\Http\Controllers\Backend\DallEImageGenerate;
use App\Http\Controllers\Backend\Settings\AISettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
});

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

});//End Admin Middleware


// User Middleware
Route::middleware(['auth', 'role:user'])->group(function(){

    // User Routes
    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

});//End User Middleware


// Custom Templates
Route::prefix('custom/Template')->group(function(){

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
    Route::get('/expert/add', [AIChatController::class, 'ExpertAdd'])->name('expert.add');
    Route::post('/expert/store', [AIChatController::class, 'ExpertStore'])->name('expert.store');



    // TEST CHAT
    Route::get('/expert/view', [AIChatController::class, 'index'])->name('chat');
    Route::get('/expert/{id}', [AIChatController::class, 'ExpertChat'])->name('expert.chat');
    Route::post('/chat', [AIChatController::class, 'SendMessages']);
        
        });

        

Route::prefix('generate')->group(function() {
        Route::get('/image/view', [DallEImageGenerate::class, 'AIGenerateImageView'])->name('generate.image.view');
        Route::post('/image', [DallEImageGenerate::class, 'generateImage'])->name('generate.image');
        });
        



 