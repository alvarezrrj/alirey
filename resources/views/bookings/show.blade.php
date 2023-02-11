{{-- Booking details --}}

<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
      <a href="{{ route('bookings.index') }}">
          <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
      </a>

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Booking details') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <div class="max-w-xl sm:pl-8">

          <h3 class="text-gray-900 dark:text-gray-100 font-bold">
            {{ __('Booking number') }}:&nbsp;{{ $booking->id }}
          </h3>

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
                  @if ($booking->payment->status == $payment_mp
                    || $booking->payment->status == $payment_cash)
                    {{ __('Paid')}} -
                  @endif
                  {{ __($booking->payment->status) }}
                </td>
              </tr>

              @if ($booking->payment->status == $payment_refunded)
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

          <div class="flex justify-between mt-6">

            @if ($booking->payment->status == $payment_mp)
              <form 
                action="{{ route('booking.refund', $booking->id) }}" 
                method="POST">
                @csrf
                @method('patch')
              <x-danger-button 
                class="h-full" 
                type="submit">
                {{ __('Refund') }}
              </x-danger-button>
            </form>
            @elseif ($booking->payment->status == $payment_pending)
              <form 
                action="{{ route('booking.paid', $booking->id) }}" 
                method="POST">
                @csrf
                @method('patch')
              <x-danger-button 
                class="h-full" 
                type="submit">
                {{ __('Confirm payment') }}
              </x-danger-button>
            </form>
            @else
            {{-- Just a placeholder to keep the following buttons pushed to the
               right --}}
              <div></div>
            @endif

            <div>

              @if($booking->status === $booking_pending)
                <form 
                  action="{{ route('booking.complete', $booking->id) }}" 
                  class="inline-block w-full sm:w-auto"
                  method="POST">
                  @csrf
                  @method('patch')
                  <x-secondary-button class="w-full">
                  {{ __('Mark as completed') }}
                  </x-secondary-button>
                </form>
              @endif

              @unless($booking->status === $booking_completed)
                <a href="{{ route('bookings.edit', $booking->id) }}">
                  <x-primary-button class="w-full sm:w-auto">
                    {{ __('Edit') }}
                  </x-primary-button>
                </a>
              @endunless

            </div>

          </div>

        </div>

      </div>
    </div>


  </div>

  @push('libraries')
    <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"></script>
    <script src="{{ Vite::asset('resources/libraries/notif/notif.js') }}"></script>
  @endpush

</x-app-layout>
{{--
    navigator.permissions.query({name: 'clipboard-write'})
    .then(res => {
        if (res.state == 'granted') {
        }
    })
--}}
