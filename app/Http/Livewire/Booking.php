<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use StaticDetails\SD;

class Booking extends Component
{
    use AuthorizesRequests;
    
    public $is_admin;

    public $booking;
    public $sd;
    public $paid_ammount;

    // Modal triggers
    public $payment_confirmed = false;
    public $cancelation_confirmed = false;
    public $completion_confirmed = false;

    public function mount()
    {
        $this->is_admin = Auth::user()->isAdmin();
    }

    public function render()
    {
        return view('livewire.booking');
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
}
