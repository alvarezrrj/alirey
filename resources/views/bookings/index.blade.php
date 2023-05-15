{{-- Bookings table --}}
<x-app-layout>

    <x-slot name="header">

      <div class="flex justify-between">
        <h2 class="inline-block text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Bookings') }}
        </h2>

        <a
          aria-label="{{ __('New booking') }}"
          data-tooltip="{{ __('New booking') }}"
          data-placement="left"
          href="{{ route('bookings.create', $therapist) }}">
          <x-primary-button >
            <x-antdesign-plus-o width="22" height="22"/>
          </x-primary-button>
        </a>
      </div>
    </x-slot>

    <div class="py-12 space-y-6">

        <livewire:bookings-table />

    </div>

</x-app-layout>
