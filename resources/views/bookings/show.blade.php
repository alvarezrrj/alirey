{{-- Booking details --}}

<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
      <a href="{{ route('bookings.index') }}">
        <x-secondary-button :small="true" class="inline-block" aria-label="back">
          👈
        </x-secondary-button>
      </a>

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Booking details') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <div class="max-w-xl pl-8">

          <h3 class="text-gray-900 dark:text-gray-100 font-bold">
            {{ __('Booking number') }}:&nbsp;{{ $booking->id }}
          </h3>

          <table class="text-gray-900 dark:text-gray-100 w-full">
            <tbody class="divide-y">
              {{--
                            <tr class="odd:bg-gray-100 dark:odd:bg-gray-900">
                                --}}
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
                <td class="text-right p-2 ">
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
                </td>
              </tr>
              <tr>
                <th class="text-left p-2 ">
                  {{ __('Date') }}
                </th>
                <td class="text-right p-2 ">
                  {{ $booking->day->format('m/d/Y') }}
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
            <x-danger-button>
              {{ __('Refund') }}
            </x-danger-button>
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
              <x-primary-button class="w-full sm:w-auto">
                {{ __('Edit') }}
              </x-primary-button>
            </div>
          </div>

        </div>

      </div>
    </div>


  </div>

  @push('libraries')
    <script src="https://code.jquery.com/jquery-3.6.3.slim.min.js" type="text/javascript"></script>
    <script src="{{ Vite::asset('resources/libraries/notif/notif.js') }}" type="text/javascript"></script>
  @endpush

</x-app-layout>
{{--
    navigator.permissions.query({name: 'clipboard-write'})
    .then(res => {
        if (res.state == 'granted') {
        }
    })
--}}
