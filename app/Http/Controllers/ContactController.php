<?php

/**
 * A controller to allow users to contact the site administrators.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Events\NewContactMessageEvent;
use App\Mail\ContactWebmaster;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contact(User $therapist)
    {
        return view('contact.contact', [
            'user' => auth()->user(),
            'therapist' => $therapist,
        ]);
    }

    public function webmaster()
    {
        return view('contact.webmaster');
    }

    public function webmasterSend(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'nullable|string',
            'screenshot' => 'nullable|image',
            'message' => 'required|string',
        ]);

        Mail::send(new ContactWebmaster(
            text: $validated['message'],
            my_subject: $validated['subject'],
            attachment: $request->file('screenshot')?->path(),
            filename: $request->file('screenshot')?->getClientOriginalName(),
        ));

        session()->flash('message', __('Thank you, your message has been sent.'));

        return redirect()->back();
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
