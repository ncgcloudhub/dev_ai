<?php

use App\Models\DalleImageGenerate;
use App\Models\Template;
use App\Models\CustomTemplate;
use App\Models\User;
use App\Models\Expert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationPromptController as AuthEmailVerificationPromptController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\AI\CustomTemplateController;
use App\Http\Controllers\Backend\AI\TemplateController;
use App\Http\Controllers\Backend\AI\AIChatController;
use App\Http\Controllers\Backend\AI\ExpertController;
use App\Http\Controllers\Backend\AI\GenerateImagesController;
use App\Http\Controllers\Backend\FAQ\FAQController;
use App\Http\Controllers\Backend\Job\JobController;
use App\Http\Controllers\Backend\Pricing\PricingController;
use App\Http\Controllers\Backend\ProfileEditController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Backend\Settings\AISettingsController;
use App\Http\Controllers\Backend\Settings\SiteSettingsController;
use App\Http\Controllers\Backend\User\UserManageController;
use App\Http\Controllers\Backend\PromptLibraryController;
use App\Http\Controllers\Backend\SEO\PageSeoController;
use App\Http\Controllers\Backend\Settings\SEOController;
use App\Http\Controllers\SubscriptionController;
use App\Models\FAQ;
use App\Models\SeoSetting;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\EmailVerificationPromptController;
use Illuminate\Support\Facades\Redirect;


Route::get('/', function () {
    $images = DalleImageGenerate::where('status', 'active')->inRandomOrder()->limit(16)->get();

    foreach ($images as $image) {
        $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
    }

    $seo = SeoSetting::find(1);
    $templates = Template::whereIn('id', [72, 73, 74, 18, 43, 21, 13, 3])->orderBy('id', 'desc')->get();
    $images_slider = DalleImageGenerate::where('resolution', '1024x1024')->where('status', 'active')->inRandomOrder()->get();

    foreach ($images_slider as $image) {
        $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
    }

    $faqs = FAQ::latest()->get();
    return view('frontend.index', compact('images', 'templates', 'images_slider', 'faqs', 'seo'));
})->name('home');


Route::get('/dashboard', function () {
    $user = Auth::user();
    $url = '';

    if ($user->role === 'admin') {
        $url = '/admin/dashboard';
    } elseif ($user->role === 'user') {
        $url = '/user/dashboard';
    }

    // Redirect to the appropriate dashboard route based on user role
    return Redirect::to($url);
})->middleware(['auth'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Admin Middleware
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');

    // AI Settings
    Route::prefix('settings/OpenAI')->group(function () {

        Route::get('/add', [AISettingsController::class, 'AIsettingsAdd'])->name('ai.settings.add');

        Route::post('/store', [AISettingsController::class, 'AIsettingsStore'])->name('ai.settings.store');
    });

    // SEO Settings
    Route::prefix('settings/SEO')->group(function () {

        Route::get('/add', [SEOController::class, 'SeosettingsAdd'])->name('seo.settings.add');

        Route::put('/store', [SEOController::class, 'SeosettingsStore'])->name('seo.settings.store');
    });

    // Site Settings
    Route::prefix('settings/site')->group(function () {

        Route::get('/add', [SiteSettingsController::class, 'SitesettingsAdd'])->name('site.settings.add');

        Route::post('/store', [SiteSettingsController::class, 'SitesettingsStore'])->name('site.settings.store');
    });

    // USER MANAGE
    Route::prefix('user')->group(function () {

        Route::get('/manage', [UserManageController::class, 'ManageUser'])->name('manage.user');

        Route::post('/update/status', [UserManageController::class, 'UpdateUserStatus'])->name('update.user.status');

        Route::put('/update/stats/{id}', [UserManageController::class, 'UpdateUserStats'])->name('update.user.stats');
    });

    // Templates
    Route::prefix('template')->group(function () {

        Route::get('/category/add', [TemplateController::class, 'TemplateCategoryAdd'])->name('template.category.add');

        Route::post('/category/store', [TemplateController::class, 'TemplateCategoryStore'])->name('template.category.store');

        Route::get('/add', [TemplateController::class, 'TemplateAdd'])->name('template.add');

        Route::post('store', [TemplateController::class, 'TemplateStore'])->name('template.store');
    });

    // Prompt Library
    Route::prefix('prompt')->group(function () {

        Route::get('/category/add', [PromptLibraryController::class, 'PromptCategoryAdd'])->name('prompt.category.add');

        Route::post('/category/store', [PromptLibraryController::class, 'PromptCategoryStore'])->name('prompt.category.store');

        Route::get('/add', [PromptLibraryController::class, 'PromptAdd'])->name('prompt.add');

        Route::post('store', [PromptLibraryController::class, 'PromptStore'])->name('prompt.store');
    });

    // Dalle Manage Image
    Route::get('/image/manage', [GenerateImagesController::class, 'DalleImageManageAdmin'])->name('manage.dalle.image.admin');

    Route::post('/update/image/status', [GenerateImagesController::class, 'UpdateStatus'])->name('update.status.dalle.image.admin');

    // PRIVACY POLICY
    Route::get('/privacy/policy', [HomeController::class, 'ManagePrivacyPolicy'])->name('manage.privacy.policy');

    Route::post('/privacy/policy/store', [HomeController::class, 'StorePrivacyPolicy'])->name('store.privacy.policy');

    Route::get('/privacy/policy/edit/{id}', [HomeController::class, 'EditPrivacyPolicy'])->name('edit.privacy.policy');

    Route::post('/privacy/policy/update', [HomeController::class, 'UpdatePrivacyPolicy'])->name('update.privacy.policy');

    Route::get('/privacy/policy/delete/{id}', [HomeController::class, 'DeletePrivacyPolicy'])->name('delete.privacy.policy');


    // TERMS & CONDITIONS
    Route::get('/terms/condition', [HomeController::class, 'ManageTermsCondition'])->name('manage.terms.condition');

    Route::post('/terms/condition/store', [HomeController::class, 'StoreTermsCondition'])->name('store.terms.condition');

    Route::get('/terms/condition/edit/{id}', [HomeController::class, 'EditTermsCondition'])->name('edit.terms.condition');

    Route::post('/terms/condition/update', [HomeController::class, 'UpdateTermsCondition'])->name('update.terms.condition');

    Route::get('/terms/condition/delete/{id}', [HomeController::class, 'DeleteTermsCondition'])->name('delete.terms.condition');
}); //End Admin Middleware


// User Middleware
Route::middleware(['auth', 'verified', 'role:user', 'check.status'])->group(function () {

    // User Routes
    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

    // Subscriptions
    Route::get('/all/subscription', [SubscriptionController::class, 'AllPackage'])->name('all.package');

    Route::get('/purchase/{pricingPlanId}', [SubscriptionController::class, 'Purchase'])->name('purchase.package');

    Route::post('/store/subscription/plan', [SubscriptionController::class, 'StoreSubscriptionPlan'])->name('store.subscription.plan');
}); //End User Middleware


// FrontEnd
//AI Image Gallery Page
Route::get('/ai/image/gallery', [HomeController::class, 'AIImageGallery'])->name('ai.image.gallery');

// Contact Us Page
Route::get('/contact-us', [HomeController::class, 'ContactUs'])->name('contact.us');

// Frontend Free Template Page
Route::get('/free/template', [HomeController::class, 'FrontendFreeTemplate'])->name('frontend.free.template');
Route::get('free/template/view/{slug}', [HomeController::class, 'TemplateView'])->name('frontend.free.template.view');
Route::post('free/template/generate', [HomeController::class, 'templategenerate'])->name('frontend.free.template.generate');

// Job Page
Route::get('/all-jobs', [HomeController::class, 'AllJobs'])->name('all.jobs');
Route::get('/job/detail/{slug}', [HomeController::class, 'detailsJob'])->name('job.detail');

// Privacy Policy Page
Route::get('/privacy-policy', [HomeController::class, 'PrivacyPolicy'])->name('privacy.policy');

// Terms And Conditions Page
Route::get('/terms-condition', [HomeController::class, 'TermsConditions'])->name('terms.condition');

// Newsletter Store for all users even without login
Route::post('/newsletter/store', [HomeController::class, 'NewsLetterStore'])->name('newsletter.store');




Route::middleware(['auth', 'check.status'])->group(function () {

    // Custom Templates
    Route::prefix('custom/template')->group(function () {

        Route::get('/category/add', [CustomTemplateController::class, 'CustomTemplateCategoryAdd'])->name('custom.template.category.add');

        Route::post('/category/store', [CustomTemplateController::class, 'CustomTemplateCategoryStore'])->name('custom.template.category.store');

        Route::get('/add', [CustomTemplateController::class, 'CustomTemplateAdd'])->name('custom.template.add');

        Route::post('store', [CustomTemplateController::class, 'CustomTemplateStore'])->name('custom.template.store');

        Route::get('/manage', [CustomTemplateController::class, 'CustomTemplateManage'])->name('custom.template.manage');

        Route::get('/view/{id}', [CustomTemplateController::class, 'CustomTemplateView'])->name('custom.template.view');

        Route::post('/generate', [CustomTemplateController::class, 'customtemplategenerate'])->name('custom.template.generate');
    });

    Route::prefix('chat')->middleware(['check.status'])->group(function () {

        // CHAT
        Route::get('/expert/add', [ExpertController::class, 'ExpertAdd'])->name('expert.add');
        Route::post('/expert/store', [ExpertController::class, 'ExpertStore'])->name('expert.store');

        // TEST CHAT
        Route::get('/expert/view', [ExpertController::class, 'index'])->name('chat');
        Route::get('/expert/{slug}', [ExpertController::class, 'ExpertChat'])->name('expert.chat');
        Route::post('/reply', [AIChatController::class, 'SendMessages']);
        
    });

    // adminDashboardChat
    Route::post('/chat/send', [AIChatController::class, 'send']);
    // Route::post('/clear-session', [AIChatController::class, 'clearSession'])->name('clear-session');



    Route::prefix('generate')->middleware(['check.status'])->group(function () {
        Route::get('/image/view', [GenerateImagesController::class, 'AIGenerateImageView'])->name('generate.image.view');
        Route::post('/image', [GenerateImagesController::class, 'generateImage'])->name('generate.image');
    });


    //Profile 
    Route::prefix('profile')->middleware(['check.status'])->group(function () {
        Route::get('/edit', [ProfileEditController::class, 'ProfileEdit'])->name('edit.profile');
        Route::post('/update', [ProfileEditController::class, 'ProfileUpdate'])->name('update.profile');
        Route::post('/update/photo', [ProfileEditController::class, 'ProfilePhotoUpdate'])->name('update.profile.photo');
    });



    //Fixed Templates 
    Route::get('template/manage', [TemplateController::class, 'TemplateManage'])->name('template.manage');

    Route::get('template/view/{slug}', [TemplateController::class, 'TemplateView'])->name('template.view');

    Route::post('template/generate', [TemplateController::class, 'templategenerate'])->name('template.generate');

    //Fixed Prompt Library 
    Route::get('prompt/manage', [PromptLibraryController::class, 'PromptManage'])->name('prompt.manage');

    Route::get('prompt/view/{slug}', [PromptLibraryController::class, 'PromptView'])->name('prompt.view');

    // Export
    Route::get('/export', [PromptLibraryController::class , 'Export'])->name('prompt.export');

    Route::post('/import', [PromptLibraryController::class , 'Import'])->name('import.store');




    // EID Card
    Route::get('eid/card', [GenerateImagesController::class, 'EidCard'])->name('eid.card');

    Route::post('eid/card/generate', [GenerateImagesController::class, 'EidCardGenerate'])->name('generate.eid.card');
}); //End Auth Middleware

// GOOGLE SOCIALITE
Route::get('google/login', [TemplateController::class, 'provider'])->name('google.login');
Route::get('google/callback', [TemplateController::class, 'callbackHandel'])->name('google.login.callback');


// GITHUB SOCIALITE
Route::get('github/login', [TemplateController::class, 'githubprovider'])->name('github.login');
Route::get('github/callback', [TemplateController::class, 'githubcallbackHandel'])->name('github.login.callback');

//Contact Us Send Mail
Route::post('/send-email', [HomeController::class, 'sendEmail'])->name('send.email');



// USER MANAGE
Route::prefix('user')->group(function () {

    Route::get('/manage', [UserManageController::class, 'ManageUser'])->name('manage.user');

    Route::post('/update/status', [UserManageController::class, 'UpdateUserStatus'])->name('update.user.status');

    Route::get('/details/{id}', [UserManageController::class, 'UserDetails'])->name('user.details');
});

Route::get('/inactive', function () {
    return view('admin.error.auth-404-basic');
})->name('inactive');


// Pricing
Route::get('/pricing-plan', [PricingController::class, 'ManagePricingPlan'])->name('manage.pricing');

Route::get('/add/pricing/plan', [PricingController::class, 'addPricingPlan'])->name('add.pricing.plan');

Route::post('/store/pricing', [PricingController::class, 'StorePricingPlan'])->name('store.pricing.plan');

Route::get('/pricing/{slug}', [PricingController::class, 'EditPricing'])->name('pricing.edit');

Route::put('/update/pricing-plans/{pricingPlan}', [PricingController::class, 'UpdatePricing'])->name('pricing.update');


// FAQ
Route::get('/faq', [FAQController::class, 'ManageFaq'])->name('manage.faq');

Route::get('/add/faq', [FAQController::class, 'AddFAQ'])->name('add.faq');

Route::post('/store/faq', [FAQController::class, 'StoreFAQ'])->name('store.faq');



// Change User's Password by ADMIN
Route::get('/admin/users/{user}/change-password', [AdminController::class, 'showChangePasswordForm'])
    ->name('admin.users.changePassword.view');


Route::put('/admin/users/{user}/change-password', [AdminController::class, 'changeUserPassword'])
    ->name('admin.users.updatePassword');

Route::delete('admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.delete');

// JOB Admin
Route::get('/add-job', [JobController::class, 'addJob'])
    ->name('add.job');
Route::post('/job/store', [JobController::class, 'storeJob'])->name('job.store');
Route::get('/manage-job', [JobController::class, 'manage'])->name('manage.job');
Route::get('/job/details/{slug}', [JobController::class, 'detailsJob'])->name('job.details');


// PAGE SEO Admin
Route::get('/add-seo', [PageSeoController::class, 'addPageSeo'])
    ->name('add.page.seo');
Route::post('/seo/page/store', [PageSeoController::class, 'storePageSeo'])->name('page.seo.store');

// Frontend Single Image
Route::post('/single/image', [GenerateImagesController::class, 'generateSingleImage'])->name('generate.single.image');
