<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\SD\SD;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingsTable extends Component
{
    use AuthorizesRequests;

    public $is_admin;

    public $filters = [
        SD::BOOKING_PENDING,
        SD::BOOKING_COMPLETED,
        SD::BOOKING_CANCELLED
    ];

    public $filter = '';

    public function mount()
    {
        $this->is_admin = Auth::user()->isAdmin();
    }

    public function render()
    {
        $bookings = Booking::where('is_booking', true)->latest('day');

        // If user is not admin, show only their bookings
        $bookings = $this->is_admin
        ? $bookings
        : $bookings->where('user_id', Auth::user()->id);

        $bookings = $this->filter 
        ? $bookings->where('status', $this->filter)->get()
        : $bookings->get();

        return view('livewire.bookings-table', [
            'bookings' => $bookings,
        ]);
    }

    public function filter($filter)
    {
        $this->$filter = $filter;
    }

    public function delete(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();
    }
}
