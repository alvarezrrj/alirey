<?php

/**
 * A controller to allow users to contact the site administrators.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Events\NewContactMessageEvent;
use App\Mail\ContactTherapist;
use App\Mail\ContactWebmaster;
use App\Mail\EmailConfirmation;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Get contact form
     * Logged in user to therapist
     */
    public function therapist(User $therapist)
    {
        return view('contact.contact', [
            'user' => auth()->user(),
            'therapist' => $therapist,
        ]);
    }

    /**
     * Send message to therapist
     */
    public function therapistSend(Request $request)
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

    /**
     * Get contact form
     * Any user to webmaster
     */
    public function webmaster()
    {
        return view('contact.webmaster');
    }

    /**
     * Send message
     */
    public function webmasterSend(Request $request)
    {
        // Check cloudflare turnstile token
        $SECRET_KEY = config('app.cf_turnstile_sk');
        $cf_token = $request->input('cf-turnstile-response');
        $cf_siteverify_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

        $response = Http::post($cf_siteverify_url, [
            'secret' => $SECRET_KEY,
            'response' => $cf_token
        ]);
        $not_a_robot = $response->json('success');

        Gate::allowIf($not_a_robot);

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

    /**
     * Send message
     * Any user to therapist
     */
    public function send(Request $request)
    {
        $validated  = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        Mail::to($request['email'])->send(new EmailConfirmation());
        // Mail::to(config('mail.therapist_addr'))->send(new ContactTherapist(
        Mail::to('contact@rodrigoalvarez.co.uk')->send(new ContactTherapist(
            name: $validated['name'],
            email:  $validated['email'],
            my_subject: $validated['subject'],
            text: $validated['message'],
        ));

        session()->flash('message', __('Your message has been sent.'));

        return redirect()->back();
    }
}
