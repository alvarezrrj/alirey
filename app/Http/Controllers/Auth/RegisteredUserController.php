<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Code;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use App\SD\SD;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $codes = DB::table('codes')->orderBy('country', 'asc')->get();

        return view('auth.register', [
            'codes' => $codes
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role_id' => ['integer'],
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'code_id' => ['required', 'integer'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new User;
        $user->role_id = $request->user?->isAdmin()
            ? $request->role_id
            : Role::where('role', SD::client)->first()->id;
        $user->firstName = $request->firstName;
        $user->lastName =  $request->lastName;
        $user->code_id = $request->code_id;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function dashboard(Request $request)
    {
        $upcoming = Booking::whereDate('day', '>=', Carbon::today())
                           ->where('status', '!=', SD::BOOKING_CANCELLED)
                           ->where('status', '!=', SD::BOOKING_COMPLETED)
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
