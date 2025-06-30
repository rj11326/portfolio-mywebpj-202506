<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkHistoryController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Company\Auth\CompanyAuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminCompanyApplicationController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\Admin\AdminJobCategoryController;
use App\Http\Controllers\Admin\AdminTagController;
use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\Company\CompanyApplyController;
use App\Http\Controllers\Company\CompanyApplicationController;
use App\Http\Controllers\Company\CompanyUserController;
use App\Http\Controllers\Company\CompanyJobController;
use App\Http\Controllers\Company\CompanyProfileController;
use App\Http\Controllers\Company\CompanyImageController;
use App\Http\Controllers\Company\CompanyDashboardController;
use App\Http\Controllers\Company\CompanyMessageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// トップページ
Route::get('/', [HomeController::class, 'index'])->name('home');

// 求人一覧・詳細（登録不要で閲覧可能）
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Breezeの認証関連ルート
require __DIR__ . '/auth.php';

// 認証が必要なルート群
Route::middleware(['auth'])->group(function () {
    // マイページ
    Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');

    // 個人情報
    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/password', [UserController::class, 'editPassword'])->name('password.edit');

    // 求人への応募
    Route::get('/jobs/{job}/apply', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/thanks', fn() => view('applications.thanks'))->name('applications.thanks');

    // メッセージ
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{application}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{application}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/file/{file}', [MessageController::class, 'downloadFile'])->name('messages.download');

    // お気に入り求人
    Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('saved_jobs.index');

    // 応募履歴（確認・ステータス）
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');

    // 職歴 CRUD
    Route::resource('workhistories', WorkHistoryController::class);

    // 学歴 CRUD
    Route::resource('educations', EducationController::class);

    // 資格 CRUD
    Route::resource('licenses', licenseController::class);
});

// 管理者側
Route::prefix('admin')->name('admin.')->group(function () {
    // 管理者認証関連
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);

    // 認証が必要なルート群
    Route::middleware(['auth:admin'])->group(function () {
        // ログアウト
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // 管理者ダッシュボード
        Route::get('dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        // 企業申請管理
        Route::resource('company_applications', AdminCompanyApplicationController::class)->only(['index', 'show']);
        Route::post('company_applications/{id}/approve', [AdminCompanyApplicationController::class, 'approve'])->name('company_applications.approve');
        Route::post('company_applications/{id}/reject', [AdminCompanyApplicationController::class, 'reject'])->name('company_applications.reject');

        // 求人管理
        Route::get('jobs', [AdminJobController::class, 'index'])->name('jobs.index');
        Route::get('jobs/{job}', [AdminJobController::class, 'show'])->name('jobs.show');
        Route::post('jobs/{job}/toggle-active', [AdminJobController::class, 'toggleActive'])->name('jobs.toggle_active');
        Route::post('jobs/{job}/toggle-featured', [AdminJobController::class, 'toggleFeatured'])->name('jobs.toggle_featured');
        Route::post('jobs/{job}/close', [AdminJobController::class, 'close'])->name('jobs.close');

        // カテゴリ管理
        Route::resource('categories', AdminJobCategoryController::class)->except(['show']);

        // タグ管理
        Route::resource('tags', AdminTagController::class)->except(['show']);

        // 企業管理
        Route::resource('companies', AdminCompanyController::class)->except(['show', 'create', 'store', 'edit', 'update']);
        Route::post('companies/{id}/restore', [AdminCompanyController::class, 'restore'])->name('companies.restore');
    });
});

// 企業側
Route::prefix('company')->name('company.')->group(function () {
    // 企業認証関連
    Route::get('login', [CompanyAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [CompanyAuthenticatedSessionController::class, 'login']);

    // 企業申請フォーム表示・送信
    Route::get('apply', [CompanyApplyController::class, 'create'])->name('apply.index');
    Route::post('apply', [CompanyApplyController::class, 'store'])->name('apply.store');
    Route::get('apply/thanks', [CompanyApplyController::class, 'thanks'])->name('apply.thanks');

    Route::middleware(['auth:company'])->group(function () {
        // 企業ログアウト
        Route::post('logout', [CompanyAuthenticatedSessionController::class, 'logout'])->name('logout');

        // 企業ダッシュボード
        Route::get('dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');

        // 企業情報管理
        Route::get('profiles', [CompanyProfileController::class, 'show'])->name('profiles.show');
        Route::get('profiles/edit', [CompanyProfileController::class, 'edit'])->name('profiles.edit');
        Route::post('profiles/edit', [CompanyProfileController::class, 'update'])->name('profiles.update');

        // 企業画像管理
        Route::get('images', [CompanyImageController::class, 'index'])->name('images');
        Route::post('images/store', [CompanyImageController::class, 'store'])->name('images.store');
        Route::delete('images/{id}', [CompanyImageController::class, 'destroy'])->name('images.destroy');
        Route::post('images/reorder', [CompanyImageController::class, 'reorder'])->name('images.reorder');

        // 企業担当者管理
        Route::resource('users', CompanyUserController::class)->names('users')->except(['show']);
        Route::post('users/{user}/reset-password', [CompanyUserController::class, 'resetPassword'])->name('users.reset_password');

        // 求人管理
        Route::post('jobs/preview', [CompanyJobController::class, 'preview'])->name('jobs.preview');
        Route::resource('jobs', CompanyJobController::class);
        Route::get('jobs/{job}/copy', [CompanyJobController::class, 'copy'])->name('jobs.copy');
        Route::post('jobs/{job}/toggle-active', [CompanyJobController::class, 'toggleActive'])->name('jobs.toggle_active');
        Route::post('jobs/{job}/close', [CompanyJobController::class, 'close'])->name('jobs.close');
        Route::get('jobs/{job}/applicants', [CompanyJobController::class, 'applicants'])->name('jobs.applicants');

        // 応募管理
        Route::get('applications', [CompanyApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [CompanyApplicationController::class, 'show'])->name('applications.show');
        Route::post('applications/{application}/status', [CompanyApplicationController::class, 'status'])->name('applications.status');
        Route::post('applications/{application}/memo', [CompanyApplicationController::class, 'memo'])->name('applications.memo');
        Route::get('applications/{application}/download-all', [CompanyApplicationController::class, 'downloadAllFiles'])->name('applications.download_all');

        // メッセージ
        Route::get('messages/{application}', [CompanyMessageController::class, 'show'])->name('messages.show');
        Route::post('messages/{application}', [CompanyMessageController::class, 'store'])->name('messages.store');
        Route::get('messages/files/{file}', [CompanyMessageController::class, 'downloadFile'])->name('messages.download');
    });
});