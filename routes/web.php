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
use App\Http\Controllers\Backend\RatingController;
use App\Http\Controllers\Backend\SEO\PageSeoController;
use App\Http\Controllers\Backend\Settings\SEOController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\DynamicPageController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MainChat;
use App\Http\Controllers\RequestModuleFeedbackController;
use App\Http\Controllers\SubscriptionController;
use App\Models\FAQ;
use App\Models\PromptLibrary;
use App\Models\SectionDesign;
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
    $templates = Template::where('inFrontEnd', 'yes')->inRandomOrder()->limit(8)->get();
    $promptLibrary = PromptLibrary::where('inFrontEnd', 'yes')->inRandomOrder()->limit(8)->get();
    $images_slider = DalleImageGenerate::where('resolution', '1024x1024')->where('status', 'active')->inRandomOrder()->limit(14)->get();

    foreach ($images_slider as $image) {
        $image->image_url = config('filesystems.disks.azure.url') . config('filesystems.disks.azure.container') . '/' . $image->image . '?' . config('filesystems.disks.azure.sas_token');
    }

    $faqs = FAQ::latest()->get();

    // How it works SECTION Design
    $how_it_works = SectionDesign::where('section_name', 'how_it_works')->value('selected_design');
    $banner = SectionDesign::where('section_name', 'banner')->value('selected_design');
    $features = SectionDesign::where('section_name', 'features')->value('selected_design');
    $services = SectionDesign::where('section_name', 'services')->value('selected_design');
    $image_generate = SectionDesign::where('section_name', 'image_generate')->value('selected_design');
    $image_slider = SectionDesign::where('section_name', 'image_slider')->value('selected_design');

    return view('frontend.index', compact('images', 'templates', 'images_slider', 'faqs', 'seo', 'promptLibrary','how_it_works','banner', 'features', 'services', 'image_generate', 'image_slider'));
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
Route::middleware(['auth', 'roles:admin', 'check.blocked.ip'])->group(function () {

    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');

    // Add Admin
    Route::controller(AdminController::class)->group(function () {

        Route::get('/all/admin', 'AllAdmin')->name('all.admin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('store.admin');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin');
        Route::post('/update/admin/{id}', 'UpdateAdmin')->name('update.admin');
    });

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

        Route::get('/details/{id}', [UserManageController::class, 'UserDetails'])->name('user.details');

        // Block User
        Route::put('/{user}/block', [UserManageController::class, 'blockUser'])->name('admin.users.block');

        Route::get('/package/history', [UserManageController::class, 'packageHistory'])->name('admin.user.package.history');
     
        Route::get('/module/feedback/request', [UserManageController::class, 'ModuleFeedbackRequest'])->name('admin.user.feedback.request');

        Route::post('/update-feedback-request-status', [UserManageController::class, 'updateStatus'])->name('update.feedback-request-status');


    });

    // REFERRAL MANAGE
    Route::get('/referral/manage', [UserManageController::class, 'ManageReferral'])->name('manage.referral');


    // Templates
    Route::prefix('ai-content-creator')->group(function () {

        Route::get('/category/add', [TemplateController::class, 'TemplateCategoryAdd'])->name('template.category.add');

        Route::post('/category/store', [TemplateController::class, 'TemplateCategoryStore'])->name('template.category.store');

        Route::get('/category/edit/{id}', [TemplateController::class, 'TemplateCategoryEdit'])->name('template.category.edit');

        Route::post('/category/update', [TemplateController::class, 'TemplateCategoryUpdate'])->name('template.category.update');

        Route::get('/category/delete/{id}', [TemplateController::class, 'TemplateCategoryDelete'])->name('template.category.delete');

        Route::get('/add', [TemplateController::class, 'TemplateAdd'])->name('template.add');

        Route::post('store', [TemplateController::class, 'TemplateStore'])->name('template.store');

        Route::get('/edit/{slug}', [TemplateController::class, 'TemplateEdit'])->name('template.edit');

        Route::post('/update', [TemplateController::class, 'TemplateUpdate'])->name('template.update');
        Route::post('/seo/update', [TemplateController::class, 'TemplateSEOUpdate'])->name('template.seo.update');
        Route::get('/seo/fetch/{id}', [TemplateController::class, 'fetchTemplate'])->name('template.seo.fetch');


        Route::get('/select/design', [TemplateController::class, 'getDesign'])->name('getDesign');

        Route::post('/update-design', [TemplateController::class, 'updateDesign'])->name('user.update_design');
    });

    //  Permission
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/permission', 'AllPermission')->name('all.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');
    });

    // Roles 
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/roles', 'AllRoles')->name('all.roles');
        Route::get('/add/roles', 'AddRoles')->name('add.roles');
        Route::post('/store/roles', 'StoreRoles')->name('store.roles');
        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles');
        Route::post('/update/roles', 'UpdateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles');

        // RoleSetup
        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission');
        Route::post('/role/permission/store', 'RolePermissionStore')->name('role.permission.store');
        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission');
        Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles');
        Route::post('/admin/roles/update/{id}', 'AdminRolesUpdate')->name('admin.roles.update');
        Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')->name('admin.delete.roles');
    });

    // Prompt Library
    Route::prefix('prompt')->group(function () {

        Route::get('/category/add', [PromptLibraryController::class, 'PromptCategoryAdd'])->name('prompt.category.add');

        Route::post('/category/store', [PromptLibraryController::class, 'PromptCategoryStore'])->name('prompt.category.store');

        Route::get('/category/edit/{id}', [PromptLibraryController::class, 'PromptCategoryEdit'])->name('prompt.category.edit');

        Route::post('/category/update', [PromptLibraryController::class, 'PromptCategoryUpdate'])->name('prompt.category.update');

        Route::get('/category/delete/{id}', [PromptLibraryController::class, 'PromptCategoryDelete'])->name('prompt.category.delete');

        Route::get('/subcategory/add', [PromptLibraryController::class, 'PromptSubCategoryAdd'])->name('prompt.subcategory.add');

        Route::post('/subcategory/store', [PromptLibraryController::class, 'PromptSubCategoryStore'])->name('prompt.subcategory.store');

        Route::get('/subcategory/edit/{id}', [PromptLibraryController::class, 'PromptSubCategoryEdit'])->name('prompt.subcategory.edit');

        Route::post('/subcategory/update', [PromptLibraryController::class, 'PromptSubCategoryUpdate'])->name('prompt.subcategory.update');

        Route::get('/subcategory/delete/{id}', [PromptLibraryController::class, 'PromptSubCategoryDelete'])->name('prompt.subcategory.delete');

        Route::get('/add', [PromptLibraryController::class, 'PromptAdd'])->name('prompt.add');

        Route::post('store', [PromptLibraryController::class, 'PromptStore'])->name('prompt.store');

        Route::get('/edit/{id}', [PromptLibraryController::class, 'PromptEdit'])->name('prompt.edit');

        Route::post('/update', [PromptLibraryController::class, 'PromptUpdate'])->name('prompt.update');

        Route::post('/seo/update', [PromptLibraryController::class, 'PromptSEOUpdate'])->name('prompt.seo.update');

        Route::get('/delete/{id}', [PromptLibraryController::class, 'PromptDelete'])->name('prompt.delete');

        // Route for deleting an example
        Route::delete('/example/{example}', [PromptLibraryController::class, 'delete'])->name('prompt.example.delete');

        // Route for updating an example (if not defined already)
        Route::put('/example/{id}',  [PromptLibraryController::class, 'update'])->name('prompt.example.update');
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


    // Pricing Plans
    Route::get('/pricing-plan', [PricingController::class, 'ManagePricingPlan'])->name('manage.pricing');

    Route::delete('/pricing/{slug}', [PricingController::class, 'destroy'])->name('pricing.destroy');

    Route::get('/add/pricing/plan', [PricingController::class, 'addPricingPlan'])->name('add.pricing.plan');

    Route::post('/store/pricing', [PricingController::class, 'StorePricingPlan'])->name('store.pricing.plan');

    Route::get('/pricing/{slug}', [PricingController::class, 'EditPricing'])->name('pricing.edit');

    Route::put('/update/pricing-plans/{pricingPlan}', [PricingController::class, 'UpdatePricing'])->name('pricing.update');

    // EDUCATION        
    Route::prefix('education')->group(function () {

        Route::get('/add/class/subject', [EducationController::class, 'manageGradeSubject'])->name('manage.grade.subject');

        Route::post('/store/grade/class', [EducationController::class, 'StoreGradeClass'])->name('store.grade.class');

    });

    // FAQ
    Route::get('/faq', [FAQController::class, 'ManageFaq'])->name('manage.faq');
    // Route::get('/add/faq', [FAQController::class, 'AddFAQ'])->name('add.faq');
    Route::post('/store/faq', [FAQController::class, 'StoreFAQ'])->name('store.faq');
    // routes/web.php
    Route::put('faq/update/{id}', [FAQController::class, 'update'])->name('faq.update');
    // routes/web.php
    Route::delete('faq/destroy/{id}', [FAQController::class, 'destroy'])->name('faq.destroy');

    // JOB Admin
    Route::get('/add-job', [JobController::class, 'addJob'])->name('add.job');
    Route::post('/job/store', [JobController::class, 'storeJob'])->name('job.store');
    Route::get('/manage-job', [JobController::class, 'manage'])->name('manage.job');
    Route::get('/manage-job/applications', [JobController::class, 'manageJobApplication'])->name('manage.job.applications');
    Route::get('/download-cv/{id}', [JobController::class, 'downloadCV'])->name('download.cv');
    Route::get('/job/details/{slug}', [JobController::class, 'detailsJob'])->name('job.details');

    // DYNAMIC PAGE
    Route::resource('dynamic-pages', DynamicPageController::class);

    // PAGE SEO Admin
    Route::get('/add-seo', [PageSeoController::class, 'addPageSeo'])
        ->name('add.page.seo');
    Route::post('/seo/page/store', [PageSeoController::class, 'storePageSeo'])->name('page.seo.store');

    // Change User's Password by ADMIN
    Route::get('/admin/users/{user}/change-password', [AdminController::class, 'showChangePasswordForm'])
        ->name('admin.users.changePassword.view');

    Route::put('/admin/users/{user}/change-password', [AdminController::class, 'changeUserPassword'])
        ->name('admin.users.updatePassword');

    // RESEND EMAIL VERIFICATION
    Route::post('/users/{user}/send-verification-email', [UserController::class, 'sendVerificationEmail'])->name('user.send-verification-email');

    // Delete user from admin manage user table
    Route::delete('admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.delete');

    // SEND EMAIL
    Route::get('/send/email', [UserManageController::class, 'sendEmailForm'])->name('send.email.form');
    Route::post('/send-emails', [UserManageController::class, 'sendEmail'])->name('emails.send');

}); //End Admin Middleware


// User Middleware
Route::middleware(['auth', 'verified', 'roles:user', 'check.status', 'check.blocked.ip'])->group(function () {
    
    // Feedback Request
    Route::post('/module/feedback', [RequestModuleFeedbackController::class, 'templateFeedback'])->name('template.module.feedback');

    // User Routes
    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

    // Template Rating
    Route::post('/rate-template', [RatingController::class, 'store'])->name('rate.template');

    // Subscriptions
    Route::get('/all/subscription', [SubscriptionController::class, 'AllPackage'])->name('all.package');

    Route::get('/purchase/{pricingPlanId}', [SubscriptionController::class, 'Purchase'])->name('purchase.package');

    Route::post('/store/subscription/plan', [SubscriptionController::class, 'StoreSubscriptionPlan'])->name('store.subscription.plan');

    // User Prompt Library
    Route::get('/user/prompt/library', [PromptLibraryController::class, 'UserPromptManage'])->name('user.prompt.library');

    // Education
    Route::prefix('education')->group(function () {

        Route::get('/add/content', [EducationController::class, 'educationForm'])->name('education.form');
       
        Route::post('/content', [EducationController::class, 'educationContent'])->name('education.content');
        
        Route::get('/get-subjects/{gradeId}', [EducationController::class, 'getSubjects']);

        Route::get('/get-content', [EducationController::class, 'getUserContents'])->name('user_generated_education_content');

        Route::post('/get-contents/subject', [EducationController::class, 'getContentsBySubject'])->name('education.getContentsBySubject');

        Route::post('/get-content-by-id', [EducationController::class, 'getContentById'])->name('education.getContentById');

        Route::delete('/deleteContent/{id}', [EducationController::class, 'deleteContent'])->name('education.deleteContent');

        Route::get('/content/{id}/download', [EducationController::class, 'downloadPDF'])->name('education.content.download');

        Route::post('/content/{id}/complete', [EducationController::class, 'markAsComplete'])->name('content.mark.complete');

        Route::get('/content/{id}/edit', [EducationController::class, 'edit'])->name('education.content.edit');

        Route::post('/content/update', [EducationController::class, 'update'])->name('education.content.update');

    });


}); //End User Middleware

Route::post('/generate-images', [EducationController::class, 'generateImages']);



Route::middleware(['auth', 'check.status'])->group(function () {

    // Custom Templates
    Route::prefix('custom/ai-content-creator')->group(function () {

        Route::get('/category/add', [CustomTemplateController::class, 'CustomTemplateCategoryAdd'])->name('custom.template.category.add');

        Route::post('/category/store', [CustomTemplateController::class, 'CustomTemplateCategoryStore'])->name('custom.template.category.store');

        Route::get('/add', [CustomTemplateController::class, 'CustomTemplateAdd'])->name('custom.template.add');

        Route::post('store', [CustomTemplateController::class, 'CustomTemplateStore'])->name('custom.template.store');

        Route::get('/manage', [CustomTemplateController::class, 'CustomTemplateManage'])->name('custom.template.manage');

        Route::get('/view/{id}', [CustomTemplateController::class, 'CustomTemplateView'])->name('custom.template.view');

        Route::post('/generate', [CustomTemplateController::class, 'customtemplategenerate'])->name('custom.template.generate');
    });


     // Global Select Model
     Route::post('/select-model', [UserController::class, 'selectModel'])->name('select-model');


    // Main Chat
    // Custom Templates
    Route::prefix('main')->group(function () {

        // NEW SESSION
        Route::post('/new-session', [MainChat::class, 'MainNewSession']);

        Route::post('/chat/send', [MainChat::class, 'send']);

        // Title Edit
        Route::post('/session/edit', [MainChat::class, 'TitleEdit']);

        Route::post('/session/delete', [MainChat::class, 'delete']);
    });

    Route::get('/chat', [MainChat::class, 'MainChatForm'])->name('main.chat.form');




    // Like Image
    Route::post('/like', [GenerateImagesController::class, 'toggleLike'])->name('image.like');

    // Favorite Image
    Route::post('/favorite', [GenerateImagesController::class, 'toggleFavorite'])->name('image.favorite');

    // Dalle Manage Image
    Route::get('/favorite/image/manage', [GenerateImagesController::class, 'ManageFavoriteImage'])->name('manage.favourite.image');

    // Image to Image
    Route::post('/generate-image-variation', [GenerateImagesController::class, 'generateImageVariation']);

    Route::prefix('chat')->middleware(['check.status'])->group(function () {

        // CHAT
        Route::get('/expert/add', [ExpertController::class, 'ExpertAdd'])->name('expert.add');
        Route::post('/expert/store', [ExpertController::class, 'ExpertStore'])->name('expert.store');

        Route::get('/expert/edit/{id}', [ExpertController::class, 'ExpertEdit'])->name('expert.edit');
        Route::post('/expert/update/{id}', [ExpertController::class, 'ExpertUpdate'])->name('expert.update');
        Route::get('/expert/delete/{id}', [ExpertController::class, 'ExpertDelete'])->name('expert.delete');

        // TEST CHAT
        Route::get('/expert/view', [ExpertController::class, 'index'])->name('chat');
        Route::get('/expert/{slug}', [ExpertController::class, 'ExpertChat'])->name('expert.chat');
        Route::post('/reply', [ExpertController::class, 'SendMessages']);

        // GET MESSAGES TEST
        Route::get('/sessions/{id}/messages', [AIChatController::class, 'getSessionMessages']);

        // CHECK SESSION
        Route::get('/check-session', [AIChatController::class, 'checkUserSession']);


        // NEW SESSION
        Route::post('/new-session', [AIChatController::class, 'newSession']);
    });

    // adminDashboardChat
    Route::post('/chat/send', [AIChatController::class, 'send']);

    // Calender
    Route::get('/calender', [FAQController::class, 'calender'])->name('calender');

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
    Route::get('ai-content-creator/manage', [TemplateController::class, 'TemplateManage'])->name('template.manage');

    Route::get('ai-content-creator/view/{slug}', [TemplateController::class, 'TemplateView'])->name('template.view');

    Route::post('ai-content-creator/generate', [TemplateController::class, 'templategenerate'])->name('template.generate');

    //Fixed Prompt Library 
    Route::get('prompt/manage', [PromptLibraryController::class, 'PromptManage'])->name('prompt.manage');

    Route::get('prompt/view/{slug}', [PromptLibraryController::class, 'PromptView'])->name('prompt.view');

    // Category Filter Prompt Library
    Route::get('prompt/category/{id}', [PromptLibraryController::class, 'PromptCatgeoryView'])->name('category.prompt.library.view');
    
    // Sub Category Filter Prompt Library
    Route::get('prompt/subcategory/{id}', [PromptLibraryController::class, 'PromptSubCatgeoryView'])->name('sub.category.prompt.library.view');

    Route::post('/prompt-examples/{promptLibrary}', [PromptLibraryController::class, 'PromptExampleStore'])->name('prompt_examples.store');

    Route::put('/prompt-examples/{promptExample}', [PromptLibraryController::class, 'updatePromptExample'])->name('prompt_examples.update');



    // Export Prompt
    Route::get('/export', [PromptLibraryController::class, 'Export'])->name('prompt.export');
    // Import Prompt
    Route::post('/import', [PromptLibraryController::class, 'Import'])->name('import.store');

    // User Export
    Route::get('/all/user/export', [UserController::class, 'export'])->name('user.export');
    Route::get('/all/user/export1', [UserController::class, 'export1'])->name('user.export1');

    // EID Card
    Route::get('eid/card', [GenerateImagesController::class, 'EidCard'])->name('eid.card');

    Route::post('eid/card/generate', [GenerateImagesController::class, 'EidCardGenerate'])->name('generate.eid.card');


    // GET SUB CATEGORY
    Route::get('/prompt/subcategories/{category_id}', [PromptLibraryController::class, 'getPromptSubCategory']);

    Route::get('/prompt/filter-prompts', [PromptLibraryController::class, 'filterPrompts']);
}); //End Auth Middleware

// FrontEnd
//AI Image Gallery Page
Route::get('/ai/image/gallery', [HomeController::class, 'AIImageGallery'])->name('ai.image.gallery');

// Contact Us Page
Route::get('/contact-us', [HomeController::class, 'ContactUs'])->name('contact.us');

// Frontend Free Template Page
Route::get('/free/ai-content-creator', [HomeController::class, 'FrontendFreeTemplate'])->name('frontend.free.template');
Route::get('/free/ai-content-creator/view/{slug}', [HomeController::class, 'TemplateView'])->name('frontend.free.template.view');
Route::post('/free/ai-content-creator/generate', [HomeController::class, 'templategenerate'])->name('frontend.free.template.generate');

// Frontend Free Prompt Library Page
Route::get('/free/prompt-library', [HomeController::class, 'FrontendFreePromptLibrary'])->name('frontend.free.prompt.library');
// Route::get('/free/template/view/{slug}', [HomeController::class, 'TemplateView'])->name('frontend.free.template.view');
// Route::post('/free/template/generate', [HomeController::class, 'templategenerate'])->name('frontend.free.template.generate');

// Job Page Frontend
Route::get('/all-jobs', [HomeController::class, 'AllJobs'])->name('all.jobs');
// Route::get('/job/detail/{slug}', [HomeController::class, 'detailsJob'])->name('job.detail');
Route::get('/jobs/{id}', [HomeController::class, 'JobDetails'])->name('jobs.details.frontend');


// Privacy Policy Page
Route::get('/privacy-policy', [HomeController::class, 'PrivacyPolicy'])->name('privacy.policy');

// Terms And Conditions Page
Route::get('/terms-condition', [HomeController::class, 'TermsConditions'])->name('terms.condition');

// Newsletter Store for all users even without login
Route::post('/newsletter/store', [HomeController::class, 'NewsLetterStore'])->name('newsletter.store');
Route::get('/newsletter/manage', [HomeController::class, 'NewsLetterManage'])->name('newsletter.manage');

// GOOGLE SOCIALITE
Route::get('google/login', [TemplateController::class, 'provider'])->name('google.login');
Route::get('google/callback', [TemplateController::class, 'callbackHandel'])->name('google.login.callback');


// GITHUB SOCIALITE
Route::get('github/login', [TemplateController::class, 'githubprovider'])->name('github.login');
Route::get('github/callback', [TemplateController::class, 'githubcallbackHandel'])->name('github.login.callback');

//Contact Us Send Mail
Route::post('/send-email', [HomeController::class, 'sendEmail'])->name('send.email');


Route::get('/inactive', function () {
    return view('admin.error.auth-404-basic');
})->name('inactive');

Route::get('/ip-blocked', function () {
    return view('admin.error.ip_block_error_page');
})->name('blocked.page');


// Frontend Job Apply
Route::post('/submit-form', [JobController::class, 'JobApplicationStore'])->name('job.apply');

// Frontend Single Image
Route::post('/single/image', [GenerateImagesController::class, 'generateSingleImage'])->name('generate.single.image');

Route::get('prompt/details/{slug}', [PromptLibraryController::class, 'PromptFrontendView'])->name('prompt.frontend.view');

 // ASK AI PROMPT LIBRARY
 Route::post('/ask/ai/send', [PromptLibraryController::class, 'AskAiPromptLibrary'])->name('ask.ai.prompt');


// Tour Status
Route::post('/save-seen-tour-steps', [UserController::class, 'saveSeenTourSteps']);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy']);


});


