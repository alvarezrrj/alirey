{{-- Update or insert booking --}}
<x-app-layout>

    <x-slot name="header">

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        @if(isset($booking))
          {{ __('Edit booking') }}
        @else
          {{ __('New booking') }}
        @endif
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