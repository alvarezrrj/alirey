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
          {{ __('Purchase details') }}
        </h3>

        <div class="max-w-lg sm:pl-8 space-y-6">

          <p class="mt-6">
            {{ __('Bioconstelation session - 1.5 hours') }}
          </p>
          <hr>
          <div class="grid grid-cols-1 sm:grid-cols-2 grid-rows-3 sm:grid-rows-2">
            <p class="flex justify-between sm:justify-start">
              <span>
                {{ __('Date') }}:
              </span>
              <span class="ml-3">
                {{ $booking->day->format('d/m/Y') }}
              </span>
            </p>
            <p class="sm:row-start-2 sm:row-end-3 col-start-1 flex justify-between sm:justify-start">
              <span>
                {{ __('Time') }}: 
              </span>
              <span class="ml-3">
                {{ $booking->slot->start->format('H:i') }}
              </span>
            </p>

            <p class="mt-3 sm:mt-0 sm:row-start-2 sm:col-start-2 font-semibold flex justify-between sm:justify-end">
              <span>
              {{ __('Price') }}: 
              </span>
              <span class="ml-3">
                $ {{ $price }}
              </span>
            </p>

          </div>

          <div class="flex justify-end">
            <div id="pay-btn" class="mt-6 min-w-[10rem] grid justify-items-stretch">

            </div>
          </div>

        </div>

      </div>

    </div>
  </div>

  @push('libraries')
    {{-- SDK MercadoPago.js --}} 
    <script src="https://sdk.mercadopago.com/js/v2"></script>
  @endpush

  @push('scripts')
    <script>
      const mp = new MercadoPago('{{ env("MP_PUB_KEY") }}', {
        locale: 'es-AR'
      });
    
      mp.checkout({
        preference: {
          id: '{{ $preference_id }}'
        },
        render: {
          container: '#pay-btn',
          label: "{{ __('Pay') }}",
        },
        theme: {
          elementsColor: '#F4903D',
          headerColor: '#E6D2AA'
        }
      });
    </script>
  @endpush

</x-app-layout>