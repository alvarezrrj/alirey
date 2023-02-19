<x-app-layout>

  <x-slot name="header">

    <h2 class="inline-block font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Checkout') }}
    </h2>

  </x-slot>

  <div class="py-12 space-y-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 text-gray-900 dark:text-gray-100">

      <x-alert-error key="overlap" />

      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <h3 class="font-semibold text-lg">
          {{ __('Payment failed') }}
        </h3>

        <div class="max-w-lg sm:pl-8 space-y-6">

          <div class="mt-6 grid grid-cols-5 gap-2">
            <div class="col-span-1 grid place-items-center border-l-2 border-orange">
              <x-bi-piggy-bank height="32" width="32"/>
            </div>
            <p class="col-span-4">
              {{ __('Woops! Something went wrong whilst trying to take that payment.') }}
            </p>
          </div>

        </div>

      </div>

    </div>
  </div>

</x-app-layout>