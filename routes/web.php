<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Database\Capsule\Manager;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyPageController;

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

Route::get('/', [EventController::class, 'welcome'])->name('welcome');
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
| manager Routes
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
    Route::get('/reservations/{event}', [EventController::class, 'detail'])->name('reservations.detail');
    Route::post('/reservations', [EventController::class, 'join'])->name('reservations.join');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
