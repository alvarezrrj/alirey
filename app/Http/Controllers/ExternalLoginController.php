<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\SD\SD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;
use Laravel\Socialite\Facades\Socialite;

class ExternalLoginController extends Controller
{
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(Request $request)
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            // Display notification saying something went wrong
            session()->flash('login', 'Whoops! Something went wrong 🤦. Please try again.');

            return redirect()->route('login');
        }

        $user_from_db = User::where('email', $user->email)->first();

        if (! $user_from_db) {
            // Create user
            $new_user = new User;
            $new_user->firstName = $user->user['given_name'];
            $new_user->lastName = $user->user['family_name'];
            $new_user->email = $user->email;
            $new_user->role_id = Role::where('role', SD::client)->first()->id;
            $new_user->password = Hash::make(uniqid());
            $new_user->email_verified_at = now();
            $new_user->avatar = $user->avatar;
            // $new_user->google_id = $user->id;

            $new_user->save();
            Auth::login($new_user);
        } else {
            // Update and login user
            $user_from_db->avatar = $user->avatar;
            if (! $user_from_db->email_verified_at) {
                $user_from_db->email_verified_at = now();
            }
            $user_from_db->save();
            Auth::login($user_from_db);
        }

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
        // echo '<pre>';
        // echo var_dump($user);
        // echo '<pre>';
        // exit;
    }
}
