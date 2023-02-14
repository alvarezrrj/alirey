<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Code;
use App\Models\Holiday;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use StaticDetails\SD;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**
         * TO DO
         *  pending, completed, cancelled
         */
        return view('bookings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bookings.create', [
            'booking' => null,
            'codes' => Code::all(),
            'data' => $this->getData(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookingRequest $request)
    {
        $validated_user = $request->safe()->only([
            'firstName',
            'lastName',
            'email',
            'code_id',
            'phone'
        ]);
        $validated_booking = $request->safe()->only([
            'virtual', 
            'day', 
            'slot_id'
        ]);

        // Find or create user
        $user = User::where('email', $validated_user['email'])->first();
        if (! $user)
        {
            $user = new User;
            $user->role_id = Role::where('role', SD::client)->first()->id;
            $user->firstName = $validated_user['firstName'];
            $user->lastName =  $validated_user['lastName'];
            $user->code_id = $validated_user['code_id'];
            $user->phone = $validated_user['phone'];
            $user->email = $validated_user['email'];
            $user->password = Hash::make(uniqid('', true));

            $user->save();
        }

        // Create pending payment
        $payment = Payment::create([
            'amount' => 0,
            'status' => SD::PAYMENT_PENDING,
        ]);
        $validated_booking['payment_id'] = $payment->id;

        $validated_booking['status'] = SD::BOOKING_PENDING;

        // Store booking
        $booking = $user->bookings()->create($validated_booking);

        return redirect(route('bookings.show', $booking));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return view('bookings.show', [
            'booking' => $booking,
            'payment_refunded' => SD::PAYMENT_REFUNDED,
            'payment_mp' => SD::PAYMENT_MP,
            'payment_cash' => SD::PAYMENT_CASH,
            'payment_pending' => SD::PAYMENT_PENDING,
            'booking_pending' => SD::BOOKING_PENDING,
            'booking_completed' => SD::BOOKING_COMPLETED,
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

        $now = Carbon::today();
        $last_day = Auth::user()->config->allways_open 
        ? Carbon::today()->addDays(Auth::user()->config->anticipation)
        : Carbon::create(Auth::user()->config->open_until);
        $holidays = Holiday::where('user_id', Auth::user()->id)
                    ->whereDate('day', '>=', $now)
                    ->whereDate('day', '<=', $last_day) 
                    ->get()
                    ->pluck('id', 'day')
                    ->all();
        $bookings = Booking::with('slot')
                    ->whereDate('day', '>=', $now)
                    ->get();
        $slots = Slot::all();

        $days = [];

        while($now->lte($last_day)) 
        {
            $is_holiday = isset($holidays[$now->toDateTimeString()]);
            // Disable day if it's full
            $is_full = (Booking::whereDate('day', $now)->count() >= count($slots)
                    // But not if it's the booking's day
                    && ! $booking->day->eq($now) );

            array_push($days, [
                'value' => $now->toDateString(),
                'display' => $now->format('l j \d\e F Y'),
                'disabled' => $is_holiday || $is_full,
            ]);

            $now->addDay();
        }

        return view('bookings.update', [
            'booking'  => $booking,
            'codes'    => Code::all(),
            'bookings' => $bookings,
            'days'     => $days,
            'slots'    => $slots,
            'holidays' => $holidays,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookingRequest $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
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

        return redirect(route('bookings.show', $booking->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        //
    }

    public function complete(int $id)
    {
        $booking = Booking::find($id);

        $this->authorize('update', $booking);

        $booking->status = SD::BOOKING_COMPLETED;

        $booking->save();

        return back();
    }

    public function paid(int $id)
    {
        $booking = Booking::find($id);

        $this->authorize('update', $booking);

        $booking->payment->status = SD::PAYMENT_CASH;

        $booking->payment->save();

        return back();
    }

    protected function getData($booking = null)
    {
        $working_days = Auth::user()->config->working_days;
        $now = Carbon::today();
        $last_day = Auth::user()->config->allways_open 
        ? Carbon::today()->addDays(Auth::user()->config->anticipation)
        : Carbon::create(Auth::user()->config->open_until);
        $holidays = Holiday::where('user_id', Auth::user()->id)
                    ->whereDate('day', '>=', $now)
                    ->whereDate('day', '<=', $last_day) 
                    ->get()
                    ->pluck('id', 'day')
                    ->all();
        $bookings = Booking::with('slot')
                    ->whereDate('day', '>=', $now)
                    ->get();
        $slots = Slot::all();

        $disabled = [];

        while($now->lte($last_day)) 
        {

            $is_holiday = isset($holidays[$now->toDateTimeString()]);
            // Disable day if it's full
            $is_full = (Booking::whereDate('day', $now)->count() >= count($slots)
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
}
