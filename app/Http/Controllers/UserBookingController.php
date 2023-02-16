<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateBookingRequest;
use App\Models\Booking;
use App\Models\Code;
use App\Models\Payment;
use App\SD\SD;
use Illuminate\Http\Request;

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

        $request->session()->flash('message', 'Booking saved.');

        return redirect(route('user.bookings.show', $booking));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
