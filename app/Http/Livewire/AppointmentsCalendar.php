<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\Models\Slot;
use Illuminate\Support\Collection;
use Rabol\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AppointmentsCalendar extends LivewireCalendar
{

    public Booking $modal_data;
    public $open_modal = false;

    public function events(): Collection
    {
        return Booking::whereDate('day', '>', $this->gridStartsAt)
            ->whereDate('day', '<', $this->gridEndsAt)
            ->join('slots', 'slots.id', '=', 'bookings.slot_id')
            ->orderBy('slots.start')
            ->select('bookings.*')
            ->get()
            ->map(function (Booking $booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->user->firstName ?? __('Holiday'),
                'description' => $booking->slot->start->format('H:i'),
                'date' => $booking->day
            ];
        });
    }
}
