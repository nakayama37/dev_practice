<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| manager Routes
|--------------------------------------------------------------------------
|
| 管理者権限のルート
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
| 利用者権限のルート
|
*/
Route::middleware('can:user-higher')->group(function () {
    Route::get('index', function () {
        dd('user');
    });
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
