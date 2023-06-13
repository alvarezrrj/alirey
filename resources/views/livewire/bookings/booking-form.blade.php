{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div>
{{-- Toggle switch styles --}}
@push('styles')
  @vite('resources/css/toggle.css')
{{-- Fix searchable select dark mode issue untill filament team sort it out --}}
<style>
  .choices {
    background: inherit;
    border-color: inherit;
    color: inherit;
    border-radius: inherit;
  }
  .choices__inner {
    background: inherit;
    border-color: inherit;
    color: inherit;
    border-radius: inherit;
  }
  .choices__list--dropdown {
    background: inherit !important;
    border-color: inherit !important;
    color: inherit !important;
    border-radius: inherit !important;
  }
</style>
@endpush

      {{-- Form won't be displayed if the user has a pending payment. They need
        to complete the payment or cancel the booking before continuing --}}
      @if( session()->has('pending_payment') )

        <x-alert-warning :message="__('You have a pending payment. You need to finish your checkout or delete the booking before you can grab another slot.')" />

        <div class="flex justify-center w-full mt-6">
          <a href="{{ route('bookings.checkout', session()->get('pending_payment')) }}">
            <x-primary-button>
              {{ __('Continue to checkout') }}
            </x-primary-button>
          </a>
        </div>

      @else

      <div class="max-w-xl space-y-6 text-gray-900 sm:pl-8 dark:text-gray-100 ">
        <x-alert-warning key="overlap" />
        <x-alert-message />

        {{-- Booking instructions --}}
        @if(! $is_admin)
        <div x-data="{ open: false, height:'' }" class="relative">
          <h3 x-on:click="open = !open"
            class="z-10 flex items-center mb-6 text-lg font-bold cursor-pointer">
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
          class="absolute overflow-hidden transition-all duration-500"
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
          <h3 class="mt-4 font-bold">
            {{ __('Booking number') }}:&nbsp;{{ $booking->id }}
          </h3>
        @endif


        @php($action = isset($booking) ? 'update' : 'insert')
        <form wire:submit.prevent="{{ $action }}">
          @csrf
          @method(isset($booking) ? 'patch' : 'post')

          @if($is_admin && $is_booking)

          <input type="hidden" value="{{ $booking?->user->id }}" name="user_id">
          <input type="hidden" value="{{ $booking?->id }}" name="booking_id">

          {{ $this->clientForm }}

          @endif

          @if($is_booking)
            {{-- Booking type --}}
            <fieldset class="mt-6">
              <legend class="text-sm font-medium text-gray-800 dark:text-gray-200">
                {{ __('Booking type') }}
              </legend>
              <x-radio-input
                id="virtual"
                name="virtual"
                :checked="$booking?->virtual ?? true"
                value="1"
                wire:model="virtual"/>
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
                  wire:model="virtual"
                  />
                <x-input-label class="inline-block"
                  for="in-person"
                  :value="__('In-person')" />
              </div>
            </fieldset>
            <x-input-error class="mt-2" :messages="$errors->get('virtual')" />
          @else
            <input type="hidden" name="virtual" value="1" wire:model="virtual">
          @endif

          {{-- Date --}}
          <div class="mt-6">
            {{ $this->dayForm }}
          </div>

          {{-- Alpine slot --}}
          {{--
            computedSlots disables slots if:
              * exists a booking for same day and slot (except that booking
                is the booking being edited)
              * the slot is for earlier today
          --}}
          <div class="mt-6"
            x-data="{
              slots: @js($data['slots']),
              bookings: @js($data['bookings']),
              day: @entangle('day'),
              slot_id: @entangle('slot_id'),

              get computedSlots() {
                this.slots.forEach(s => {
                  if(this.bookings.some(
                    b => b.id != {{ $booking->id ?? -1 }}
                    && b.day == this.day?.split(' ')[0]
                    && b.slot.id == s.id
                    ) || (this.day?.split(' ')[0] == (new Date).toISOString().split('T')[0]
                    && Number(s.start.split(':')[0]) <= (new Date).getHours())
                    ) s.disabled = true;
                    else
                    s.disabled = false;
                  });
                  return this.slots;
                },
            }">

            <div class="w-full">
              <x-input-label for="slot_id" :value="__('Slot')" class="inline-block "/>
              <sup class="font-medium text-danger-700 dark:text-danger-400">*</sup>
              <x-select-input id="slot_id"
                x-model="slot_id"
                class="inline-block w-full mt-1 mb-1"
                type="text"
                name="slot_id"
                wire:model="slot_id">

                <template x-for="(slot, index) in computedSlots" :key="slot.id">
                  <option
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
            <div class="flex justify-end w-full">
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

          @if(! $is_admin)
          {{-- Ackowledge terms --}}
          <div class="flex items-center mt-6 text-sm text-gray-800 dark:text-gray-200">
            <x-checkbox wire:model="accepts_terms"/>
            {{ __('I have read and accept the') }}&nbsp;
            <a href="{{ route('terms') }}" target="_blank" class="underline">
              {{ __('terms and conditions') }}
            </a>
          </div>
          @endif

          @if(isset($booking))
          {{-- Paid amount --}}
          <x-input-label class="mt-0" for="amount" :value="__('Paid ammount')" />
          <div class="flex items-center justify-start w-full">
            <span class="w-6 text-gray-800 dark:text-gray-200">$</span>
                <x-text-input
                  class="mt-1 w-[calc(100%-1.5rem)]"
                  id="amount"
                  inputmode="numeric"
                  required
                  step="100"
                  type="number"
                  wire:model.defer="amount"
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
            @if($is_booking)
              <a href="{{ $back }}">
                <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
              </a>
            @endif

            @if(! $is_admin && $virtual && ! $client->phone  && ! $client->prefers_calling)
            {{-- Show phone number modal --}}
            <x-primary-button
              :disabled="!$accepts_terms"
              type="button"
              x-data=""
              x-on:click="$dispatch('open-modal', 'no-phone-modal')">
              {{ __('Continue to checkout') }}
            </x-primary-button>
            @else
            {{-- Save booking and continue --}}
            <x-primary-button
              class="min-w-[7rem]"
              :disabled="!$accepts_terms && !$is_admin" >
              <span wire:loading.class='hidden' wire:target='update,insert'>
                @if($is_admin)
                  {{ __('Save') }}
                @else
                  {{ __('Continue to checkout') }}
                @endif
              </span>
              <span wire:loading wire:target='update,insert'>
                <x-spinner />
              </span>
            </x-primary-button>
            @endif
          </div>

        </form>

      @endif {{-- End if session()->has('pending_payment') else --}}
    </div>


  {{-- Booking type modal --}}
  @if(! $is_admin)
    <x-modal name="booking-type-modal" focusable>
      <div class="p-6 text-gray-900 dark:text-gray-100" >
        <h2 class="text-lg font-semibold">
            {{ __('In-person sessions') }}
        </h2>

        <p class="mt-6">
          {{ __('In-person sessions happen in Cosquin, Cordoba, Argenina. If you want to have an online session via video call, please select "Virtual".')}}
        </p>

        <div class="flex justify-between mt-6">
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

  {{-- No phone modal --}}
  @if(! $is_admin && ! Auth::user()->phone)
  <x-modal name="no-phone-modal" focusable>

    @if($errors->isNotEmpty())
    <div x-data="" x-init="$dispatch('close')">Sarasa</div>
    @endif

    <div class="p-6 text-gray-900 dark:text-gray-100" >
      <h2 class="text-lg font-semibold">
        {{ __('Virtual sessions') }}
      </h2>

      <p class="my-6">
        {{ __('Since you haven\'t given us your phone number, it\'ll be your responsibility to phone us at the time of your booking. Or you can leave your number bellow and let us call you.') }}
      </p>

      <livewire:phone-input />

      <p class="mt-6 text-sm">
        <x-antdesign-info-circle class="inline-block w-4 h-4" />
        {{ __('If you want to stop seeing this message, you can go to your profile and select \'I prefer to call you\' under the phone number field.') }}

      <div class="flex justify-end mt-6">
        <x-secondary-button
          class="min-w-[12rem]"
          wire:click='insert'>
          <span wire:loading.class='hidden'>
            {{ __('I\'ll call you') }}
          </span>
          <span wire:loading wire:target='insert'>
            <x-spinner />
          </span>
        </x-secondary-button>
      </div>

    </div>
  </x-modal>
  @endif

  @push('libraries')
  <script src="https://sdk.mercadopago.com/js/v2"></script>
  @endpush

  <pre class="text-white">
    {{-- Errors:
    {{ print_r($errors) }} --}}
    {{-- Client:
    {{ print_r($client) }} --}}
  </pre>

</div>
