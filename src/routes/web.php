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
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Public (Guest OK)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/models', [ModelProfileController::class, 'index'])->name('models.index');
Route::get('/models/{modelProfile}', [ModelProfileController::class, 'show'])->name('models.show');

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

// お問い合わせ
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Auth Required (Common)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/job/{job}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/job/{job}', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/jobs/{job}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/jobs/{job}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read.post');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/models/{modelProfile}', [\App\Http\Controllers\FavoriteController::class, 'storeModel'])->name('favorites.store.model');
    Route::post('/favorites/jobs/{job}', [\App\Http\Controllers\FavoriteController::class, 'storeJob'])->name('favorites.store.job');
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
    Route::get('/profile/edit', [ModelProfileEditController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ModelProfileEditController::class, 'update'])->name('profile.update');

    Route::get('/applications', [ModelApplicationController::class, 'index'])->name('applications.index');

    // 応募は「依頼詳細からPOST」想定（画面は jobs.show 側でOK）
    Route::post('/jobs/{job}/apply', [ModelApplicationController::class, 'apply'])->name('jobs.apply');
});

/*
|--------------------------------------------------------------------------
| Painter only
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:painter'])->prefix('painter')->name('painter.')->group(function () {
    Route::get('/profile/edit', [PainterProfileEditController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PainterProfileEditController::class, 'update'])->name('profile.update');

    Route::get('/jobs', [PainterJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/create', [PainterJobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [PainterJobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}/edit', [PainterJobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [PainterJobController::class, 'update'])->name('jobs.update');

    Route::get('/jobs/{job}/applications', [PainterJobApplicationController::class, 'index'])->name('jobs.applications.index');
    Route::post('/jobs/{job}/applications/{application}/accept', [PainterJobApplicationController::class, 'accept'])->name('jobs.applications.accept');
    Route::post('/jobs/{job}/applications/{application}/reject', [PainterJobApplicationController::class, 'reject'])->name('jobs.applications.reject');
});

require __DIR__.'/auth.php';
