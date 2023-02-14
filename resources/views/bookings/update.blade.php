{{-- Edit booking --}}

<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
      @if(isset($booking))
      <a href="{{ route('bookings.show', $booking->id) }}">
          <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
      </a>
      @endif

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        @if(isset($booking))
          {{ __('Edit booking') }}
        @else
          {{ __('New booking') }}
        @endif
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <x-alert-error key="overlap" />
      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <div class="max-w-xl sm:pl-8 space-y-6">

          @if(isset($booking))
            <h3 class="text-gray-900 dark:text-gray-100 font-bold">
              {{ __('Booking number') }}:&nbsp;{{ $booking->id }}
            </h3>
          @endif

          @php($action = isset($booking)
          ? route('bookings.update', $booking->id)
          : route('bookings.store')
          )
          <form action={{ $action }} method="POST" class="">
            @csrf
            @method(isset($booking) ? 'patch' : 'post')

            <input type="hidden" value="{{ $booking?->user->id }}" name="user_id">
            <input type="hidden" value="{{ $booking?->id }}" name="booking_id">

            <x-input-label for="firstName" :value="__('Name')" />
            <x-text-input 
              id="firstName" 
              name="firstName" 
              type="text" 
              class="mt-1 block w-full" 
              :value="old('firstName', $booking?->user->firstName)" 
              required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('firstName')" />

            <x-input-label class="mt-6" for="lastName" :value="__('Last Name')" />
            <x-text-input 
              id="lastName" 
              name="lastName" 
              type="text" 
              class="mt-1 block w-full" 
              :value="old('lastName', $booking?->user->lastName)" 
              required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('lastName')" />

            <x-input-label class="mt-6" for="email" :value="__('Email')" />
            <x-text-input 
              id="email" 
              name="email" 
              type="text" 
              class="mt-1 block w-full" 
              :value="old('email', $booking?->user->email)" 
              required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            {{-- Telephone Number --}}
            <div class="mt-6">
                <x-input-label for="phone" :value="__('Telephone Number')" />
                <div class="flex">
                    <x-code-select :codes="$codes" :value="old('code_id', $booking?->user->code_id)" label="Country code" id="code" name="code_id" rounded="rounded-l-md"
                    class="flexselect inline-block mt-1 w-2/5" required />
                    <x-text-input id="phone" inputmode="numeric" rounded="rounded-r-md"
                    class="inline-block mt-1 w-3/5" type="text" name="phone" 
                    :value="old('phone', $booking?->user->phone )" required />
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                <x-input-error :messages="$errors->get('code_id')" class="mt-2" />
            </div>

            {{-- Booking type --}}
            <fieldset class="mt-6">
              <legend class="font-medium text-sm text-gray-800 dark:text-gray-200">
                {{ __('Booking type') }}
              </legend>
              <x-radio-input 
                id="virtual"
                name="virtual" 
                :checked="$booking->virtual"
                value="1" />
              <x-input-label 
                class="inline-block" 
                for="virtual" 
                :value="__('Virtual')" />
                <br>

              <x-radio-input
                id="in-person"
                name="virtual" 
                :checked="! $booking->virtual"
                value="0" />
              <x-input-label class="inline-block" 
                for="in-person" 
                :value="__('In-person')" />
            </fieldset>
            <x-input-error class="mt-2" :messages="$errors->get('type')" />

            {{-- Date and slot alpine object --}}
            <div class="flex flex-wrap w-full"
                x-data="{
                    slots: @js($slots),
                    bookings: @js($bookings),
                    day: @js($booking->day->toDateString()),
                    slot_id: @js($booking->slot->id),

                    get computedSlots() {
                      this.slots.forEach(s => {
                        if(this.bookings.some(b => 
                             b.id != {{ $booking->id }} 
                          && b.day == this.day 
                          && b.slot.id == s.id
                        )) 
                          s.disabled = true;
                        else
                          s.disabled = false;
                      });
                      return this.slots;
                    },
                }"
                >
                <div class="w-full sm:w-1/2">
                  <x-input-label for="day" :value="__('Date')" class="mt-6" />
                  <x-select-input id="day" 
                    x-model="day"
                    rounded="rounded-md sm:rounded-l-md sm:rounded-r-none"
                    class="inline-block mt-1 w-full" type="text" name="day" >

                    @foreach($days as $day)

                    <option value="{{ $day['value'] }}" 
                      @disabled($day['disabled'])
                      @selected($booking->day->toDateString() == $day['value'])>
                      {{ $day['display'] }}
                    </option>

                    @endforeach

                  </x-select-input>
                  <x-input-error class="mt-2" :messages="$errors->get('day')" />
                </div>

                <div class="w-full sm:w-1/2">
                  <x-input-label for="slot_id" :value="__('Slot')" class="mt-6"/>
                  <x-select-input id="slot_id" 
                    x-model="slot_id"
                    rounded="rounded-md sm:rounded-r-md sm:rounded-l-none"
                    class="inline-block mt-1 w-full" 
                    type="text" 
                    name="slot_id" >

                    <template x-for="slot in computedSlots" >
                      <option 
                        x-bind:selected="slot.id == slot_id"
                        x-bind:value="slot.id" 
                        x-bind:disabled="slot.disabled ? true : false"
                        x-text="slot.start + ' - ' + slot.end">
                    </template>

                  </x-select-input>
                  <x-input-error class="mt-2" :messages="$errors->get('slot_id')" />
                </div>
                  
                {{-- Restore booking date and time to initial state --}}
                <div class="w-full flex justify-end">
                  <x-primary-button 
                    class="mt-2"
                    :small="true"
                    data-tooltip="{{ __('Restore booking\'s date and time') }}"
                    data-placement="left"
                    type="button"
                    x-on:click="
                      day = '{{ $booking->day->toDateString() }}';
                      slot_id = {{ $booking->slot->id }};
                    ">
                    ↩ {{ __('restore') }}
                  </x-primary-button>
                </div>
            </div> {{-- End Alpine object --}}

            {{-- Paid amount --}}
            <x-input-label class="mt-6" for="amount" :value="__('Paid ammount')" />
            <div class="w-full flex items-center justify-start">
              <span class="text-gray-800 dark:text-gray-200 w-6">$</span>
                  <x-text-input 
                    class="mt-1 w-[calc(100%-1.5rem)]" 
                    id="amount" 
                    inputmode="numeric"
                    name="amount" 
                    required
                    step="100"
                    type="number" 
                    :value="old('amount', $booking?->payment->amount)" 
                     />
              <x-input-error :messages="$errors->get('amount')" class="mt-2" />
            </div>

            <div class="flex justify-between mt-8">
              <a href="{{ route('bookings.show', $booking->id) }}">
                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
              </a>
              <x-primary-button>{{ __('Save') }}</x-primary-button>
            </div>

          </form>

        </div>

      </div>
    </div>

  </div>

</x-app-layout>