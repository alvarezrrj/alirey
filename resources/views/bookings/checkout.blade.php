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

        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold">
            {{ __('Purchase details') }}
          </h3>

          {{-- Countdown --}}
          <div
            class="px-2 bg-gray-100 rounded-md dark:bg-gray-600"
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

        <div class="max-w-lg space-y-6 sm:pl-8">

          <div class="flex items-center justify-between mt-6">
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
          <div class="grid grid-cols-1 grid-rows-3 sm:grid-cols-2 sm:grid-rows-2">
            <p class="flex justify-between sm:justify-start">
              <span>
                {{ __('Date') }}:
              </span>
              <span class="ml-3">
                {{ $booking->day->format('d/m/Y') }}
              </span>
            </p>
            <p class="flex justify-between col-start-1 sm:row-start-2 sm:row-end-3 sm:justify-start">
              <span>
                {{ __('Time') }}:
              </span>
              <span class="ml-3">
                {{ $booking->slot->start->format('H:i') }}
              </span>
            </p>

            <p class="flex justify-between mt-3 font-semibold sm:mt-0 sm:row-start-2 sm:col-start-2 sm:justify-end">
              <span>
              {{ __('Price') }}:
              </span>
              <span class="ml-3">
                $ {{ $price }}
              </span>
            </p>

          </div>

          <div class="flex justify-end mt-6">
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

      <div class="flex justify-between mt-6">
        <x-secondary-button
          x-data=""
          x-on:click="$dispatch('close')">
          {{ __('Cancel') }}
        </x-secondary-button>

        <form
          action="{{ route('bookings.destroy', $booking) }}"
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
      const mp = new MercadoPago('{{ config("mercadopago.mp_pub_key") }}', {
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
