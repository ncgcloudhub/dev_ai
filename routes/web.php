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
use App\Http\Controllers\Backend\AI\AIContentCreatorController;
use App\Http\Controllers\Backend\AI\ExpertController;
use App\Http\Controllers\Backend\AI\GenerateImagesController;
use App\Http\Controllers\Backend\AI\StableDifussionController;
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
    $image_gallery = SectionDesign::where('section_name', 'image_gallery')->value('selected_design');
    $content_creator = SectionDesign::where('section_name', 'content_creator')->value('selected_design');
    $prompt_library = SectionDesign::where('section_name', 'prompt_library')->value('selected_design');

    return view('frontend.index', compact('images', 'templates', 'images_slider', 'faqs', 'seo', 'promptLibrary','how_it_works','banner', 'features', 'services', 'image_generate', 'image_slider', 'image_gallery', 'content_creator', 'prompt_library'));
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

        Route::get('/all/admin', 'AllAdmin')->name('all.admin')->middleware('admin.permission:manageUser&Admin.manageAdmin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('store.admin');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin')->middleware('admin.permission:manageUser&Admin.manageAdmin.edit');
        Route::post('/update/admin/{id}', 'UpdateAdmin')->name('update.admin');
    });

    // AI Settings
    Route::prefix('settings/OpenAI')->group(function () {

        Route::get('/add', [AISettingsController::class, 'AIsettingsAdd'])->name('ai.settings.add')->middleware('admin.permission:settings.AISettings');

        Route::post('/store', [AISettingsController::class, 'AIsettingsStore'])->name('ai.settings.store');
    });

    // SEO Settings
    Route::prefix('settings/SEO')->group(function () {

        Route::get('/add', [SEOController::class, 'SeosettingsAdd'])->name('seo.settings.add')->middleware('admin.permission:settings.SEOSettings');

        Route::put('/store', [SEOController::class, 'SeosettingsStore'])->name('seo.settings.store');
    });

    // Site Settings
    Route::prefix('settings/site')->group(function () {

        Route::get('/add', [SiteSettingsController::class, 'SitesettingsAdd'])->name('site.settings.add')->middleware('admin.permission:settings.siteSettings');

        Route::post('/store', [SiteSettingsController::class, 'SitesettingsStore'])->name('site.settings.store');
    });

    // USER MANAGE
    Route::prefix('user')->group(function () {

        Route::get('/manage', [UserManageController::class, 'ManageUser'])->name('manage.user')->middleware('admin.permission:manageUser&Admin.manageUser');

        Route::post('/update/status', [UserManageController::class, 'UpdateUserStatus'])->name('update.user.status');

        Route::put('/update/stats/{id}', [UserManageController::class, 'UpdateUserStats'])->name('update.user.stats');

        Route::get('/details/{id}', [UserManageController::class, 'UserDetails'])->name('user.details');

        // Block User
        Route::put('/{user}/block', [UserManageController::class, 'blockUser'])->name('admin.users.block');
        Route::post('/users/bulk-block', [UserManageController::class, 'bulkBlock'])->name('admin.users.bulkBlock')->middleware('admin.permission:manageUser&Admin.manageUser.block');
        // Route for bulk email verification
        Route::post('/bulk-verify-email', [UserManageController::class, 'bulkVerifyEmail'])->name('admin.users.bulkVerifyEmail')->middleware('admin.permission:manageUser&Admin.manageUser.sendVerificationMail');

        Route::get('/manage/block', [UserManageController::class, 'manageBlock'])->name('manage.block')->middleware('admin.permission:settings.countryBlock');
        Route::post('/block/store', [UserManageController::class, 'storeBlock'])->name('country.block.store');
        Route::get('/countries/block/edit/{id}', [UserManageController::class, 'editCountry'])->name('block.countries.edit')->middleware('admin.permission:settings.countryBlock.edit');
        Route::post('/countries/block/update', [UserManageController::class, 'updateCountry'])->name('block.countries.update');
        Route::delete('/countries/{id}', [UserManageController::class, 'countryDestroy'])->name('block.countries.delete')->middleware('admin.permission:settings.countryBlock.delete');


        Route::post('/users/bulk-status-change', [UserManageController::class, 'bulkStatusChange'])->name('admin.users.bulkStatusChange')->middleware('admin.permission:manageUser&Admin.manageUser.statusChange');

        Route::get('/package/history', [UserManageController::class, 'packageHistory'])->name('admin.user.package.history')->middleware('admin.permission:manageUser&Admin.manageuserPackage');
     
        Route::get('/module/feedback/request', [UserManageController::class, 'ModuleFeedbackRequest'])->name('admin.user.feedback.request')->middleware('admin.permission:manageUser&Admin.manageFeedback');

        Route::post('/update-feedback-request-status', [UserManageController::class, 'updateStatus'])->name('update.feedback-request-status');


    });

    // REFERRAL MANAGE
    Route::get('/referral/manage', [UserManageController::class, 'ManageReferral'])->name('manage.referral')->middleware('admin.permission:manageRefferal.menu');


    // Templates
    Route::prefix('ai-content-creator')->group(function () {

        Route::get('/category/add', [AIContentCreatorController::class, 'AIContentCreatorCategoryAdd'])->name('aicontentcreator.category.add')->middleware('admin.permission:aiContentCreator.category');

        Route::post('/category/store', [AIContentCreatorController::class, 'AIContentCreatorCategoryStore'])->name('aicontentcreator.category.store');

        Route::get('/category/edit/{id}', [AIContentCreatorController::class, 'AIContentCreatorCategoryEdit'])->name('aicontentcreator.category.edit')->middleware('admin.permission:aiContentCreator.category.edit');

        Route::post('/category/update', [AIContentCreatorController::class, 'AIContentCreatorCategoryUpdate'])->name('aicontentcreator.category.update');

        Route::get('/category/delete/{id}', [AIContentCreatorController::class, 'AIContentCreatorCategoryDelete'])->name('aicontentcreator.category.delete')->middleware('admin.permission:aiContentCreator.category.delete');

        Route::get('/add', [AIContentCreatorController::class, 'AIContentCreatorAdd'])->name('aicontentcreator.add')->middleware('admin.permission:aiContentCreator.add');

        Route::post('store', [AIContentCreatorController::class, 'AIContentCreatorStore'])->name('aicontentcreator.store');

        Route::get('/edit/{slug}', [AIContentCreatorController::class, 'AIContentCreatorEdit'])->name('aicontentcreator.edit')->middleware('admin.permission:aiContentCreator.edit');
        
        Route::get('/delete/{id}', [AIContentCreatorController::class, 'AIContentCreatorDelete'])->name('aicontentcreator.delete')->middleware('admin.permission:aiContentCreator.delete');

        Route::post('/update', [AIContentCreatorController::class, 'AIContentCreatorUpdate'])->name('aicontentcreator.update');

        Route::post('/seo/update', [AIContentCreatorController::class, 'AIContentCreatorSEOUpdate'])->name('aicontentcreator.seo.update');

        Route::get('/seo/fetch/{id}', [AIContentCreatorController::class, 'fetchTemplate'])->name('aicontentcreator.seo.fetch');

        Route::get('/select/design', [AIContentCreatorController::class, 'getDesign'])->name('getDesign')->middleware('admin.permission:settings.frontEndDesign');

        Route::post('/update-design', [AIContentCreatorController::class, 'updateDesign'])->name('user.update_design');
    });

    //  Permission
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/permission', 'AllPermission')->name('all.permission')->middleware('admin.permission:rolePermission.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission');
    });

    // Roles 
    Route::controller(RoleController::class)->group(function () {

        Route::get('/all/roles', 'AllRoles')->name('all.roles')->middleware('admin.permission:rolePermission.roles');
        Route::get('/add/roles', 'AddRoles')->name('add.roles');
        Route::post('/store/roles', 'StoreRoles')->name('store.roles');
        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles');
        Route::post('/update/roles', 'UpdateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles');

        // RoleSetup
        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission')->middleware('admin.permission:rolePermission.roleInPermission');
        Route::post('/role/permission/store', 'RolePermissionStore')->name('role.permission.store');
        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission')->middleware('admin.permission:rolePermission.roleInPermissionManage');
        Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles');
        Route::post('/admin/roles/update/{id}', 'AdminRolesUpdate')->name('admin.roles.update');
        Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')->name('admin.delete.roles');
        Route::get('/import/permission', 'ImportPermission')->name('import.permission');
        Route::get('/export/permission', 'ExportPermission')->name('export.permission');
        Route::post('/import/store', 'ImportStore')->name('import.store.permission');
    });

    // Prompt Library
    Route::prefix('prompt')->group(function () {

        Route::get('/category/add', [PromptLibraryController::class, 'PromptCategoryAdd'])->name('prompt.category.add')->middleware('admin.permission:promptLibrary.category');

        Route::post('/category/store', [PromptLibraryController::class, 'PromptCategoryStore'])->name('prompt.category.store');

        Route::get('/category/edit/{id}', [PromptLibraryController::class, 'PromptCategoryEdit'])->name('prompt.category.edit')->middleware('admin.permission:promptLibrary.category.edit');

        Route::post('/category/update', [PromptLibraryController::class, 'PromptCategoryUpdate'])->name('prompt.category.update');

        Route::get('/category/delete/{id}', [PromptLibraryController::class, 'PromptCategoryDelete'])->name('prompt.category.delete')->middleware('admin.permission:promptLibrary.category.delete');

        Route::get('/subcategory/add', [PromptLibraryController::class, 'PromptSubCategoryAdd'])->name('prompt.subcategory.add')->middleware('admin.permission:promptLibrary.subcategory');

        Route::post('/subcategory/store', [PromptLibraryController::class, 'PromptSubCategoryStore'])->name('prompt.subcategory.store');

        Route::get('/subcategory/edit/{id}', [PromptLibraryController::class, 'PromptSubCategoryEdit'])->name('prompt.subcategory.edit')->middleware('admin.permission:promptLibrary.subcategory.edit');

        Route::post('/subcategory/update', [PromptLibraryController::class, 'PromptSubCategoryUpdate'])->name('prompt.subcategory.update');

        Route::get('/subcategory/delete/{id}', [PromptLibraryController::class, 'PromptSubCategoryDelete'])->name('prompt.subcategory.delete')->middleware('admin.permission:promptLibrary.subcategory.delete');

        Route::get('/add', [PromptLibraryController::class, 'PromptAdd'])->name('prompt.add')->middleware('admin.permission:promptLibrary.add');

        Route::post('store', [PromptLibraryController::class, 'PromptStore'])->name('prompt.store');

        Route::get('/edit/{id}', [PromptLibraryController::class, 'PromptEdit'])->name('prompt.edit')->middleware('admin.permission:promptLibrary.edit');

        Route::post('/update', [PromptLibraryController::class, 'PromptUpdate'])->name('prompt.update');

        Route::post('/seo/update', [PromptLibraryController::class, 'PromptSEOUpdate'])->name('prompt.seo.update');

        Route::get('/delete/{id}', [PromptLibraryController::class, 'PromptDelete'])->name('prompt.delete')->middleware('admin.permission:promptLibrary.delete');

        // Route for deleting an example
        Route::delete('/example/{example}', [PromptLibraryController::class, 'delete'])->name('prompt.example.delete');

        // Route for updating an example (if not defined already)
        Route::put('/example/{id}',  [PromptLibraryController::class, 'update'])->name('prompt.example.update');

        Route::get('/seo/fetch/{id}', [PromptLibraryController::class, 'fetchPrompt'])->name('promptLibrary.seo.fetch');

    });


    // Dalle Manage Image
    Route::get('/image/manage', [GenerateImagesController::class, 'DalleImageManageAdmin'])->name('manage.dalle.image.admin')->middleware('admin.permission:cleverImageCreator.manage');

    Route::post('/update/image/status', [GenerateImagesController::class, 'UpdateStatus'])->name('update.status.dalle.image.admin');


    // PRIVACY POLICY
    Route::get('/privacy/policy', [HomeController::class, 'ManagePrivacyPolicy'])->name('manage.privacy.policy')->middleware('admin.permission:settings.privacyPolicy');

    Route::post('/privacy/policy/store', [HomeController::class, 'StorePrivacyPolicy'])->name('store.privacy.policy');

    Route::get('/privacy/policy/edit/{id}', [HomeController::class, 'EditPrivacyPolicy'])->name('edit.privacy.policy')->middleware('admin.permission:settings.privacyPolicy.edit');

    Route::post('/privacy/policy/update', [HomeController::class, 'UpdatePrivacyPolicy'])->name('update.privacy.policy');

    Route::get('/privacy/policy/delete/{id}', [HomeController::class, 'DeletePrivacyPolicy'])->name('delete.privacy.policy')->middleware('admin.permission:settings.privacyPolicy.delete');

    // Magic Ball (Jokes) | Middleware Must be added
    Route::get('/jokes', [HomeController::class, 'MagicBallJokes'])->name('magic.ball.jokes');
    Route::post('/store/jokes', [HomeController::class, 'MagicBallJokeStore'])->name('jokes.store');


    // TERMS & CONDITIONS
    Route::get('/terms/condition', [HomeController::class, 'ManageTermsCondition'])->name('manage.terms.condition')->middleware('admin.permission:settings.termsAndConditions');

    Route::post('/terms/condition/store', [HomeController::class, 'StoreTermsCondition'])->name('store.terms.condition');

    Route::get('/terms/condition/edit/{id}', [HomeController::class, 'EditTermsCondition'])->name('edit.terms.condition')->middleware('admin.permission:settings.termsAndConditions.edit');

    Route::post('/terms/condition/update', [HomeController::class, 'UpdateTermsCondition'])->name('update.terms.condition');

    Route::get('/terms/condition/delete/{id}', [HomeController::class, 'DeleteTermsCondition'])->name('delete.terms.condition')->middleware('admin.permission:settings.termsAndConditions.delete');


    // Pricing Plans
    Route::get('/pricing-plan', [PricingController::class, 'ManagePricingPlan'])->name('manage.pricing')->middleware('admin.permission:settings.pricing');

    Route::delete('/pricing/{slug}', [PricingController::class, 'destroy'])->name('pricing.destroy')->middleware('admin.permission:settings.pricing.delete');

    Route::get('/add/pricing/plan', [PricingController::class, 'addPricingPlan'])->name('add.pricing.plan');

    Route::post('/store/pricing', [PricingController::class, 'StorePricingPlan'])->name('store.pricing.plan');

    Route::get('/pricing/{slug}', [PricingController::class, 'EditPricing'])->name('pricing.edit')->middleware('admin.permission:settings.pricing.edit');

    Route::put('/update/pricing-plans/{pricingPlan}', [PricingController::class, 'UpdatePricing'])->name('pricing.update');

    // EDUCATION        
    Route::prefix('education')->group(function () {

        Route::get('/add/class/subject', [EducationController::class, 'manageGradeSubject'])->name('manage.grade.subject')->middleware('admin.permission:education.manageGradeSubject');

        Route::post('/store/grade/class', [EducationController::class, 'StoreGradeClass'])->name('store.grade.class');

        Route::post('update-grade/{id}', [EducationController::class, 'updateGrade'])->name('update.grade');

        Route::post('update-subject/{id}', [EducationController::class, 'updateSubject'])->name('update.subject');

        Route::post('delete-grade/{id}', [EducationController::class, 'deleteGrade'])->name('delete.grade')->middleware('admin.permission:education.manageGradeSubject.gradeDelete');

        Route::post('delete-subject/{id}', [EducationController::class, 'deleteSubject'])->name('delete.subject')->middleware('admin.permission:education.manageGradeSubject.subjectDelete');
        
        Route::get('/add/tools', [EducationController::class, 'AddTools'])->name('add.education.tools')->middleware('admin.permission:education.manageTools.add');

        Route::post('/store/tools', [EducationController::class, 'StoreTools'])->name('store.education.tools');

        // Education Tools Category

        Route::get('/tools/category/add', [EducationController::class, 'EducationToolsCategoryAdd'])->name('education.tools.category.add');

        Route::post('/tools/category/store', [EducationController::class, 'EducationToolsCategoryStore'])->name('education.tools.category.store');

        Route::get('/tools/category/edit/{id}', [EducationController::class, 'EducationToolsCategoryEdit'])->name('education.tools.category.edit');

        Route::post('/tools/category/update', [EducationController::class, 'EducationToolsCategoryUpdate'])->name('education.tools.category.update');

        Route::get('/tools/category/delete/{id}', [EducationController::class, 'EducationToolsCategoryDelete'])->name('education.tools.category.delete');


        //Education Tools category End

        // Route to show the form for editing a specific tool (edit)
        Route::get('/tools/{id}/edit', [EducationController::class, 'editTools'])->name('tools.edit')->middleware('admin.permission:education.manageTools.edit');

        // Route to update a specific tool (update)
        Route::put('/tools/{id}', [EducationController::class, 'updateTools'])->name('tools.update');

        // Route to delete a specific tool (destroy)
        Route::delete('/tools/{id}', [EducationController::class, 'destroyTools'])->name('tools.destroy')->middleware('admin.permission:education.manageTools.delete');

    });

    // FAQ
    Route::get('/faq', [FAQController::class, 'ManageFaq'])->name('manage.faq')->middleware('admin.permission:settings.FAQ');
    // Route::get('/add/faq', [FAQController::class, 'AddFAQ'])->name('add.faq');
    Route::post('/store/faq', [FAQController::class, 'StoreFAQ'])->name('store.faq');
    // routes/web.php
    Route::put('faq/update/{id}', [FAQController::class, 'update'])->name('faq.update');
    // routes/web.php
    Route::delete('faq/destroy/{id}', [FAQController::class, 'destroy'])->name('faq.destroy')->middleware('admin.permission:settings.FAQ.delete');

    // JOB Admin
    Route::get('/add-job', [JobController::class, 'addJob'])->name('add.job')->middleware('admin.permission:jobs.addJob');
    Route::post('/job/store', [JobController::class, 'storeJob'])->name('job.store');
    Route::get('/manage-job', [JobController::class, 'manage'])->name('manage.job')->middleware('admin.permission:jobs.manageJobs');
    Route::get('/manage-job/applications', [JobController::class, 'manageJobApplication'])->name('manage.job.applications')->middleware('admin.permission:jobs.manageJobApplication');
    Route::get('/download-cv/{id}', [JobController::class, 'downloadCV'])->name('download.cv');
    Route::get('/job/details/{slug}', [JobController::class, 'detailsJob'])->name('job.details');

    // DYNAMIC PAGE
    Route::resource('dynamic-pages', DynamicPageController::class)->except(['show']);
    Route::post('/dynamic-pages/seo/generate', [DynamicPageController::class, 'generateSeoContent'])
    ->name('dynamic-pages.seo.generate');
    Route::get('/dynamic-pages/check-route', [DynamicPageController::class, 'checkRouteAvailability'])->name('dynamic-pages.check-route');


   

    // PAGE SEO Admin
    Route::get('/add-seo', [PageSeoController::class, 'addPageSeo'])->name('add.page.seo')->middleware('admin.permission:settings.pageSEOAdd');
    Route::get('/get-seo-details', [PageSeoController::class, 'getPageSeoDetails'])->name('get.page.seo.details');
    Route::post('/seo/page/store', [PageSeoController::class, 'storePageSeo'])->name('page.seo.store');

    // Change User's Password by ADMIN
    Route::get('/admin/users/{user}/change-password', [AdminController::class, 'showChangePasswordForm'])
        ->name('admin.users.changePassword.view')->middleware('admin.permission:manageUser&Admin.manageUser.changePassword');

    Route::put('/admin/users/{user}/change-password', [AdminController::class, 'changeUserPassword'])
        ->name('admin.users.updatePassword');

    // RESEND EMAIL VERIFICATION
    Route::post('/users/{user}/send-verification-email', [UserController::class, 'sendVerificationEmail'])->name('user.send-verification-email');

    // Delete user from admin manage user table
    Route::delete('admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.delete')->middleware('admin.permission:manageUser&Admin.manageAdmin.delete');
    Route::post('/users/bulk-delete', [AdminController::class, 'bulkDelete'])->name('admin.users.bulkDelete')->middleware('admin.permission:manageUser&Admin.manageUser.delete');

    // SEND EMAIL
    Route::get('/send/email', [UserManageController::class, 'sendEmailForm'])->name('send.email.form')->middleware('admin.permission:manageUser&Admin.sendEmail'); 
    Route::get('/manage/send/email', [UserManageController::class, 'manageSendEmail'])->name('manage.email.send')->middleware('admin.permission:manageUser&Admin.sendEmail.manage');
    Route::post('/send-emails', [UserManageController::class, 'sendEmail'])->name('emails.send');

}); //End Admin Middleware


// User Middleware
Route::middleware(['auth', 'verified', 'roles:user', 'check.status', 'check.blocked.ip'])->group(function () {
    
    // Feedback Request
    Route::post('/module/feedback', [RequestModuleFeedbackController::class, 'templateFeedback'])->name('template.module.feedback');

    // User Routes
    Route::get('/user/dashboard', [UserController::class, 'UserDashboard'])->name('user.dashboard');

   

    // Subscriptions
    Route::get('/all/subscription', [SubscriptionController::class, 'AllPackage'])->name('all.package');

    Route::get('/purchase/{pricingPlanId}', [SubscriptionController::class, 'Purchase'])->name('purchase.package');

    Route::post('/store/subscription/plan', [SubscriptionController::class, 'StoreSubscriptionPlan'])->name('store.subscription.plan');

}); //End User Middleware

Route::post('/generate-images', [EducationController::class, 'generateImages']);



Route::middleware(['auth', 'check.status', 'check.blocked.ip'])->group(function () {

    Route::prefix('education')->group(function () {
        Route::get('/add/content', [EducationController::class, 'educationForm'])->name('education.form')->middleware('admin.permission:education.educationWizard');      
        Route::post('/content', [EducationController::class, 'educationContent'])->name('education.content');     
        Route::get('/search', [EducationController::class, 'search'])->name('educationContent.search');  
        Route::get('/get-subjects/{gradeId}', [EducationController::class, 'getSubjects']);
        Route::get('/get-content', [EducationController::class, 'getUserContents'])->name('user_generated_education_content');
        Route::post('/get-contents/subject', [EducationController::class, 'getContentsBySubject'])->name('education.getContentsBySubject');
        Route::get('/content/{id}', [EducationController::class, 'getContent']);
        Route::post('/content/{id}/update', [EducationController::class, 'updateContent']);       
        Route::post('/get-contents/subject/library', [EducationController::class, 'getContentsBySubjectLibrary'])->name('education.getContentsBySubject.library');
        Route::post('/get-content-by-id', [EducationController::class, 'getContentById'])->name('education.getContentById');
        Route::delete('/deleteContent/{id}', [EducationController::class, 'deleteContent'])->name('education.deleteContent');
        Route::get('/content/{id}/download', [EducationController::class, 'downloadPDF'])->name('education.content.download');
        Route::post('/content/{id}/complete', [EducationController::class, 'markAsComplete'])->name('content.mark.complete');       
        Route::post('/content/{id}/add-to-library', [EducationController::class, 'addToLibrary'])->name('content.add.library');
        Route::get('/content/{id}/edit', [EducationController::class, 'edit'])->name('education.content.edit');
        Route::post('/content/update', [EducationController::class, 'update'])->name('education.content.update');
        Route::get('/tools', [EducationController::class, 'manageToolsUser'])->name('education.wizard.creator');
        Route::get('/tools/library', [EducationController::class, 'toolsLibrary'])->name('education.tools.contents')->middleware('admin.permission:education.library') ;
        Route::get('/toolContent/{id}', [EducationController::class, 'getToolContent']);
        Route::post('/toolContent/{id}/update', [EducationController::class, 'updateToolContent']);
        Route::get('/manage/tools', [EducationController::class, 'manageTools'])->name('manage.education.tools')->middleware('admin.permission:education.manageTools') ;
        Route::get('/tool/{id}', [EducationController::class, 'showTool'])->name('tool.show');
        Route::post('/tools/generate-content', [EducationController::class, 'ToolsGenerateContent'])->name('tools.generate.content');
        Route::post('/toggle-favorite', [EducationController::class, 'toggleFavorite'])->name('toggle.favorite');
        
    });

    // Custom Templates
    Route::prefix('custom/ai-content-creator')->group(function () {

        Route::get('/category/add', [CustomTemplateController::class, 'CustomTemplateCategoryAdd'])->name('custom.template.category.add')->middleware('admin.permission:customTemplate.category');

        Route::post('/category/store', [CustomTemplateController::class, 'CustomTemplateCategoryStore'])->name('custom.template.category.store');

        Route::get('/category/edit/{id}', [CustomTemplateController::class, 'CustomTemplateCategoryEdit'])->name('custom.template.category.edit')->middleware('admin.permission:customTemplate.category.edit');

        Route::post('/category/update', [CustomTemplateController::class, 'CustomTemplateCategoryUpdate'])->name('custom.template.category.update');

        Route::get('/category/delete/{id}', [CustomTemplateController::class, 'CustomTemplateCategoryDelete'])->name('custom.template.category.delete')->middleware('admin.permission:customTemplate.category.delete');

        Route::get('/add', [CustomTemplateController::class, 'CustomTemplateAdd'])->name('custom.template.add')->middleware('admin.permission:customTemplate.add');

        Route::post('store', [CustomTemplateController::class, 'CustomTemplateStore'])->name('custom.template.store');

        Route::get('/edit/{slug}', [CustomTemplateController::class, 'CustomTemplateEdit'])->name('custom.template.edit')->middleware('admin.permission:customTemplate.edit');

        Route::post('/update', [CustomTemplateController::class, 'CustomTemplateUpdate'])->name('custom.template.update');
        
        Route::get('/delete/{id}', [CustomTemplateController::class, 'CustomTemplateDelete'])->name('custom.template.delete')->middleware('admin.permission:customTemplate.delete');

        Route::get('/manage', [CustomTemplateController::class, 'CustomTemplateManage'])->name('custom.template.manage')->middleware('admin.permission:customTemplate.manage');

        Route::get('/view/{id}', [CustomTemplateController::class, 'CustomTemplateView'])->name('custom.template.view');

        Route::post('/generate', [CustomTemplateController::class, 'customtemplategenerate'])->name('custom.template.generate');
    });

    // Template Rating
    Route::post('/rate-template', [RatingController::class, 'store'])->name('rate.template');


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

    Route::get('/chat', [MainChat::class, 'MainChatForm'])->name('main.chat.form')->middleware('admin.permission:chattermate.menu');

    Route::post('/save-seen-tour-steps', [MainChat::class, 'saveSeenSteps'])->middleware('auth');

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
        Route::get('/expert/add', [ExpertController::class, 'ExpertAdd'])->name('expert.add')->middleware('admin.permission:cleverExpert.add');
        Route::post('/expert/store', [ExpertController::class, 'ExpertStore'])->name('expert.store');

        Route::get('/expert/edit/{slug}', [ExpertController::class, 'ExpertEdit'])->name('expert.edit')->middleware('admin.permission:cleverExpert.edit');
        Route::post('/expert/update/{id}', [ExpertController::class, 'ExpertUpdate'])->name('expert.update');
        Route::get('/expert/delete/{id}', [ExpertController::class, 'ExpertDelete'])->name('expert.delete')->middleware('admin.permission:cleverExpert.delete');

        // TEST CHAT
        Route::get('/expert/view', [ExpertController::class, 'index'])->name('chat')->middleware('admin.permission:cleverExpert.manage');
        Route::get('/expert/{slug}/{id}', [ExpertController::class, 'ExpertChat'])->name('expert.chat');
        Route::post('/reply', [ExpertController::class, 'SendMessages']);
        Route::post('/expert/delete-conversation', [ExpertController::class, 'deleteConversation']);
        Route::get('/conversation/{expertId}', [ExpertController::class, 'getConversation']);


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
    Route::get('/calender', [FAQController::class, 'calender'])->name('calender')->middleware('admin.permission:calender.menu');
    
    Route::get('/fetch-joke/{category}', [FAQController::class, 'fetchRandomJoke'])->name('jokes');

    Route::prefix('generate')->middleware(['check.status'])->group(function () {
    Route::get('/image/view', [GenerateImagesController::class, 'AIGenerateImageView'])->name('generate.image.view');
    Route::post('/image', [GenerateImagesController::class, 'generateImage'])->name('generate.image');
    Route::post('/extract/image', [GenerateImagesController::class, 'ExtractImage'])->name('extract.image');
    });

    //Profile 
    Route::prefix('profile')->middleware(['check.status'])->group(function () {

        Route::get('/edit', [ProfileEditController::class, 'ProfileEdit'])->name('edit.profile');

        Route::post('/update', [ProfileEditController::class, 'ProfileUpdate'])->name('update.profile');

        Route::post('/update/photo', [ProfileEditController::class, 'ProfilePhotoUpdate'])->name('update.profile.photo');
    });

    //Fixed Templates 
    Route::get('ai-content-creator/manage', [AIContentCreatorController::class, 'AIContentCreatorManage'])->name('aicontentcreator.manage')->middleware('admin.permission:aiContentCreator.manage');

    Route::get('ai-content-creator/view/{slug}', [AIContentCreatorController::class, 'AIContentCreatorView'])->name('aicontentcreator.view');

    Route::get('/ai-content-creator/getContentByUser/{id}', [AIContentCreatorController::class, 'getTemplateContent'])->name('template.content');
    
    // AI Content Creator Extract and Generate
    Route::get('ai-content-creator/extract', [AIContentCreatorController::class, 'AIContentCreatorExtractPromptAndGenerate'])->name('aicontentcreator.view.extract.prompt');
    Route::post('ai-content-creator/generate', [AIContentCreatorController::class, 'AIContentCreatorgenerate'])->name('aicontentcreator.generate');

    //Fixed Prompt Library 
    Route::get('prompt/manage', [PromptLibraryController::class, 'PromptManage'])->name('prompt.manage')->middleware('admin.permission:promptLibrary.manage');

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
    Route::get('greeting/card', [GenerateImagesController::class, 'GreetingCard'])->name('greeting.card')->middleware('admin.permission:greetingCard.menu');

    Route::post('greeting/card/generate', [GenerateImagesController::class, 'GreetingCardGenerate'])->name('generate.greeting.card');


    // GET SUB CATEGORY
    Route::get('/prompt/subcategories/{category_id}', [PromptLibraryController::class, 'getPromptSubCategory']);

    Route::get('/prompt/filter-prompts', [PromptLibraryController::class, 'filterPrompts']);
}); //End Auth Middleware

// FrontEnd
//AI Image Gallery Page
Route::get('/ai/image/gallery', [HomeController::class, 'AIImageGallery'])->name('ai.image.gallery');

// Contact Us Page
Route::get('/contact-us', [HomeController::class, 'ContactUs'])->name('contact.us');
// Stable Frontend
Route::get('/stable-diffusion', [HomeController::class, 'StableDiffusionPage'])->name('stable.frontend.page');

// Frontend Free Template Page
Route::get('/free/ai-content-creator', [HomeController::class, 'FrontendFreeTemplate'])->name('frontend.free.aicontentcreator');
Route::get('/free/ai-content-creator/view/{slug}', [HomeController::class, 'TemplateView'])->name('frontend.free.aicontentcreator.view');
Route::post('/free/ai-content-creator/generate', [HomeController::class, 'templategenerate'])->name('frontend.free.aicontentcreator.generate');

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
Route::get('/newsletter/manage', [HomeController::class, 'NewsLetterManage'])->name('newsletter.manage')->middleware('admin.permission:manageNewsletter.menu');

// GOOGLE SOCIALITE
Route::get('google/login', [AIContentCreatorController::class, 'provider'])->name('google.login');
Route::get('google/callback', [AIContentCreatorController::class, 'callbackHandel'])->name('google.login.callback');


// GITHUB SOCIALITE
Route::get('github/login', [AIContentCreatorController::class, 'githubprovider'])->name('github.login');
Route::get('github/callback', [AIContentCreatorController::class, 'githubcallbackHandel'])->name('github.login.callback');

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
    Route::put('/events/drag/{event}', [EventController::class, 'updateDrag'])->name('events.update.drag');
    Route::delete('/events/{event}', [EventController::class, 'destroy']);


});

Route::get('/stable-form', [StableDifussionController::class, 'index'])->name('stable.form');
Route::post('/stable-image', [StableDifussionController::class, 'generate'])->name('stable.image');
Route::post('/stable-diffusion-like-image', [StableDifussionController::class, 'likeImage'])->name('like.image');
Route::post('/increment-stable-download/{id}', [StableDifussionController::class, 'incrementDownloadCount']);

// Stable Text to Video
Route::get('/stable-text-video-form', [StableDifussionController::class, 'TextVideoindex'])->name('stable.text.video.form');
Route::post('/generate-image-to-video', [StableDifussionController::class, 'generateImageToVideo'])->name('generate.image_to_video');
Route::get('/video-result/{generationId}', [StableDifussionController::class, 'getVideoResult']);
Route::get('/test-resize', [StableDifussionController::class, 'testResize']);

// Stable Image Upscale
Route::get('/stable-upscale-form', [StableDifussionController::class, 'UpscaleForm'])->name('stable.upscale.form');
Route::post('/upscale', [StableDifussionController::class, 'upscale']);

// Stable Diffusion Edit Erase
Route::get('/stable-edit-erase-form', [StableDifussionController::class, 'eraseForm']);
Route::post('/stable-edit-erase', [StableDifussionController::class, 'erase'])->name('stable.edit.erase');

// Stable Diffusion Edit Inpaint
Route::get('/stable-edit-inpaint-form', [StableDifussionController::class, 'inpaintForm']);
Route::post('/stable-edit-inpaint', [StableDifussionController::class, 'inpaint'])->name('stable.edit.inpaint');

// Stable Diffusion Edit Inpaint
Route::get('/stable-edit-outpaint-form', [StableDifussionController::class, 'outpaintForm']);
Route::post('/stable-edit-outpaint', [StableDifussionController::class, 'outpaint'])->name('stable.edit.outpaint');

// Stable Diffusion Search and Replace
Route::get('/stable-edit-search-replace-form', [StableDifussionController::class, 'SearchReplaceForm']);
Route::post('/stable-edit-search-And-Replace', [StableDifussionController::class, 'searchAndReplace'])->name('stable.search.replace');

// Stable Edit Without async
Route::get('/stable-edit-search-recolor-form', [StableDifussionController::class, 'WithoutAsyncEditForm']);
Route::post('/stable--without-async-edit', [StableDifussionController::class, 'WithoutAsyncEdit'])->name('without.async.edit');

// Stable Diffusion Remove Background
Route::get('/stable-edit-remove-bg-form', [StableDifussionController::class, 'RemoveBgForm']);
Route::post('/edit-remove-background', [StableDifussionController::class, 'editRemoveBackground'])->name('stable.remove.background');

// Stable Edit
Route::get('/stable-edit-replacebg-relight-form', [StableDifussionController::class, 'EditForm'])->name('stable.edit.form');
Route::post('/edit-background', [StableDifussionController::class, 'editBackground'])->name('edit.background');
Route::post('/check-generation-status', [StableDifussionController::class, 'checkGenerationStatus']);


// Stable Video
Route::get('/stable-video-form', [StableDifussionController::class, 'Videoindex'])->name('stable.video.form');
Route::post('/generate-video', [StableDifussionController::class, 'generateVideo'])->name('generate.video');
Route::get('/get-video-result/{generationId}', [StableDifussionController::class, 'getVideoResult']);

// Stable Diffusion Control(Sketch)
Route::get('/stable-control-sketch-form', [StableDifussionController::class, 'controlSketchForm']);
Route::post('/stable-control-sketch', [StableDifussionController::class, 'controlSketch'])->name('stable.control.sketch');

 // Catch-all dynamic page route (must be at the end)
 Route::get('/{route}', [DynamicPageController::class, 'show'])
 ->where('route', '.*') // Matches all routes
 ->name('dynamic-pages.show');

