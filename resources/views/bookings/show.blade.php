{{-- Booking details --}}
<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
      {{-- Redirect back to bookings.index unless user comes 
           from bookings.create --}}
      @unless( substr_count(url()->previous(), 'create') 
            || substr_count(url()->previous(), 'checkout')
            || substr_count(url()->previous(), 'confirmation')
            || url()->previous() == url()->current())
        <a href="{{ url()->previous() }}">
            <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
        </a>
      @endunless

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Booking details') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      <x-alert-message key="message" />

      <div class="p-4 sm:p-8 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <livewire:booking 
          :booking="$booking"
          />

      </div>

    </div>
  </div>

</x-app-layout>