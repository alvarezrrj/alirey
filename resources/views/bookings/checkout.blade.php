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

        <div class="flex justify-between items-center">
          <h3 class="font-semibold text-lg">
            {{ __('Purchase details') }}
          </h3>

          {{-- Countdown --}}
          <div 
            class="bg-gray-100 dark:bg-gray-600 rounded-md px-2"
            x-data="{ 'expiresIn': {{ $expires_in }} }"
            x-init="
              setInterval(() => expiresIn--, 1000);
              $watch('expiresIn', value => value == 0 && location.reload())">
            <span x-text="~~(expiresIn / 60)"></span> :
            <span x-text="
              expiresIn < 0 
              ? 0
              : String(expiresIn % 60).length < 2 
                ? '0' + (expiresIn % 60)
                : expiresIn % 60"></span>
          </div>
        </div>

        <div class="max-w-lg sm:pl-8 space-y-6">

          <div class="mt-6 flex justify-between items-center">
            <p class="">
              {{ __('Bioconstelation session - 1.5 hours') }}
            </p>
            {{-- Delete booking --}}
            <a 
              class="cursor-pointer"
              data-tooltip="{{ __('Delete booking') }}"
              data-placement="left"
              x-data=""
              x-on:click="$dispatch('open-modal', 'delete-booking-modal')"
              >
              <x-bi-trash 
                aria-label="{{ __('Delete booking') }}"
                class="text-red-600"
                height="18"
                width="18"
                />
            </a>
          </div>

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

          <div class="mt-6 flex justify-end">
            {{-- MP pay button container --}}
            <div id="pay-btn" class="min-w-[10rem] grid justify-items-stretch">
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>

  {{-- Delete booking modal --}}
  <x-modal name="delete-booking-modal" focusable>
    <div class="p-6 text-gray-900 dark:text-gray-100" >
      <h2 class="text-lg font-semibold">
        {{ __('Delete booking') }}
      </h2>

      <p class="mt-6">
        {{ __('Are you sure you want to delete this booking? If you do, you\'ll loose your slot and have to re-start the reservation process.') }}
      </p>

      <div class="mt-6 flex justify-between">
        <x-secondary-button
          x-data="" 
          x-on:click="$dispatch('close')">
          {{ __('Cancel') }}
        </x-secondary-button>

        <form 
          action="{{ route('user.bookings.destroy', $booking) }}"
          method="POST" >
          @csrf
          @method('delete')
          <x-danger-button type="submit">
            {{ __('Confirm') }}
          </x-danger-button>
        </form>
      </div>

    </div>
  </x-modal>{{-- End Delete booking modal --}}

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