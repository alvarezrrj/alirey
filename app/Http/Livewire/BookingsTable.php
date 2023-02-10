<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use StaticDetails\SD;

class BookingsTable extends Component
{
    use AuthorizesRequests;

    public $filters = [
        SD::BOOKING_PENDING,
        SD::BOOKING_COMPLETED,
        SD::BOOKING_CANCELLED
    ];

    public $filter = '';

    public function render()
    {
        $bookings = Booking::where('is_booking', true);
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
