<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\SD\SD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        // Registration is being handled by App\Http\Livewire\Register
    }

    public function dashboard(Request $request)
    {
        $upcoming = Booking::whereDate('day', '>=', Carbon::today())
                           ->where('status', '!=', SD::BOOKING_CANCELLED)
                           ->where('status', '!=', SD::BOOKING_COMPLETED)
                           ->where('is_booking', true)
                           ->oldest('day')
                           ->limit(1);

        if($request->user()->isAdmin()) {
            $upcoming = $upcoming->first();
            $is_admin = true;
        } else {
            $upcoming = $upcoming->where('user_id', $request->user()->id)
                                 ->first();
        }

        return view('dashboard', [
            'booking' => $upcoming,
            'is_admin' => $is_admin ?? false,
        ]);
    }
}
