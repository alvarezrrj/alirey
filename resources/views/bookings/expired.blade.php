<x-app-layout>

  <x-slot name="header">

    <h2 class="inline-block text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Checkout') }}
    </h2>

  </x-slot>

  <div class="py-12 space-y-6">
    <div class="mx-auto space-y-6 text-gray-900 max-w-7xl sm:px-6 lg:px-8 dark:text-gray-100">

      <x-alert-error key="overlap" />

      <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg ">

        <h3 class="text-lg font-semibold">
          {{ __('Booking expired') }}
        </h3>

        <div class="max-w-lg space-y-6 sm:pl-8">

          <div class="grid grid-cols-5 mt-6">
            <div class="grid col-span-1 border-l-2 place-items-center border-orange">
              <x-bi-hourglass-bottom height="32" width="32"/>
            </div>
            <p class="col-span-4">
              {{ __('Woops! It seems you have ran out of time to pay for this booking. But don\'t worry, you can') }}
              <a href="{{ route('bookings.create', $therapist) }}" class="underline text-brown">{{ __('get yourself another slot') }}</a>.
            </p>
          </div>

        </div>

      </div>

    </div>
  </div>

</x-app-layout>
