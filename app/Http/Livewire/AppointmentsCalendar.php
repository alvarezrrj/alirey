<?php

namespace App\Http\Livewire;

use App\Models\Booking;
use App\SD\SD;
use Illuminate\Support\Collection;
use Rabol\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class AppointmentsCalendar extends LivewireCalendar
{
    use AuthorizesRequests;

    public Carbon $today;

    public function afterMount($extras = [])
    {
        $this->today = Carbon::now()->locale($this->locale);
    }

    public function events(): Collection
    {
        return Booking::whereDate('day', '>=', $this->gridStartsAt)
            ->whereDate('day', '<', $this->gridEndsAt)
            ->where('status', '!=', SD::BOOKING_CANCELLED)
            ->join('slots', 'slots.id', '=', 'bookings.slot_id')
            ->orderBy('slots.start')
            ->select('bookings.*')
            ->get()
            ->map(function (Booking $booking) {
            return [
                'id' => $booking->id,
                'title' => $booking->user->firstName == Auth::user()->firstName
                ? __('Closed')
                : $booking->user->firstName,
                'description' => $booking->slot->start->format('H:i'),
                'date' => $booking->day
            ];
        });
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();
    }
}
