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
use App\Models\ContactMessage;
use App\Notifications\MessageConfirmation;
use App\Notifications\NewMessage;

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
    return view('home');
});

Route::get('/dashboard', [RegisteredUserController::class, 'dashboard'])
    // Add verified middleware
    ->middleware(['auth', 'pending_payment'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// admin
// Add verified middleware
Route::middleware(['auth', 'admin'])->group(function() {
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/single-slot-holiday', [BookingController::class, 'singleSlotHoliday'])
        ->name('bookings.singleSlotHoliday');
    Route::resource('users', UsersController::class);
    Route::get('/config', [ConfigController::class, 'index'])
        ->name('config');
});

// customer
// Add verified middleware
Route::middleware(['auth', 'customer', 'pending_payment'])->group(function() {
    // Contact forms (for contacting therapist)
    Route::get('contact/{therapist}/query', [ContactController::class, 'contact'])
        ->name('contact');
    Route::post('contact/query', [ContactController::class, 'send'])
        ->name('contact.send');

    // Bookings
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

//=== Contact (for contacting site admin) ===
Route::get('contact/webmaster', [ContactController::class, 'webmaster'])
    ->name('contact.webmaster');
Route::post('contact/webmaster', [ContactController::class, 'webmasterSend'])
    ->name('contact.webmaster.send');

//=== Emaill testing ===
Route::get('/email_test', function() {
    $message = new ContactMessage([
        'name' => 'Test User',
        'email' => 'alvarezrrj@gmail.com',
        'about' => 'Test',
        'message' => 'This is a test message',
        'user_id' => 7,
        'therapist_id' => 1,
    ]);
    return (new NewMessage($message))->toMail($message->user);
});

Route::get('do_flags', function() {
    $codes = \App\Models\Code::all();
    foreach($codes as $code) {
        if (!isset($code->flag)) {
            $code->update(['flag' => '&nbsp;&nbsp;&nbsp;&nbsp;']);
            $code->save;
        }
    }
    echo 'Allisgood';
    exit;
});
