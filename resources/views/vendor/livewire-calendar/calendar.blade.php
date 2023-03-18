<div
    @if($pollMillis !== null && $pollAction !== null)
        wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
    @elseif($pollMillis !== null)
        wire:poll.{{ $pollMillis }}ms
    @endif
>
    <div>
        @includeIf($beforeCalendarView)
    </div>

    <div class="flex sm:rounded-lg overflow-hidden shadow">
        <div class="overflow-x-auto w-full">
            <div class="min-w-full overflow-hidden">

                <div class="w-full flex flex-row">
                    @foreach($monthGrid->first() as $day)
                        @include($dayOfWeekView, ['day' => $day])
                    @endforeach
                </div>

                @foreach($monthGrid as $week)
                    <div class="w-full flex flex-row">
                        @foreach($week as $day)
                            @include($dayView, [
                                    'componentId' => $componentId,
                                    'day' => $day,
                                    'dayInMonth' => $day->isSameMonth($startsAt),
                                    'isToday' => $day->isToday(),
                                    'events' => $getEventsForDay($day, $events),
                                ])
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div>
        @includeIf($afterCalendarView)
    </div>

    {{-- 
  <div x-data="{ open_modal: @entangle('open_modal') }"
  x-init="$watch('open_modal', value => {
    $dispatch('open-modal', 'booking-details-modal');
  })"
  x-on:click="$dispatch('open-modal', 'booking-details-modal');"
  class="hidden"
  >
  </div>
  --}}

  {{-- Booking details modal --}}
  <x-modal name="booking-details-modal" focusable>
      <div class="p-6 text-gray-900 dark:text-gray-100">
        <h2 class="text-lg font-semibold">
            {{ __('Booking details') }}
        </h2>

        <p class="mt-6">
          {{ $modal_data?->user->firstName }} {{ $modal_data?->user->lastName }}
        </p>
        <p class="mt-3">
          {{ $modal_data?->day?->format('d/m/Y') }}
        </p>
        <p class="mt-3">
          {{ $modal_data?->slot->start?->format('H:i') }}
        </p>

        <div class="mt-6 flex justify-between">
            <x-secondary-button 
              type="button"
              x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>

            <a href="{{ route('bookings.show', $modal_data || 0) }}">
              <x-primary-button 
                class="ml-3"
                >
                {{ __('View booking') }}
              </x-primary-button>
            </a>
        </div>

      </div>
  </x-modal>{{-- End Booking details modal --}}

</div>
