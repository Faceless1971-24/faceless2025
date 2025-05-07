<?php

use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\Auth\PasswordChangeController;


use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CampaignController as FrontendCampaignController;
use App\Http\Controllers\Frontend\MembershipController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\ContactController;

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Frontend\Auth\LoginController as FrontendLoginController;
use App\Http\Controllers\Frontend\Auth\RegisterController as FrontendRegisterController;

use App\Services\UpdateClockOut;

use Illuminate\Support\Facades\Route;


// Frontend authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [FrontendLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [FrontendLoginController::class, 'login']);
    Route::get('/register', [FrontendRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [FrontendRegisterController::class, 'register']);
});

Route::post('/logout', [FrontendLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// Admin authentication routes
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login']);
    });

    Route::post('/logout', [AdminLoginController::class, 'logout'])
        ->middleware('auth')
        ->name('admin.logout');


Route::get('/password/change', [PasswordChangeController::class, 'index'])->name('password-change.index');
Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password-change.update');

});


// Public routes (accessible without login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');


// Routes for authenticated frontend users - use regular auth, not admin auth
Route::middleware(['auth:web'])->group(function () {
    // User Profile
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [UserProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Membership application
    Route::get('/membership', [MembershipController::class, 'index'])->name('membership.index');
    Route::get('/membership/apply', [MembershipController::class, 'create'])->name('membership.apply');
    Route::post('/membership/apply', [MembershipController::class, 'store'])->name('membership.store');
    Route::get('/membership/status', [MembershipController::class, 'status'])->name('membership.status');

    // Campaign interaction for logged-in users
    Route::post('/campaigns/{campaign}/support', [FrontendCampaignController::class, 'support'])->name('frontend.campaigns.support');
    Route::post('/campaigns/{campaign}/share', [FrontendCampaignController::class, 'share'])->name('frontend.campaigns.share');
    Route::post('/campaigns/{campaign}/comment', [FrontendCampaignController::class, 'comment'])->name('frontend.campaigns.comment');
    // News & Updates
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

    // Public Campaigns
    Route::get('/campaigns', [FrontendCampaignController::class, 'index'])->name('frontend.campaigns.index');
    Route::get('/campaigns/filter', [FrontendCampaignController::class, 'filter'])->name('frontend.campaigns.filter');
    Route::get('/campaigns/{campaign}', [FrontendCampaignController::class, 'show'])->name('frontend.campaigns.show');


});


// Admin routes - add a prefix to keep them separate from frontend
// Use the auth.admin middleware to ensure only admin users can access
Route::prefix('admin')->middleware(['auth', 'auth.admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/coming_soon', function () {
        return view('coming_soon');
    })->name('coming_soon');

    Route::get('/mail', [DashboardController::class, 'mail'])->name('dashboard.mail');
    Route::resource('company_setting', CompanySettingController::class)->only(['index', 'store']);

    Route::get('/get-districts/{division_id}', [LocationController::class, 'getDistricts'])->name('location.districts');
    Route::get('/get-upazilas/{district_id}', [LocationController::class, 'getUpazilas'])->name('location.upazilas');
    Route::get('/get-unions/{upazila_id}', [LocationController::class, 'getUnions'])->name('location.unions');


    // User management
    Route::get('users/{user}/password-reset', [UserController::class, 'passwordReset'])->name('users.password-reset');
    Route::get('users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::get('users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::resource('users', UserController::class);

    // Member Request Management Routes
        Route::get('/member-requests', [App\Http\Controllers\UserController::class, 'memberRequests'])
            ->name('users.memberRequests');

        // Get Member Requests Data for DataTables
        Route::get('/member-requests/get-data', [App\Http\Controllers\UserController::class, 'getMemberRequests'])
            ->name('users.getMemberRequests');

        // Member Request Export to Excel
        Route::get('/member-requests/excel-download', [App\Http\Controllers\UserController::class, 'memberRequestsExcelDownload'])
            ->name('users.member_requests_excel_download');

        // Show Member Request Details
        Route::get('/member-requests/{id}', [App\Http\Controllers\UserController::class, 'showMemberRequest'])
            ->name('users.showMemberRequest');

        // Approve Member Request
        Route::get('/member-requests/{id}/approve', [App\Http\Controllers\UserController::class, 'approveMemberRequest'])
            ->name('users.approveMemberRequest');

        // Reject Member Request
        Route::put('/member-requests/{id}/reject', [App\Http\Controllers\UserController::class, 'rejectMemberRequest'])
            ->name('users.rejectMemberRequest');
    
    Route::get('/users/set-user-type/{user_type}', [UserController::class, 'setUserType'])->name('set-user-type');
    Route::get('user_excel_download', [UserController::class, 'user_excel_download'])->name('user_excel_download');

    Route::get('/ajax/users/get-users', [UserController::class, 'getUsers'])->name('users.get-users');
    Route::resource('designation', DesignationController::class)->only(['index', 'store', 'update', 'destroy']);

    // Admin console
    Route::prefix('admin-console')->group(function () {
        Route::resource('menus', MenuController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('permissions', PermissionController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('user-types/{user_type}/config', [UserTypeController::class, 'config'])->name('user-types.config');
        Route::put('user-types/{user_type}/update-menus', [UserTypeController::class, 'updateMenus'])
            ->name('user-types.update-menus');
        Route::put('user-types/{user_type}/update-permissions', [UserTypeController::class, 'updatePermissions'])
            ->name('user-types.update-permissions');
        Route::resource('user-types', UserTypeController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // Notice board
    Route::resource('notices', NoticeBoardController::class);
    Route::get('/ajax/get-notices', [NoticeBoardController::class, 'getNotices'])->name('get.notices');

    // Admin campaigns
    Route::resource('campaigns', CampaignController::class);
    Route::get('/campaigns/filter', [CampaignController::class, 'filter'])->name('campaigns.filter');
    Route::patch('campaigns/{campaign}/toggle-featured', [CampaignController::class, 'toggleFeatured'])->name('campaigns.toggle.featured');
    Route::patch('campaigns/{campaign}/change-status', [CampaignController::class, 'changeStatus'])->name('campaigns.change.status');
    Route::delete('campaigns/media/delete', [CampaignController::class, 'deleteMedia'])->name('campaigns.delete.media');
    Route::post('campaigns/{campaign}/record-engagement', [CampaignController::class, 'recordEngagement'])
        ->name('campaigns.record.engagement');
    Route::post('campaigns/{campaign}/record-share', [CampaignController::class, 'recordShare'])
        ->name('campaigns.record.share');
    Route::post('campaigns/{campaign}/support', [CampaignController::class, 'support'])
        ->name('campaigns.support');
});

// Move location routes outside admin prefix since they are needed by frontend too
Route::middleware(['auth'])->group(function () {
    Route::get('/get-districts/{division_id}', [LocationController::class, 'getDistricts']);
    Route::get('/get-upazilas/{district_id}', [LocationController::class, 'getUpazilas']);
    Route::get('/get-unions/{upazila_id}', [LocationController::class, 'getUnions']);
});

// Remove this as we're defining our own auth routes above
// require __DIR__ . '/auth.php';

Route::get('/hello', function () {
    $x = new UpdateClockOut();
    $x->clock_out_update();
});
