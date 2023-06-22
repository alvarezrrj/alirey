<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use \Google\Client;

class GoogleCalController extends Controller
{
    /**
     * Redirects the user to Google's authorization page.
     */
    public function auth(Request $request)
    {
        $client = $this::initGoogleClient();
        $client->setScopes([\Google\Service\Calendar::CALENDAR_EVENTS]);
        // Setting to 'offline' allows the app to refresh access tokens
        $client->setAccessType('offline');
        // Give Google a hint as to who is trying to log in. Simplifies the process.
        $client->setLoginHint($request->user()->email);
        // Use booking ID as a CSRF token
        // TODO implement state
        // $client->setState(Crypt::encryptString("$booking->id"));

        return new RedirectResponse($client->createAuthUrl());
    }

    /**
     * Receives Google's auth code as a url parameter after user has
     * given permission to access scopes.
     */
    public function callback(Request $request)
    {
        $authCode = $request->get('code');
        if (! $authCode) {
            // User didn't approve the request.
            $error = $request->get('error');
            session()->flash('error', $error);

            return redirect()->route('google.calendar.finished', 'error');
        }
        // try {
        //     $state = Crypt::decryptString($request->get('state'));
        // } catch (DecryptException $e) {
        //     // CSRF token doesn't match.
        //     session()->flash('error', 'Something went wrong.');
        //     return redirect()->route('dashboard');
        // }
        $client = $this::initGoogleClient();
        $client->setAccessType('offline');
        // Exchange the authorization code for an access token.
        $client->authenticate($authCode);
        $token = $client->getAccessToken();
        $request->user()->google_token = Crypt::encryptString(json_encode($token));
        $request->user()->save();

        return redirect()->route('google.calendar.finished', 'ok');
    }

    /**
     * @param string $result = 'ok' | 'error'
     */
    public function finished(string $result)
    {
        return view('oauth.finished')->with('result', $result);
    }


    /**
     * Store a booking on the user's Google Calendar
     */
    public static function store(Booking $booking)
    {
        $client = self::initGoogleClient($booking->user);
        $calendarService = new \Google\Service\Calendar($client);
        $event = self::createEvent($booking);
        $calendarId = config('services.google.calendar_id');
        $inserted = $calendarService->events->insert($calendarId, $event);
        // TODO Find out what iCalUID is (in $inserted->iCalUID)
        $booking->fill([
            'google_event_id' => $inserted->id
        ])->save();
    }

    /**
     * Update a booking on the user's Google Calendar
     */
    public static function update(Booking $booking)
    {
        if ($booking->google_event_id) {
            $client = self::initGoogleClient($booking->user);
            $event = self::createEvent($booking);
            $calendarId = config('services.google.calendar_id');
            $calendarService = new \Google\Service\Calendar($client);
            $calendarService->events->update($calendarId, $booking->google_event_id, $event);
        }
    }

    /**
     * Delete a booking from the user's Google Calendar
     */
    public static function delete(Booking $booking)
    {
        if ($booking->google_event_id) {
            $client = self::initGoogleClient($booking->user);
            $calendarId = config('services.google.calendar_id');
            $calendarService = new \Google\Service\Calendar($client);
            $calendarService->events->delete($calendarId, $booking->google_event_id);
        }
    }

    private static function createEvent(Booking $booking, $reminders = [24 * 60, 20])
    {
        $start = $booking->day
            ->addHours($booking->slot->start->format('H'))
            ->addMinutes($booking->slot->start->format('i'));
        $end = $booking->day
            ->addHours($booking->slot->end->format('H'))
            ->addMinutes($booking->slot->end->format('i'));
        $overrides = [];
        foreach ($reminders as $minutes) {
            $overrides[] = ['method' => 'popup', 'minutes' => $minutes];
        }
        $location = $booking->virtual
            ? 'WhatsApp'
            : 'Fleming 180, Cosquin, Cordoba';

        return new \Google_Service_Calendar_Event([
            'summary' => 'Sesión de Bioconstelación',
            'location' => $location,
            'start' => [
                'dateTime' => $start,
            ],
            'end' => [
                'dateTime' => $end,
            ],
            'reminders' => [
                'useDefault' => FALSE,
                'overrides' => $overrides,
            ],
        ]);
    }

    private static function initGoogleClient(?User $user=null)
    {
        $client = new Client();
        $client->setApplicationName(config('app.name'));
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(route('google.calendar.callback'));

        if ($user) {
            try {
                $token = json_decode(Crypt::decryptString($user->google_token), true);
            } catch (DecryptException $e) {
                // Value could not be decrypted. Has probably been tampered with.
                $user->google_token = null;
                $user->save();

                session()->flash('warning', 'We couldn\'t connect to Google Calendar. Please contact us if the issue persists.');

                throw $e;
            }

            $client->setAccessToken($token);
        }

        return $client;
    }

    public static function revokeToken(User $user)
    {
        $client = self::initGoogleClient($user);
        $client->revokeToken();
    }
}
