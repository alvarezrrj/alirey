<?php

/**
 * A controller to allow users to contact the site administrators.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Events\NewContactMessageEvent;
use App\Models\User;

class ContactController extends Controller
{
    public function contact(User $therapist)
    {
        return view('contact.contact', [
            'user' => auth()->user(),
            'therapist' => $therapist,
        ]);
    }

    public function errorReporting()
    {
        return view('contact.error-reporting');
    }

    public function send(Request $request)
    {
        $validated  = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'therapist_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $message = new ContactMessage($validated);
        $message->save();

        NewContactMessageEvent::dispatch($message);

        session()->flash('message', __('Your message has been sent.'));

        return redirect()->back();
    }
}
