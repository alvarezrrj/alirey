<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserBookingController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [RegisteredUserController::class, 'dashboard'])
    ->middleware(['auth', 'verified', 'pending_payment'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/config', [ConfigController::class, 'index'])
    ->name('config')
    ->middleware(['auth']);

//=== Bookings ===
// admin
Route::resource('bookings', BookingController::class)
    ->middleware(['auth', 'admin']);

// user
Route::middleware(['auth', 'pending_payment'])->group(function() {
    Route::name('user.')->group(function() {
        Route::resource('user/bookings', UserBookingController::class)
            ->only(['index', 'show', 'create', 'store', 'destroy']);
        Route::get('user/bookings/{booking}/checkout', [UserBookingController::class, 'checkout'])
            ->name('bookings.checkout');
        Route::get('user/bookings/{booking}/confirmation', 
                [UserBookingController::class, 'confirmation'])
                ->name('bookings.confirmation');
        Route::get('user/bookings/{booking}/failure', 
                [UserBookingController::class, 'failure'])
                ->name('bookings.failure');
    });
});

//=== Contact ===
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');