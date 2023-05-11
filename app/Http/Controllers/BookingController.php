<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\SingleSlotHolidayRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Code;
use App\Models\Holiday;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
use App\SD\SD;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        session()->flash('message', 'Booking saved.');

        return redirect(route('bookings.show', $booking));

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
            'codes' => Code::all(),
            'data' => $this->getData(),
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
    public function destroy(Booking $booking): RedirectResponse
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return Redirect::back();
    }

    static function getData($booking = null)
    {
        $admin = User::where('role_id', Role::where('role', SD::admin)->first()->id)->first();
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
