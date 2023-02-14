{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}

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

        <x-input-label class="mt-6" for="email" :value="__('Email')" />
        <x-text-input 
          id="email" 
          name="email" 
          type="text" 
          class="mt-1 block w-full" 
          :value="old('email', $booking?->user->email)" 
          required autofocus autocomplete="name" 
          wire:model.lazy="email"/>
        <x-input-error class="mt-2" :messages="$errors->get('email')" />

        <x-input-label for="firstName" :value="__('Name')" />
        <x-text-input 
          id="firstName" 
          name="firstName" 
          type="text" 
          class="mt-1 block w-full" 
          :value="old('firstName', $booking?->user->firstName)" 
          required autofocus autocomplete="name" 
          wire:model="firstName"/>
        <x-input-error class="mt-2" :messages="$errors->get('firstName')" />

        <x-input-label class="mt-6" for="lastName" :value="__('Last Name')" />
        <x-text-input 
          id="lastName" 
          name="lastName" 
          type="text" 
          class="mt-1 block w-full" 
          :value="old('lastName', $booking?->user->lastName)" 
          required autofocus autocomplete="name"
          wire:model="lastName"/>
        <x-input-error class="mt-2" :messages="$errors->get('lastName')" />

        {{-- Telephone Number --}}
        <div class="mt-6">
            <x-input-label for="phone" :value="__('Telephone Number')" />
            <div class="flex">
                <x-code-select 
                  :codes="$codes" 
                  :value="old('code_id', $booking?->user->code_id)" 
                  label="Country" 
                  id="code" 
                  name="code_id" 
                  rounded="rounded-l-md"
                  class="flexselect inline-block mt-1 w-2/5" 
                  required 
                  wire:model="code_id"/>
                <x-text-input 
                  id="phone" 
                  inputmode="numeric" 
                  rounded="rounded-r-md"
                  class="inline-block mt-1 w-3/5" 
                  type="text" 
                  name="phone" 
                  :value="old('phone', $booking?->user->phone )" 
                  required 
                  wire:model="phone"/>
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
            :checked="$booking?->virtual ?? true"
            value="1" />
          <x-input-label 
            class="inline-block" 
            for="virtual" 
            :value="__('Virtual')" />
            <br>

          <x-radio-input
            id="in-person"
            name="virtual" 
            :checked="! ($booking?->virtual ?? true)"
            value="0" />
          <x-input-label class="inline-block" 
            for="in-person" 
            :value="__('In-person')" />
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

        </div> {{-- End Alpine slot --}}

        <div class="mt-6 flex justify-end">
          <x-primary-button>
            {{__('Continue to checkout') }}
          </x-primary-button>
        </div>

      </form>

    </div>

  </div>
</div>
