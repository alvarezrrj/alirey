{{-- Booking details --}}

<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
      <a href="{{ route('bookings.index') }}">
          <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
      </a>

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Booking details') }}
      </h2>
    </div>
  </x-slot>

  <livewire:booking 
    :booking="$booking"
    :sd="$sd"/>


</x-app-layout>