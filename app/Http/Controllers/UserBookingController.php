<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateBookingRequest;
use App\Models\Booking;
use App\Models\Code;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Notifications\BookingReminder;
use App\SD\SD;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LengthException;

class UserBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bookings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bookings.upsert', [
            'booking' => null,
            'codes' => Code::all(),
            'data' => (new BookingController)->getData(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateBookingRequest $request)
    {
        $validated = $request->validated();

        // Create pending payment
        $payment = Payment::create([
            'amount' => 0,
            'status' => SD::PAYMENT_PENDING,
        ]);
        $validated['payment_id'] = $payment->id;

        $validated['status'] = SD::BOOKING_PENDING;

        $booking = $request->user()->bookings()->create($validated);

        //$request->session()->flash('message', 'Booking saved.');

        // TO DO 
        // Schedule booking deletion: delete it if it has a pref_id and it is
        // expired

        return redirect()->route('user.bookings.checkout', $booking);
    }
    
    public function checkout(Booking $booking)
    {
        $this->authorize('checkout', $booking);

        // Create MP preferene
        $preference_id = MercadoPagoController::create_or_get_preference(
            $booking, $booking->therapist->config->price
        );

        // MercadoPagoController returns false if a preference is expired 
        // (10' passed ) since its creation, and deletes the booking.
        if(! $preference_id)
        {
            return view('bookings.expired');
        }

        $expires_in = Carbon::now()->diffInSeconds($booking->pref_expiry);

        return view('bookings.checkout', [
            'booking' => $booking,
            'price' => $booking->therapist->config->price,
            'preference_id' => $preference_id,
            'expires_in' => $expires_in
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        return view('bookings.show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('user.bookings.create');
    }

    /**
     * MP Payment confirmation
     * MP redirects here after a successful payment
     */
    public function confirmation(Booking $booking)
    {
        $this->authorize('view', $booking);

        session()->forget('pending_payment');
        // Removing preference attributes from booking keeps it from being deleted
        // by scheduled task
        $booking->pref_id = null;
        $booking->pref_expiry = null;
        $booking->save();

        // This keeps the booking from being checked out twice (BookingPolicy checks
        // payment status to be == PENDING)
        DB::transaction(function () use ($booking) {
            if($booking->payment->status == SD::PAYMENT_PENDING) {
                $booking->payment->status = SD::PAYMENT_MP_AWAIT;
                $booking->payment->save();
            }
        });

        session()->flash('message', __('Thank you for your booking! Please check your details to make sure they are correct. You will be receiving a copy of this via email.'));

        return redirect()->route('user.bookings.show', $booking);
    }

    /**
     * MP Payment failure
     * MP redirects here after a failed payment
     */
    public function failure()
    {
        return view('bookings.failure');
    }

    /**
     * Delete bookings that have not been paid for after 10' of creating the
     * MP preference
     * 
     * Gets called every minute by job scheduler \App\Console\Kernel::schedule()
     */
    static function purge_unpaid_bookings()
    {
        // Give the preference an extra minute to avoid deleting it the 
        // moment it gets paid for
        Booking::where('pref_expiry', '<', Carbon::now()->subMinute())
            ->delete();
    }

    static function send_reminder()
    {
        // All bookings happening within 20'
        $bookings = Booking::whereHas('slot', function(Builder $query) {
            $query->whereTime('start', '>=', Carbon::now())
                  ->whereTime('start', '<=', Carbon::now()->addMinutes(20));
        })
        ->whereDate('day', Carbon::now())
        ->where('reminder_sent', false)
        ->where('status', SD::BOOKING_PENDING)
        ->cursor();

        foreach($bookings as $booking) {
            $booking->user->notify(new BookingReminder($booking));
            $booking->reminder_sent = true;
            $booking->save();
        }
    }
}
