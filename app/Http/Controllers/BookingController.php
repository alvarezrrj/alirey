<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SingleSlotHolidayRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Holiday;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
use App\Notifications\BookingReminder;
use App\SD\SD;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

/**
 * A controller for the admin to manage bookings.
 */
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $therapist = Role::where('role', SD::admin)->first()->users()->first();

        return view('bookings.index', [
            'therapist' => $therapist,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $therapist)
    {
        return view('bookings.upsert', [
            'booking' => null,
            'therapist' => $therapist,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // Handled by App\Http\Livewire\BookingForm
    }

    /**
     * Store a single slot holiday.
     */
    public function singleSlotHoliday(SingleSlotHolidayRequest $request)
    {
        $data = $request->validated();
        // Create dummy payment
        $payment = Payment::create([
            'amount' => 0,
            'status' => SD::PAYMENT_PENDING,
        ]);
        $data['payment_id'] = $payment->id;
        $data['status'] = SD::BOOKING_PENDING;
        $data['is_booking'] = false;
        $holiday = $request->user()->bookings()->create($data);

        $holiday->save();

        session()->flash('message', 'Changes saved.');

        return Redirect::route('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
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
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        $this->authorize('update', $booking);

        return view('bookings.upsert', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookingRequest $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->authorize('update', $booking);

        $validated_user = $request->safe()->only([
            'firstName',
            'lastName',
            'email',
            'code_id',
            'phone'
        ]);
        $validated_payment = $request->safe()->only(['amount']);
        $validated_booking = $request->safe()->only([
            'virtual',
            'day',
            'slot_id'
        ]);

        $booking->user()->update($validated_user);
        $booking->payment()->update($validated_payment);
        $booking->update($validated_booking);

        session()->flash('message', 'Booking updated.');

        return redirect(route('bookings.show', $booking->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        if ($request->user()->isAdmin()) return Redirect::back();

        else return Redirect::route('bookings.create', $booking->therapist);
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
            return view('bookings.expired', [
                'therapist' => $booking->therapist,
            ]);
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

        return redirect()->route('bookings.show', $booking);
    }

    /**
     * MP Payment failure
     * MP redirects here after a failed payment
     */
    public function failure()
    {
        return view('bookings.failure');
    }

    public static function getData($booking = null)
    {
        $admin = Role::where('role', SD::admin)->first()->users()->first();
        $working_days = $admin->config->working_days;
        $now = Carbon::today();
        $last_day = $admin->config->allways_open
        ? Carbon::today()->addDays($admin->config->anticipation)
        : Carbon::create($admin->config->open_until);
        $holidays = Holiday::where('user_id', $admin->id)
                    ->whereDate('day', '>=', $now)
                    ->whereDate('day', '<=', $last_day)
                    ->get()
                    ->pluck('id', 'day')
                    ->all();
        $bookings = Booking::with('slot')
                    ->whereDate('day', '>=', $now)
                    ->where('status', '!=', SD::BOOKING_CANCELLED)
                    ->get();
        $slots = Slot::all();

        $disabled = [];

        while($now->lte($last_day))
        {

            $is_holiday = isset($holidays[$now->toDateTimeString()]);
            // Disable day if it's full
            $is_full = (Booking::whereDate('day', $now)
                                ->where('status', '!=' , SD::BOOKING_CANCELLED)
                                ->count()
                                 >= count($slots)
                    // But not if it's the booking's day
                    && ! $booking?->day->eq($now) );

            $is_working_day = $working_days[((int) $now->format('w') - 1) % 7] == '1';

            ($is_holiday || $is_full || !$is_working_day)
            && array_push($disabled, $now->format('Y-m-d'));

            $now->addDay();
        }

        return [
            'first_day' => Carbon::today(),
            'last_day' => $last_day,
            'disabled' => $disabled,
            'bookings' => $bookings,
            'slots' => $slots,
        ];
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

    /**
     * Send reminder to client with booking happening within 20'
     *
     * Gets called every minute by job scheduler \App\Console\Kernel::schedule()
     */
    public static function send_reminder()
    {
        // All bookings happening within 20'
        $bookings = Booking::whereHas('slot', function(Builder $query) {
            // This time comparison works regardless of the Server's location
            // because Laravel is configured to use env('TIMEZONE'), which
            // is America/Argentina/Cordoba
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
