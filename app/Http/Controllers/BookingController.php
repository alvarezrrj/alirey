<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Code;
use App\Models\Holiday;
use App\Models\Slot;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
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
        return view('bookings.upsert', [
            'booking' => null,
            'codes' => Code::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        // Retrieve last day
        $last_day = Auth::user()->config->allways_open 
        ? Carbon::today()->addDays(Auth::user()->config->anticipation)
        : Carbon::create(Auth::user()->config->open_until);

        $now = Carbon::today();
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

        return view('bookings.upsert', [
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        
        $validated_user = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->user()->id)
            ],
            'code_id' => 'required|int',
            'phone' => 'required|numeric',
        ]);
        $validated_payment = $request->validate([
            'amount' => 'required|int|min:0',
        ]);
        
        $last_day = $request->user()->config->allways_open 
        ? Carbon::today()->addDays($request->user()->config->anticipation)
        : Carbon::create($request->user()->config->open_until);
        $last_day_string = $last_day->toDateString();

        $validated_booking = $request->validate([
            'day' => "required|date|after_or_equal:today|before_or_equal:$last_day_string",
            'slot_id' => [
                'required',
                'int',
                Rule::in(Slot::pluck('id')->all())
            ]
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
}
