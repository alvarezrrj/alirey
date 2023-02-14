{{-- Bookings table --}}
<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New booking') }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-6">

      <livewire:booking-form 
        :codes="$codes"
        :booking="$booking"
        :data="$data"
      />

    </div>

</x-app-layout>