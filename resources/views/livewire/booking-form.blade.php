{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

  <x-alert-error key="overlap" />
  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">


      {{-- Form won't be displayed if the user has a pending payment. They need
        to complete the payment or cancel the booking before continuing --}}
      @if( session()->has('pending_payment') )

        <x-alert-warning :message="__('You have a pending payment. You need to finish your checkout or delete the booking before you can grab another slot.')" />

        <div class="w-full flex justify-center mt-6">
          <a href="{{ route('user.bookings.checkout', session()->get('pending_payment')) }}">
            <x-primary-button>
              {{ __('Continue to checkout') }}
            </x-primary-button>
          </a>
        </div>

      @else

      <div class="max-w-xl sm:pl-8 space-y-6 text-gray-900 dark:text-gray-100 ">

        {{-- Booking instructions --}}
        @if(! $is_admin)
        <div x-data="{ open: false, height:'' }" class="relative">
          <h3 x-on:click="open = !open" 
            class="mb-6 font-bold text-lg flex items-center z-10 cursor-pointer">
            {{ __('How to book a bioconstelation session') }}
            <span
              class="inline-block ml-3 transition-all duration-500"
              :class="{'-rotate-90': open}"
              >
              <x-bi-caret-down-fill 
                width="20" 
                height="20" 
                />
            </span>
          </h3>
          <p 
          wire:ignore
          class="overflow-hidden transition-all duration-500 absolute"
          x-cloak
          x-init="
            height = `${$el.getBoundingClientRect().height}px`; 
            $el.style.height = 0;
            $el.classList.remove('absolute');
            $watch('open', value => {
              if(value) $el.style.height = height;
              else $el.style.height = 0;
            });"
            >
            {{ __('Select your preferred type of booking (online or in-person), choose a day from the date picker, select one of the available slots and continue to checkout. We\'ll send an email with your booking\'s details once your payment is confirmed and a reminder a few minutes before your booking.') }}
          </p>
        </div>
        @endif

        @if(isset($booking))
          <h3 class="font-bold">
            {{ __('Booking number') }}:&nbsp;{{ $booking->id }}
          </h3>
        @endif

        @php

        $action = $is_admin 
        ? isset($booking)
          ? route('bookings.update', $booking->id)
          : route('bookings.store')
        : route('user.bookings.store')

        @endphp
        

        <form action={{ $action }} method="POST" class="">
          @csrf
          @method(isset($booking) ? 'patch' : 'post')

          @if($is_admin)

          <input type="hidden" value="{{ $booking?->user->id }}" name="user_id">
          <input type="hidden" value="{{ $booking?->id }}" name="booking_id">

          <x-input-label class="mt-6" for="email" :value="__('Email')" />
          <x-text-input 
            id="email" 
            name="email" 
            type="text" 
            class="mt-1 block w-full" 
            :value="old('email', $booking?->user->email)" 
            required autofocus autocomplete="email" 
            wire:model.lazy="email"/>
          <x-input-error class="mt-2" :messages="$errors->get('email')" />

          <x-input-label class="mt-6" for="firstName" :value="__('Name')" />
          <x-text-input 
            id="firstName" 
            name="firstName" 
            type="text" 
            class="mt-1 block w-full" 
            :value="old('firstName', $booking?->user->firstName)" 
            required autocomplete="firstName" 
            wire:model="firstName"/>
          <x-input-error class="mt-2" :messages="$errors->get('firstName')" />

          <x-input-label class="mt-6" for="lastName" :value="__('Last Name')" />
          <x-text-input 
            id="lastName" 
            name="lastName" 
            type="text" 
            class="mt-1 block w-full" 
            :value="old('lastName', $booking?->user->lastName)" 
            required autocomplete="lastName"
            wire:model="lastName"/>
          <x-input-error class="mt-2" :messages="$errors->get('lastName')" />

          {{-- Telephone Number --}}
          <div class="mt-6">
              <x-input-label for="phone" :value="__('Telephone Number')" />
              <div class="flex">
                  <x-code-select 
                    class="flexselect inline-block mt-1 w-2/5" 
                    :codes="$codes" 
                    id="code" 
                    label="Country" 
                    name="code_id" 
                    required 
                    rounded="rounded-l-md"
                    :value="old('code_id', $booking?->user->code_id)" 
                    wire:model="code_id"/>
                  <x-text-input 
                    class="inline-block mt-1 w-3/5" 
                    id="phone" 
                    inputmode="numeric" 
                    name="phone" 
                    required 
                    rounded="rounded-r-md"
                    type="text" 
                    :value="old('phone', $booking?->user->phone )" 
                    wire:model="phone"/>
              </div>
              <x-input-error :messages="$errors->get('phone')" class="mt-2" />
              <x-input-error :messages="$errors->get('code_id')" class="mt-2" />
          </div>

          @endif

          {{-- Booking type --}}
          <fieldset class="mt-6">
            <legend class="font-medium text-sm text-gray-800 dark:text-gray-200">
              {{ __('Booking type') }}
            </legend>
            <x-radio-input 
              id="virtual"
              name="virtual" 
              :checked="$booking?->virtual ?? true"
              value="1" />
            <x-input-label 
              class="inline-block" 
              for="virtual" 
              :value="__('Virtual')" />
              <br>

            <div
              x-data="{
                ackowledged: @entangle('ackowledged'),
                is_admin: @entangle('is_admin'),
              }"
              x-on:click="(!ackowledged && !is_admin) 
              ? $dispatch('open-modal', 'booking-type-modal') 
              : ''"
              >
              <x-radio-input
                id="in-person"
                name="virtual" 
                :checked="! ($booking?->virtual ?? true)"
                value="0"
                />
              <x-input-label class="inline-block" 
                for="in-person" 
                :value="__('In-person')" />
            </div>
          </fieldset>
          <x-input-error class="mt-2" :messages="$errors->get('type')" />

          {{-- Date --}}
          <div class="mt-6"
            wire:click="calClickHandler">
            {{ $this->form }}
          </div>

          <input 
            type="hidden"
            name="day"
            wire:model="day"
          >

          {{-- Alpine slot --}}
          <div class="mt-6"
            x-data="{
                slots: @js($data['slots']),
                bookings: @js($data['bookings']),
                day: @entangle('day'),
                slot_id: @js($booking?->slot->id),

                get computedSlots() {
                  this.slots.forEach(s => {
                    if(this.bookings.some(b => 
                          b.id != {{ $booking->id ?? -1 }} 
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

            <div class="w-full">
              <x-input-label for="slot_id" :value="__('Slot')" class="mt-6"/>
              <x-select-input id="slot_id" 
                x-model="slot_id"
                class="inline-block mt-1 w-full mb-1" 
                type="text" 
                name="slot_id" 
                wire:model="slot_id">

                <template x-for="slot in computedSlots" >
                  <option 
                    x-bind:selected="slot.id == slot_id"
                    x-bind:value="slot.id" 
                    x-bind:disabled="slot.disabled ? true : false"
                    x-text="slot.start + ' - ' + slot.end">
                </template>

              </x-select-input>
              @if(! $is_admin)
              <span class="text-xs text-gray-600 dark:text-gray-400">
                {{ __('Remember times are in UTC-3 (Argentinian time)') }}
              </span>
              @endif
              <x-input-error class="mt-2" :messages="$errors->get('slot_id')" />
            </div>

            @if(isset($booking))
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
            @endif

          </div> {{-- End Alpine slot --}}

          @if(isset($booking))
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
                  wire:model="payment.amount"
                    />
            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
          </div>
          @endif


          @php($back = $is_admin
          ? isset($booking)
            ? route('bookings.show', $booking)
            : route('dashboard')
          : route('dashboard')
          )

          <div class="flex justify-between mt-8">
            <a href="{{ $back }}">
              <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
            </a>

            <x-primary-button>
              @if($is_admin)
                {{ __('Save') }}
              @else
                {{ __('Continue to checkout') }}
              @endif
            </x-primary-button>
          </div>

        </form>

      @endif {{-- End if session()->has('pending_payment') else --}}
    </div>

  </div>

@if(! $is_admin)
  {{-- Booking type modal --}}
  <x-modal name="booking-type-modal" focusable>
    <div class="p-6 text-gray-900 dark:text-gray-100" >
      <h2 class="text-lg font-semibold">
          {{ __('In-person sessions') }}
      </h2>

      <p class="mt-6">
        {{ __('In-person sessions happen in Cosquin, Cordoba, Argenina. If you want to have an online session via video call, please select "Virtual".')}}
      </p>

      <div class="mt-6 flex justify-between">
        <div class="flex items-center">
          <x-checkbox id="dsa" value="1" wire:model="ackowledged" />
          <x-input-label for="dsa" :value="__('Don\'t show again')"/>
        </div>

        <x-primary-button 
          class="ml-3"
          type="submit"
          x-on:click="$dispatch('close')"
          >
          {{ __('Confirm') }}
        </x-primary-button>
      </div>

    </div>
  </x-modal>{{-- End Booking type modal --}}
@endif

@push('libraries')
<script src="https://sdk.mercadopago.com/js/v2"></script>
@endpush

</div>
