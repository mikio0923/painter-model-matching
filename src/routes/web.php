<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Painter\PainterJobController;
use App\Http\Controllers\Painter\PainterJobApplicationController;
use App\Http\Controllers\Painter\PainterProfileEditController;
use App\Http\Controllers\Model\ModelProfileEditController;
use App\Http\Controllers\Model\ModelApplicationController;
use App\Http\Controllers\Model\ModelProfileController;
use App\Http\Controllers\Model\ModelQuestionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public (Guest OK)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::get('/models', [ModelProfileController::class, 'index'])->name('models.index');
Route::get('/models/{modelProfile}', [ModelProfileController::class, 'show'])->name('models.show');

// 画家プロフィール公開ページ
Route::get('/painters/{painterProfile}', [\App\Http\Controllers\PainterProfileController::class, 'show'])->name('painters.show');
Route::post('/models/{modelProfile}/questions', [\App\Http\Controllers\ModelProfileQuestionController::class, 'store'])
    ->middleware(['auth', 'throttle:10,1'])
    ->name('model-profile.questions.store');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

Route::get('/information', [\App\Http\Controllers\InformationController::class, 'index'])->name('information.index');
Route::get('/information/{information}', [\App\Http\Controllers\InformationController::class, 'show'])->name('information.show');

// 静的ページ
Route::view('/about', 'about')->name('about');
Route::view('/guideline', 'guideline')->name('guideline');
Route::view('/faq', 'faq')->name('faq');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');
Route::view('/guide/model', 'guide.model')->name('guide.model');
Route::view('/guide/painter', 'guide.painter')->name('guide.painter');

// お問い合わせ（レート制限付き）
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

/*
|--------------------------------------------------------------------------
| Auth Required (Common)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 退会フロー
    Route::get('/account/delete', [\App\Http\Controllers\AccountDeletionController::class, 'show'])
        ->name('account.delete.show');
    Route::delete('/account/delete', [\App\Http\Controllers\AccountDeletionController::class, 'destroy'])
        ->middleware('throttle:3,1')
        ->name('account.delete');

    // メール配信設定（オプトアウト）
    Route::get('/account/email-preferences', [\App\Http\Controllers\EmailPreferenceController::class, 'edit'])
        ->name('account.email-preferences.edit');
    Route::put('/account/email-preferences', [\App\Http\Controllers\EmailPreferenceController::class, 'update'])
        ->middleware('throttle:10,1')
        ->name('account.email-preferences.update');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/job/{job}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/job/{job}', [MessageController::class, 'store'])
        ->middleware('throttle:20,1')
        ->name('messages.store');

    Route::get('/jobs/{job}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/jobs/{job}/reviews', [ReviewController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('reviews.store');

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read.post');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [\App\Http\Controllers\FavoriteController::class, 'toggle'])
        ->middleware('throttle:60,1')
        ->name('favorites.toggle');
    Route::post('/favorites/models/{modelProfile}', [\App\Http\Controllers\FavoriteController::class, 'storeModel'])
        ->middleware('throttle:20,1')
        ->name('favorites.store.model');
    Route::post('/favorites/jobs/{job}', [\App\Http\Controllers\FavoriteController::class, 'storeJob'])
        ->middleware('throttle:20,1')
        ->name('favorites.store.job');
    Route::delete('/favorites/models/{modelProfile}', [\App\Http\Controllers\FavoriteController::class, 'destroyModel'])->name('favorites.destroy.model');
    Route::delete('/favorites/jobs/{job}', [\App\Http\Controllers\FavoriteController::class, 'destroyJob'])->name('favorites.destroy.job');
    Route::delete('/favorites/{favorite}', [\App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

/*
|--------------------------------------------------------------------------
| Model only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:model'])->prefix('model')->name('model.')->group(function () {
    Route::get('/profile/create', [ModelProfileEditController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ModelProfileEditController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ModelProfileEditController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ModelProfileEditController::class, 'update'])
        ->middleware('throttle:5,1')
        ->name('profile.update');

    // ポートフォリオ管理（プロフィール編集から分離）
    Route::get('/portfolio', [\App\Http\Controllers\Model\PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::post('/portfolio', [\App\Http\Controllers\Model\PortfolioController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('portfolio.store');
    Route::put('/portfolio/{image}/caption', [\App\Http\Controllers\Model\PortfolioController::class, 'updateCaption'])->name('portfolio.caption');
    Route::post('/portfolio/{image}/main', [\App\Http\Controllers\Model\PortfolioController::class, 'setMain'])->name('portfolio.set-main');
    Route::delete('/portfolio/{image}', [\App\Http\Controllers\Model\PortfolioController::class, 'destroy'])->name('portfolio.destroy');

    // アカウント設定（マイページから分離した別画面）
    Route::get('/account-settings', function() {
        return view('account.settings');
    })->name('account-settings');

    Route::get('/applications', [ModelApplicationController::class, 'index'])->name('applications.index');

    // 応募は「依頼詳細からPOST」想定（画面は jobs.show 側でOK）
    Route::post('/jobs/{job}/apply', [ModelApplicationController::class, 'apply'])
        ->middleware('throttle:10,1')
        ->name('jobs.apply');

    // あなたへの質問
    Route::get('/questions', [ModelQuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions/{modelProfileQuestion}/answer', [ModelQuestionController::class, 'answer'])->name('questions.answer');
    Route::get('/questions/{modelProfileQuestion}/edit', [ModelQuestionController::class, 'edit'])->name('questions.edit');

    // 個別依頼（Job Offer）
    Route::get('/job-offers', [\App\Http\Controllers\Model\ModelJobOfferController::class, 'index'])->name('job-offers.index');
    Route::get('/job-offers/{offer}', [\App\Http\Controllers\Model\ModelJobOfferController::class, 'show'])->name('job-offers.show');
    Route::post('/job-offers/{offer}/accept', [\App\Http\Controllers\Model\ModelJobOfferController::class, 'accept'])
        ->middleware('throttle:10,1')
        ->name('job-offers.accept');
    Route::post('/job-offers/{offer}/decline', [\App\Http\Controllers\Model\ModelJobOfferController::class, 'decline'])
        ->middleware('throttle:10,1')
        ->name('job-offers.decline');

    // 本人確認（一旦停止 — マイページ掲載写真を実在証明として運用）
    // Route::get('/identity-verification', [\App\Http\Controllers\Model\IdentityVerificationController::class, 'show'])->name('identity-verification');
    // Route::post('/identity-verification', [\App\Http\Controllers\Model\IdentityVerificationController::class, 'store'])
    //     ->middleware('throttle:5,10')
    //     ->name('identity-verification.store');
});

/*
|--------------------------------------------------------------------------
| Painter only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:painter'])->prefix('painter')->name('painter.')->group(function () {
    Route::get('/profile/edit', [PainterProfileEditController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PainterProfileEditController::class, 'update'])
        ->middleware('throttle:5,1')
        ->name('profile.update');

    Route::get('/jobs', [PainterJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [PainterJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [PainterJobController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('jobs.store');
    Route::get('/jobs/{job}/edit', [PainterJobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [PainterJobController::class, 'update'])
        ->middleware('throttle:10,1')
        ->name('jobs.update');
    Route::delete('/jobs/{job}', [PainterJobController::class, 'destroy'])->name('jobs.destroy');

    Route::get('/jobs/{job}/applications', [PainterJobApplicationController::class, 'index'])->name('jobs.applications.index');
    Route::post('/jobs/{job}/applications/{application}/accept', [PainterJobApplicationController::class, 'accept'])->name('jobs.applications.accept');
    Route::post('/jobs/{job}/applications/{application}/reject', [PainterJobApplicationController::class, 'reject'])->name('jobs.applications.reject');

    // 個別依頼（Job Offer）
    Route::get('/job-offers', [\App\Http\Controllers\Painter\PainterJobOfferController::class, 'index'])->name('job-offers.index');
    Route::get('/job-offers/create', [\App\Http\Controllers\Painter\PainterJobOfferController::class, 'create'])->name('job-offers.create');
    Route::post('/job-offers', [\App\Http\Controllers\Painter\PainterJobOfferController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('job-offers.store');
});

/*
|--------------------------------------------------------------------------
| Admin login (guest only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Admin\AdminLoginController::class, 'store'])
            ->middleware('throttle:10,1');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Admin\AdminLoginController::class, 'destroy'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Admin only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    // ユーザー管理
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');

    // 依頼管理
    Route::get('/jobs', [\App\Http\Controllers\Admin\AdminJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [\App\Http\Controllers\Admin\AdminJobController::class, 'show'])->name('jobs.show');
    Route::delete('/jobs/{job}', [\App\Http\Controllers\Admin\AdminJobController::class, 'destroy'])->name('jobs.destroy');

    // お問い合わせ管理
    Route::get('/contacts', [\App\Http\Controllers\Admin\AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [\App\Http\Controllers\Admin\AdminContactController::class, 'show'])->name('contacts.show');
    Route::post('/contacts/{contact}/read', [\App\Http\Controllers\Admin\AdminContactController::class, 'markAsRead'])->name('contacts.read');
    Route::delete('/contacts/{contact}', [\App\Http\Controllers\Admin\AdminContactController::class, 'destroy'])->name('contacts.destroy');

    // 本人確認管理（一旦停止）
    // Route::get('/identity-verifications', [\App\Http\Controllers\Admin\AdminIdentityVerificationController::class, 'index'])->name('identity-verifications.index');
    // Route::get('/identity-verifications/{verification}', [\App\Http\Controllers\Admin\AdminIdentityVerificationController::class, 'show'])->name('identity-verifications.show');
    // Route::get('/identity-verifications/{verification}/image/{field}', [\App\Http\Controllers\Admin\AdminIdentityVerificationController::class, 'image'])->name('identity-verifications.image');
    // Route::post('/identity-verifications/{verification}/approve', [\App\Http\Controllers\Admin\AdminIdentityVerificationController::class, 'approve'])->name('identity-verifications.approve');
    // Route::post('/identity-verifications/{verification}/reject', [\App\Http\Controllers\Admin\AdminIdentityVerificationController::class, 'reject'])->name('identity-verifications.reject');

    // お知らせ管理
    Route::get('/information', [\App\Http\Controllers\Admin\AdminInformationController::class, 'index'])->name('information.index');
    Route::get('/information/create', [\App\Http\Controllers\Admin\AdminInformationController::class, 'create'])->name('information.create');
    Route::post('/information', [\App\Http\Controllers\Admin\AdminInformationController::class, 'store'])->name('information.store');
    Route::get('/information/{information}/edit', [\App\Http\Controllers\Admin\AdminInformationController::class, 'edit'])->name('information.edit');
    Route::put('/information/{information}', [\App\Http\Controllers\Admin\AdminInformationController::class, 'update'])->name('information.update');
    Route::delete('/information/{information}', [\App\Http\Controllers\Admin\AdminInformationController::class, 'destroy'])->name('information.destroy');
});

require __DIR__.'/auth.php';
