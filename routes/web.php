<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\AlterationController;
use App\Http\Controllers\Dashboard\BillingController;
use App\Http\Controllers\Dashboard\CaseController;
use App\Http\Controllers\Dashboard\FitnessController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\InsuranceController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\OtherUserController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\PermitController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RolePermission\PermissionController;
use App\Http\Controllers\Dashboard\RolePermission\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\TaxController;
use App\Http\Controllers\Dashboard\TransferController;
use App\Http\Controllers\Dashboard\User\ArchivedUserController;
use App\Http\Controllers\Dashboard\User\UserController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\Dashboard\VehicleExportController;
use App\Http\Middleware\CheckAccountActivation;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/lang/{lang}', function ($lang) {
    // dd($lang);
    if(! in_array($lang, ['en','fr','ar','de'])){
        abort(404);
    }else{
        session(['locale' => $lang]);
        App::setLocale($lang);
        Log::info("Locale set to: " . $lang);
        return redirect()->back();
    }
})->name('lang');

Route::get('/current-time', function () {
    return response()->json([
        'time' => Carbon::now()->format('h:iA') // Returns time in 12-hour format with AM/PM
    ]);
});

Auth::routes();
Route::get('/', function () {
    return redirect()->route('dashboard');
});
// Guest Routes
Route::group(['middleware' => ['guest']], function () {

    //User Login Authentication Routes
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login-attempt', [LoginController::class, 'login_attempt'])->name('login.attempt');

    //User Register Authentication Routes
    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('registration-attempt', [RegisterController::class, 'register_attempt'])->name('register.attempt');

    // Google Authentication Routes
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.login');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.login.callback');
    // Github Authentication Routes
    Route::get('auth/github', [GithubController::class, 'redirectToGithub'])->name('auth.github.login');
    Route::get('auth/github/callback', [GithubController::class, 'handleGithubCallback'])->name('auth.github.login.callback');
    // Facebook Authentication Routes
    // Route::controller(FacebookController::class)->group(function () {
    //     Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
    //     Route::get('auth/facebook/callback', 'handleFacebookCallback');
    // });

});

// Authentication Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('login-verification', [AuthController::class, 'login_verification'])->name('login.verification');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('verify-account', [AuthController::class, 'verify_account'])->name('verify.account');
    Route::post('resend-code', [AuthController::class, 'resend_code'])->name('resend.code');

    // Verified notification
    Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verification_verify'])->middleware(['signed'])->name('verification.verify');
    Route::get('email/verify', [AuthController::class, 'verification_notice'])->name('verification.notice');
    Route::post('email/verification-notification', [AuthController::class, 'verification_send'])->middleware(['throttle:2,1'])->name('verification.send');
    // Verified notification
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/deactivated', function () {
        return view('errors.deactivated');
    })->name('deactivated');
    Route::middleware(['check.activation'])->group(function () {

        Route::resource('profile', ProfileController::class);
        Route::post('profile/setting/account/{id}', [ProfileController::class, 'accountDeactivation'])->name('account.deactivate');
        Route::post('profile/security/password/{id}', [ProfileController::class, 'passwordUpdate'])->name('update.password');

        Route::get('/get/notifications', [NotificationController::class, 'getNotifications']);
        Route::get('/notifications/click/{id}', [NotificationController::class, 'notificationClickHandle'])->name('notification.click');
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::post('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
        Route::post('/notifications/{id}/delete', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');
        Route::get('/notifications/send-test-noti/{id}', [NotificationController::class, 'testNotification']);

        Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');
        Route::get('dashboard/stats', [HomeController::class, 'dashboardStatsIndex'])->name('dashboard.stats');
        Route::get('/dashboard/refresh', [HomeController::class, 'refreshData'])->name('dashboard.refresh');

        Route::get('/dashboard/pending-items', [HomeController::class, 'getPendingItems'])->name('dashboard.pending-items');

        // Admin Dashboard Authentication Routes
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
            Route::resource('user', UserController::class);
            Route::resource('archived-user', ArchivedUserController::class);
            Route::get('user/restore/{id}', [ArchivedUserController::class, 'restoreUser'])->name('archived-user.restore');
            Route::get('user/status/{id}', [UserController::class, 'updateStatus'])->name('user.status.update');

            // Role & Permission Start
            Route::resource('permissions', PermissionController::class);

            Route::resource('roles', RoleController::class);
            //Role & Permission End

            // Setting Routes
            Route::resource('setting', SettingController::class);
            Route::put('company/setting/{id}', [SettingController::class, 'updateCompanySettings'])->name('setting.company.update');
            Route::put('recaptcha/setting/{id}', [SettingController::class, 'updateRecaptchaSettings'])->name('setting.recaptcha.update');
            Route::put('system/setting/{id}', [SettingController::class, 'updateSystemSettings'])->name('setting.system.update');
            Route::put('email/setting/{id}', [SettingController::class, 'updateEmailSettings'])->name('setting.email.update');
            Route::post('send-mail/setting', [SettingController::class, 'sendTestMail'])->name('setting.send_test_mail');

            // User Dashboard Authentication Routes
            Route::resource('other-users', OtherUserController::class);
            Route::get('other-users/status/{id}', [OtherUserController::class, 'updateStatus'])->name('other-users.status.update');


            Route::resource('cases', CaseController::class);

            Route::post('cases/activities/store', [CaseController::class, 'storeActivity'])
                ->name('cases.activities.store');

            Route::get('cases/{case}/next-steps', [CaseController::class, 'nextSteps'])
                ->name('cases.next-steps');

            Route::post('cases/{case}/add-work/{workType}', [CaseController::class, 'addWork'])
                ->name('cases.add-work');

            Route::get('cases/{case}/add-work/{workType}', [CaseController::class, 'showAddWorkForm'])
                ->name('cases.add-work-form')
                ->whereIn('workType', ['transfer', 'alteration', 'tax', 'insurance', 'permit', 'fitness']);

            Route::post('cases/{case}/skip-work/{workType}', [CaseController::class, 'skipWork'])
                ->name('cases.skip-work');

            Route::post('cases/{case}/finish', [CaseController::class, 'finishAll'])
                ->name('cases.finish');

            Route::resource('transfers', TransferController::class);

            Route::resource('alterations', AlterationController::class);

            Route::resource('taxes', TaxController::class);

            Route::resource('insurances', InsuranceController::class);

            Route::resource('permits', PermitController::class);

            Route::resource('fitness', FitnessController::class);

            Route::resource('billings', BillingController::class);

            Route::resource('payments', PaymentController::class);

        });
    });
    Route::get('/dashboard/cases/{id}/items', [CaseController::class, 'getCaseItems']);
    Route::post('/api/cases/store', [CaseController::class, 'storeCaseViaApi']);
});

// Frontend Pages Routes
Route::name('frontend.')->group(function () {
    Route::get('testing', [BillingController::class, 'testing'])->name('testing');
    Route::get('billing/verify/{bill_no}', [BillingController::class, 'verifyBilling'])->name('billing.verify');
});


//Artisan Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        return "Application cache cleared!";
    })->name('clear.cache');

    Route::get('/clear-config', function () {
        Artisan::call('config:clear');
        return "Configuration cache cleared!";
    })->name('clear.config');

    Route::get('/clear-view', function () {
        Artisan::call('view:clear');
        return "View cache cleared!";
    })->name('clear.view');

    Route::get('/clear-route', function () {
        Artisan::call('route:clear');
        return "Route cache cleared!";
    })->name('clear.route');

    Route::get('/clear-optimize', function () {
        Artisan::call('optimize:clear');
        return "Optimization cache cleared!";
    })->name('clear.optimize');
});
