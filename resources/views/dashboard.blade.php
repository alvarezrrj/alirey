<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                  <h3>
                    {{ __("Upcoming session") }}
                  </h3>

                  @if(!$booking && !$is_admin)

                    <p class="mt-6">
                      {{ __('There\'s nothing over here')}}
                      <x-primary-button class="mt-6">
                        {{ __('Book yourself a slot') }}
                      </x-primary-button>
                    </p>

                  @elseif(!$is_admin)


                  {{-- TO DO
                  Remove this text and stick the table from bookings.show in its place
                  If is_admin, display action buttons --}}
                  <p class="mt-6">
                    {{ __('Your next session is on') }}
                    {{ $booking->day->locale('es')->dayName }}
                    {{ $booking->day->day }}
                    @switch($booking->day->day)
                      @case(1)
                        {{ __('st')}}
                        @break
                      @case(2)
                        {{ __('nd')}}
                        @break
                      @case(3)
                        {{ __('rd')}}
                        @break
                      @default
                        {{ __('th')}}
                    @endswitch

                    {{ $booking->day->monthName }}
                    {{ __(' ')}}
                    {{ $booking->day->year }}
                    {{ __('at') }}
                    {{ $booking->slot->start->format('H:i') }}
                  </p>

                  @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
