{{-- Because she competes with no one, no one can compete with her. --}}
<div class="py-12 space-y-6">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    <x-alert-message key="message" />

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

      <div class="max-w-xl sm:pl-8">

        <div class="flex justify-between">
          <h3 class="text-gray-900 dark:text-gray-100 font-bold">
            {{ __('Booking number') }}:&nbsp;{{ $booking->id }}
          </h3>
          @unless($booking->status === SD::BOOKING_COMPLETED
               || $booking->status === SD::BOOKING_CANCELLED
               || ! Auth::user()->isAdmin())
            <a href="{{ route('bookings.edit', $booking) }}">
              <x-primary-button class="w-full sm:w-auto">
                <x-antdesign-edit-o width="18" height="18"/>&nbsp;
                {{ __('Edit') }}
              </x-primary-button>
            </a>
          @endunless
        </div>

        <table class="text-gray-900 dark:text-gray-100 w-full mt-6">
          <tbody class="divide-y">
            <tr>
              <th class="text-left p-2  ">
                {{ __('Status') }}
              </th>
              <td class="text-right p-2  ">
                {{ __($booking->status) }}
              </td>
            </tr>

            <tr>
              <th class="text-left p-2 ">
                {{ __('Name') }}
              </th>
              <td class="text-right p-2 ">
                {{ $booking->user->firstName }}&nbsp;
                {{ $booking->user->lastName }}
              </td>
            </tr>

            <tr>
              <th class="text-left p-2 ">
                {{ __('Email') }}<br>
                <small class="text-gray-500 dark:text-gray-500 font-normal">
                  {{ __('Tap to message') }}
                </small>
              </th>
              <td class="text-right p-2 ">
                <a href="mailto:{{ $booking->user->email }}">
                  {{ $booking->user->email }}
                </a>
              </td>
            </tr>

            <tr>
              <th class="text-left p-2 ">
                {{ __('Phone') }}<br>
                <template x-data="" x-ref="tap" x-if="'clipboard' in navigator">
                  <small class="text-gray-500 dark:text-gray-500 font-normal">
                    {{ __('Tap to copy') }}
                  </small>
                </template>
              </th>
              <td class="text-right p-2">
                <div class="flex items-center justify-end h-full">
                  <span 
                    x-data="" 
                    x-init="$refs.tap ?
                      $el.style.cursor = 'pointer' :
                      ''" 
                    x-ref="number"
                    x-on:click="
                      navigator.clipboard?.writeText($el.innerText)
                      .then(notif.i(
                          '{{ __('Copied') }}', 
                          true, 
                          3500)
                      )
                    ">
                    +{{ $booking->user->code->code }}&nbsp;
                    {{ $booking->user->phone }}
                  </span>
                    <a href="https://api.whatsapp.com/send/?phone={{ $booking->user->code->code }}{{ $booking->user->phone }}&text&type=phone_number&app_absent=1"
                      class="ml-2"
                      title="{{ __('Call on Whatsapp') }}">
                      <x-primary-button :small="true">
                        <x-antdesign-whats-app-o width="22" height="22"/>
                      </x-primary-button>
                    </a>
                </div>
              </td>
            </tr>

            <tr>
              <th class="text-left p-2 ">
                {{ __('Booking type') }}
              </th>
              <td class="text-right p-2 ">
                @if($booking->virtual) {{ __('Virtual') }}
                @else {{ __('In-person') }}
                @endif
              </td>
            </tr>

            <tr>
              <th class="text-left p-2 ">
                {{ __('Date') }}
              </th>
              <td class="text-right p-2 ">
                {{ $booking->day->format('d/m/Y') }}
              </td>
            </tr>

            <tr>
              <th class="text-left p-2 ">
                {{ __('Start time') }}
              </th>
              <td class="text-right p-2 ">
                {{ $booking->slot->start->format('h:i') }}
              </td>
            </tr>

            <tr>
              <th class="text-left p-2 ">
                {{ __('Payment status') }}
              </th>
              <td class="text-right p-2 ">
                @if ($booking->payment->status == SD::PAYMENT_MP
                  || $booking->payment->status == SD::PAYMENT_CASH)
                  {{ __('Paid')}} -
                @endif
                {{ __($booking->payment->status) }}
              </td>
            </tr>

            @if ($booking->payment->status == SD::PAYMENT_REFUNDED)
              <tr>
                <th class="text-left p-2 ">
                  {{ __('Refund date') }}
                </th>
                <td class="text-right p-2 ">
                  {{ $booking->payment->updated_at->format('m/d/Y') }}
                </td>
              </tr>
            @endif

            <tr>
              <th class="text-left p-2 ">
                {{ __('Paid ammount') }}
              </th>
              <td class="text-right p-2 ">
                $ {{ $booking->payment->amount }}
              </td>
            </tr>

          </tbody>
        </table>

        {{-- Buttons --}}
        <div class="grid sm:grid-cols-3 grid-rows-2 mt-6 gap-x-2 gap-y-4">

          @if ($booking->payment->status == SD::PAYMENT_MP)
            <x-danger-button 
              wire:click="refund">
              {{ __('Refund') }}
            </x-danger-button>
          @elseif ($booking->payment->status == SD::PAYMENT_PENDING)
            <x-danger-button 
              x-data=""
              x-on:click.prevent="$dispatch('open-modal', 'payment-modal')">
              {{ __('Confirm payment') }}
            </x-danger-button>
          @else 
            {{-- placeholder --}}
            <div></div>
          @endif

          @if($booking->status === SD::BOOKING_PENDING)
            <x-secondary-button 
              x-data=""
              x-on:click.prevent="$dispatch('open-modal', 'cancelation-modal')">
              {{ __('Cancel booking') }}
            </x-secondary-button>

            <x-primary-button 
              x-data=""
              x-on:click.prevent="$dispatch('open-modal', 'completion-modal')">
              {{ __('Mark as completed') }}
            </x-primary-button>
          @endif

        </div>{{-- End Buttons --}}

        {{-- Payment confirmation modal --}}
        <x-modal name="payment-modal" focusable>
            <div 
              class="p-6 text-gray-900 dark:text-gray-100"
              x-data="{
                payment_confirmed: @entangle('payment_confirmed')
              }"
              x-init="$watch('payment_confirmed', value => {
                if(value) $dispatch('close');
              })"
              >
                <h2 class="text-lg">
                    {{ __('Confirm payment') }}
                </h2>

                <form wire:submit.prevent="paid">

                  <x-input-label 
                    class="mt-6" 
                    for="ammount" 
                    :value="__('Paid ammount')" />
                  <div class="mt-2 w-full grid grid-cols-12 items-center">
                    <span class="col-span-1 text-center"> $ </span>
                    <x-text-input 
                      class="col-span-11"
                      id="ammount"
                      inputmode="numeric"
                      required
                      type=number 
                      wire:model="paid_ammount"
                      />
                  </div>

                  <div class="mt-6 flex justify-between">
                      <x-secondary-button 
                        type="button"
                        x-on:click="$dispatch('close')">
                          {{ __('Cancel') }}
                      </x-secondary-button>

                      <x-danger-button 
                        class="ml-3"
                        type="submit"
                        >
                        {{ __('Confirm') }}
                      </x-danger-button>
                  </div>

                </form>
            </div>
        </x-modal>{{-- End Payment confirmation modal --}}

        {{-- Cancelation modal --}}
        <x-modal name="cancelation-modal" focusable>
          <div 
            class="p-6 text-gray-900 dark:text-gray-100"
            x-data="{
              cancelation_confirmed: @entangle('cancelation_confirmed')
            }"
            x-init="$watch('cancelation_confirmed', value => {
              if(value) $dispatch('close');
            })"
            >
              <h2 class="text-lg">
                  {{ __('Cancel booking') }}
              </h2>

              <p class="mt-6">
                {{ __('Booking number') }}: {{ $booking->id }}
              </p>
              <p>
                {{ __('Name') }}: {{ $booking->user->firstName }}
              <p class="mt-3">
                {{ __('Are you sure you wish to cancel this booking?')}}<br>
                {{ __('Once you do this, you won\'t be able to edit this booking.') }}
              </p>

              <div class="mt-6 flex justify-between">
                <x-secondary-button 
                  type="button"
                  x-on:click="$dispatch('close')">
                    {{ __('Back') }}
                </x-secondary-button>

                <x-danger-button 
                  class="ml-3"
                  type="submit"
                  wire:click="cancel"
                  >
                  {{ __('Confirm') }}
                </x-danger-button>
              </div>
          </div>
        </x-modal>{{-- End Cancelation modal --}}

        {{-- Completion modal --}}
        <x-modal name="completion-modal" focusable>
            <div 
              class="p-6 text-gray-900 dark:text-gray-100"
              x-data="{
                completion_confirmed: @entangle('completion_confirmed')
              }"
              x-init="$watch('completion_confirmed', value => {
                if(value) $dispatch('close');
              })"
              >
                <h2 class="text-lg">
                    {{ __('Complete booking') }}
                </h2>

              <p class="mt-6">
                {{ __('Booking number') }}: {{ $booking->id }}
              </p>
              <p>
                {{ __('Name') }}: {{ $booking->user->firstName }}
              <p class="mt-3">
                {{ __('Are you sure you wish to mark this booking as completed?')}}<br>
                {{ __('Once you do this, you won\'t be able to edit this booking.') }}
              </p>

              <div class="mt-6 flex justify-between">
                  <x-secondary-button 
                    type="button"
                    x-on:click="$dispatch('close')">
                      {{ __('Cancel') }}
                  </x-secondary-button>

                  <x-primary-button 
                    class="ml-3"
                    wire:click="complete"
                    >
                    {{ __('Confirm') }}
                  </x-primary-button>
              </div>

            </div>
        </x-modal>{{-- End Completion modal --}}


      </div>

    </div>
  </div>

@push('libraries')
  <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"></script>
  <script src="{{ Vite::asset('resources/libraries/notif/notif.js') }}"></script>
@endpush

</div>
