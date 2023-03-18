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
        return Booking::join('slots', 'slots.id', '=', 'bookings.slot_id')->orderBy('slots.start')->select('bookings.*')->get()

            ->map(function (Booking $booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->user->firstName ?? __('Holiday'),
                'description' => $booking->slot->start->format('H:i'),
                'date' => $booking->day
            ];
        });
        /*
        return collect([
            [
                'id' => 1,
                'title' => 'Breakfast',
                'description' => 'Pancakes! 🥞',
                'date' => Carbon::today(),
            ],
            [
                'id' => 2,
                'title' => 'Meeting with Pamela',
                'description' => 'Work stuff',
                'date' => Carbon::tomorrow(),
            ],
        ]);
        */
    }

    public function onEventClick($id)
    {
        $this->modal_data = Booking::find($id);
        $this->open_modal = !$this->open_modal;
    }
}
