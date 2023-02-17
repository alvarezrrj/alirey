<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateBookingRequest;
use App\Models\Booking;
use App\Models\Code;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
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

        //$request->session()->flash('message', 'Booking saved.');

        // TO DO 
        // Schedule booking deletion: delete it if it has a pref_id and it is
        // expired

        return redirect()->route('user.bookings.checkout', $booking);
    }
    
    public function checkout(Booking $booking)
    {
        $admin = User::where('role_id', Role::where('role', SD::admin)->first()->id)->first();
        $preference_id = MercadoPagoController::createPreference(
            $booking, $admin->config->price
        );

        // MercadoPagoController returns false if a preference is expired 
        // (10' passed ) since its creation, and deletes the booking.
        if(! $preference_id)
        {
            return view('bookings.expired');
        }

        // Create MP preference

        return view('bookings.checkout', [
            'booking' => $booking,
            'price' => $admin->config->price,
            'preference_id' => $preference_id
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

    public function confirmation($booking)
    {

    }

    public function failure($booking)
    {

    }
}
