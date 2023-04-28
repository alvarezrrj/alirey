<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserBookingController;
use App\Models\Booking;
use App\Notifications\BookingReminder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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
Route::middleware(['auth', 'admin'])->group(function() {
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/single-slot-holiday', [BookingController::class, 'singleSlotHoliday'])
        ->name('bookings.singleSlotHoliday');
    Route::resource('users', UsersController::class);
});

// user
Route::middleware(['auth', 'customer', 'pending_payment'])->group(function() {
    Route::name('user.')->group(function() {
        Route::resource('user/bookings', UserBookingController::class)
            ->only(['index', 'show', 'create', 'store', 'destroy']);
        Route::get('user/bookings/{booking}/checkout', [UserBookingController::class, 'checkout'])
            ->name('bookings.checkout');
        Route::get('user/bookings/{booking}/confirmation',
                [UserBookingController::class, 'confirmation'])
                ->name('bookings.confirmation');
        Route::get('user/failed_payment', [UserBookingController::class, 'failure'])
                ->name('bookings.failure');
    });
});

Route::get('hola', function() { return 'hola';})->name('hola');

//=== Contact ===
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');

//=== Emaill testing ===
Route::get('/email_test', function() {
    $booking = Booking::find(38);
    return (new BookingReminder($booking))->toMail($booking->user);
});
