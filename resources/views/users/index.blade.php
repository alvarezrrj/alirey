{{-- Bookings table --}}
<x-app-layout>

  <x-slot name="header">

    <div class="flex justify-between">
      <h2 class="inline-block font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('Users') }}
      </h2>

      <a
        aria-label="{{ __('New user') }}"
        data-tooltip="{{ __('New user') }}"
        data-placement="left"
        href="{{ route('dashboard') }}">
        <x-primary-button >
          <x-antdesign-plus-o width="22" height="22"/>
        </x-primary-button>
      </a>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">

      <livewire:users-table />

  </div>

</x-app-layout>
