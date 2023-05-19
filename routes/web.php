<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Livewire\Home;
use App\Mail\ContactTherapist;
use App\Models\Booking;
use App\Notifications\BookingReminder;
use App\Mail\EmailConfirmation;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\BookingConfirmation;
use App\Notifications\MessageConfirmation;
use App\Notifications\NewMessage;
use App\Notifications\SetUpYourPassword;
use Illuminate\Support\Facades\Password;

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

Route::get('/terms', function() {
    return view('legal.terms-of-service');
})->name('terms');
Route::get('/privacypolicy', function() {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/dashboard', [RegisteredUserController::class, 'dashboard'])
    // Add verified middleware
    ->middleware(['auth', 'pending_payment'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// everyone
// Add verified middleware
Route::middleware(['auth', 'pending_payment'])->group(function() {
    Route::resource('bookings', BookingController::class)
        ->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::get('bookings/{therapist}/create', [BookingController::class, 'create'])
        ->name('bookings.create');
    Route::get('bookings/{booking}/checkout', [BookingController::class, 'checkout'])
        ->name('bookings.checkout');
    Route::get('bookings/{booking}/confirmation', [BookingController::class, 'confirmation'])
        ->name('bookings.confirmation');
    Route::get('failed_payment', [BookingController::class, 'failure'])
        ->name('bookings.failure');
});

// admin
// Add verified middleware
Route::middleware(['auth', 'admin'])->group(function() {
    Route::resource('users', UsersController::class);
    Route::get('/config', [ConfigController::class, 'index'])
        ->name('config');
});

// customer
// Add verified middleware
Route::middleware(['auth', 'customer'])->group(function() {
    // Contact forms (for contacting therapist)
    Route::get('contact/{therapist}/query', [ContactController::class, 'therapist'])
        ->name('contact.therapist');
    Route::post('contact/query', [ContactController::class, 'therapistSend'])
        ->name('contact.therapist.send');
    // Bookings
    Route::name('user.')->group(function() {
    });
});

//=== Contact ===
// site admin
Route::get('contact/webmaster', [ContactController::class, 'webmaster'])
    ->name('contact.webmaster');
Route::post('contact/webmaster', [ContactController::class, 'webmasterSend'])
    ->name('contact.webmaster.send');
// therapist
Route::post('contact', [ContactController::class, 'send'])
    ->name('contact');

//=== Emaill testing ===
Route::get('/email_test', function() {
    $booking  = Booking::find(155);
    return (new SetUpYourPassword)
        ->toMail($booking->user);

    // return new ContactTherapist(
    //     name: 'Carlitos',
    //     email:  'hello@example.com',
    //     my_subject: 'consulta',
    //     text: 'Hola como estas, este es el mensaje',
    // );
});

Route::get('reset_link', function() {
    $user = User::first();
    return Password::createToken($user);
});

// Route::get('do_flags', function() {
//     $codes = \App\Models\Code::all();
//     foreach($codes as $code) {
//         if (!isset($code->flag)) {
//             $code->update(['flag' => '&nbsp;&nbsp;&nbsp;&nbsp;']);
//             $code->save;
//         } else {
//             $new = str_replace('U+', '&#x', $code->flag);
//             $new = str_replace(' ', ';', $new);
//             $new .= ';';
//             $code->update(['flag' => $new]);
//             $code->save();
//         }
//     }
// });
