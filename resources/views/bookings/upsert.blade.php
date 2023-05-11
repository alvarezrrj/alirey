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

      <livewire:booking-form :booking="$booking" />

    </div>

</x-app-layout>
