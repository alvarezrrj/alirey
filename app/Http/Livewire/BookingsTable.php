<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\SD\SD;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingsTable extends Component
{
    use AuthorizesRequests;

    public $is_admin;

    public $statuses = [
        SD::BOOKING_PENDING,
        SD::BOOKING_COMPLETED,
        SD::BOOKING_CANCELLED
    ];

    // Show all bookings by default
    public $status_filter = '';

    public $dates = [
        'Past' => '<=',
        'Future' => '>='
    ];

    // Show only future bookings by default
    public $date_filter = 'Future';

    public function mount()
    {
        $this->is_admin = Auth::user()->isAdmin();
    }

    public function render()
    {
        $bookings = Booking::where('is_booking', true)->oldest('day');

        // If user is not admin, show only their bookings
        $bookings = $this->is_admin
        ? $bookings
        : $bookings->where('user_id', Auth::user()->id);

        $bookings = $this->status_filter 
        ? $bookings->where('status', $this->status_filter)
        : $bookings;

        $bookings = $this->date_filter
        ? $bookings->whereDate('day', $this->dates[$this->date_filter], Carbon::now())
        : $bookings;



        return view('livewire.bookings-table', [
            'bookings' => $bookings->get(),
        ]);
    }

    public function status_filter($filter)
    {
        $this->status_filter = $filter;
    }

    public function date_filter($filter)
    {
        $this->date_filter = $filter;
    }

    public function delete(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();
    }
}
