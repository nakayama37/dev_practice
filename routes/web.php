<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Capsule\Manager;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Api\AddressController;

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| ウェルカムページのルート
|
*/
Route::get('/', [EventController::class, 'welcome'])->name('welcome');
Route::get('/search/index', [EventController::class, 'searchIndex'])->name('search.index');
Route::get('/events/search', [EventController::class, 'search'])->name('events.search');
/*
|--------------------------------------------------------------------------
| admin Routes
|--------------------------------------------------------------------------
|
| 管理者以上権限のルート
|
*/
Route::prefix('admin')
    ->middleware('can:admin')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::get('categories/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'toggle'])->name('categories.public.toggle');
        Route::post('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
       
    });
/*
|--------------------------------------------------------------------------
| manager Routes
|--------------------------------------------------------------------------
|
| 管理者以上権限のルート
|
*/
Route::prefix('manager')
    ->middleware('can:manager-higher')->group(function () {
        Route::get('events/past', [EventController::class, 'past'])->name('events.past');
        Route::resource('events', EventController::class);
    });
/*
|--------------------------------------------------------------------------
| user Routes
|--------------------------------------------------------------------------
|
| 利用者以上権限のルート
|
*/
 Route::middleware('can:user-higher')->group(function () {
    Route::get('/home', [EventController::class, 'home'])->name('home');
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/{id}', [MyPageController::class, 'show'])->name('mypage.show');
    Route::post('/mypage/{id}', [MyPageController::class, 'cancel'])->name('mypage.cancel');
    Route::post('/reservations', [EventController::class, 'join'])->name('reservations.join');
    Route::post('/events/{event}/like', [LikeController::class, 'toggleLike'])->name('events.like');
    Route::get('/events/{event}/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
/*
|--------------------------------------------------------------------------
| user-only Routes
|--------------------------------------------------------------------------
|
| 利用者のみ権限のルート
|
*/
 Route::prefix('user')
    ->middleware('can:user-only')->group(function () {
        Route::get('/events', [EventController::class, 'createByOnlyUser'])->name('user.events.create');
        Route::post('/events', [EventController::class, 'storeByOnlyUser'])->name('user.events.store');
});

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| イベント詳細のみログイン必須
|
*/
Route::middleware('auth')->get('/reservations/{event}', [EventController::class, 'detail'])->name('reservations.detail');
/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Api
|
*/
Route::get('/api/get-address/{postcode}', [AddressController::class, 'getAddress']);

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| プロフィール機能 デフォルトのもの
|
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
