{{-- Update or insert booking --}}
<x-app-layout>

    <x-slot name="header">

      <h2 class="inline-block ml-2 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        @if(isset($booking))
          {{ __('Edit booking') }}
        @else
          {{ __('New booking') }}
        @endif
      </h2>

    </x-slot>

    <div class="py-12 space-y-6">

      <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

        <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">

        <livewire:booking-form :booking="$booking"
          :therapist="$therapist ?? $booking->therapist" />

        </div>
      </div>

    </div>

</x-app-layout>
