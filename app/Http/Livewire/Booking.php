<?php

namespace App\Http\Livewire;

use App\Http\Controllers\GoogleCalController;
use App\Http\Controllers\MercadoPagoController;
use App\SD\SD;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Http\Request;

class Booking extends Component
{
    use AuthorizesRequests;

    public $is_admin;

    public $booking;
    public $paid_ammount;

    // Modal triggers
    public $payment_confirmed = false;
    public $cancelation_confirmed = false;
    public $completion_confirmed = false;

    public $alert = [];

    public $response;

    public $saved_to_google = false;

    public function mount()
    {
        $this->is_admin = Auth::user()->isAdmin();
    }

    public function render()
    {
        return view('livewire.bookings.booking');
    }

    public function paid()
    {
        $this->authorize('update', $this->booking);

        $this->booking->payment->status = SD::PAYMENT_CASH;
        $this->booking->payment->amount = $this->paid_ammount;

        $this->payment_confirmed = true;

        $this->booking->payment->save();
    }

    public function refund()
    {
        $this->authorize('refund', $this->booking);

        $response = MercadoPagoController::refund($this->booking->payment->mp_id);

        if($response['status'] != 'approved') {
           $this->alert['error'] = __('There was an error, please try again').' 😶‍🌫️';
        }
        else {
           $this->alert['message'] = __('Payment refunded'). ' 👍';

           $this->booking->payment->status = SD::PAYMENT_REFUNDED;
           $this->booking->payment->save();
        }
        $this->response = $response;
    }

    public function cancel()
    {
        $this->authorize('update', $this->booking);

        $this->booking->status = SD::BOOKING_CANCELLED;

        $this->cancelation_confirmed = true;

        $this->booking->save();

    }

    public function complete()
    {
        $this->authorize('update', $this->booking);

        $this->booking->status = SD::BOOKING_COMPLETED;

        $this->completion_confirmed = true;

        $this->booking->save();
    }

    public function addToGoogleCalendar(Request $request)
    {
        if ($request->user()->google_token) {
            GoogleCalController::store($this->booking);
            $this->saved_to_google = true;
            return;
        }
        return redirect()->route('google.calendar.auth', $this->booking);
    }
}
