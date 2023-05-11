{{-- Toggle switch styles --}}
@push('styles')
<link
    href="{{ Vite::asset('resources/css/config.css') }}"
    rel="stylesheet"
    type="text/css"  />
@endpush

<div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

  <x-alert-error key="overlap" />

  <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
    <div class="max-w-xl space-y-6 text-gray-900 sm:pl-8 dark:text-gray-100 ">

      @if($is_admin)
      <label class="inline-block text-gray-800 form-check-label dark:text-gray-200"
      for="toggle_is_booking">
        {{ __('Is booking') }}
        <x-toggle
          :checked="$is_booking"
          id="toggle_is_booking"
          name="is_booking"
          value="{{ old('is_booking') }}"
          wire:model="is_booking">
        </x-toggle>
      </label>
      <p class="!mt-0 text-sm text-gray-600 dark:text-gray-400">
        {{ __('toggle this to choose between a holiday or a booking.')}}
      </p>
      @endif

      <form action={{ route('bookings.singleSlotHoliday') }} method="POST" class="">

        @csrf
        @method('post')

        {{-- <div wire:click="calClickHandler"> Hola </div> --}}
        {{-- Date --}}
        <div class="mt-6" wire:click="calClickHandler">
        {{ $this->dayForm }}
      </div>

        <input
          type="hidden"
          name="day"
          wire:model="day"
          >
        <x-input-error class="mt-2" :messages="$errors->get('day')" />

        {{-- Alpine slot --}}
        <div class="mt-6"
        x-data="{
            slots: @js($data['slots']),
            bookings: @js($data['bookings']),
            day: @entangle('day'),
            slot_id: @js($booking?->slot->id),

            get computedSlots() {
              this.slots.forEach(s => {
                if(this.bookings.some(
                  b => b.id != {{ $booking->id ?? -1 }}
                    && b.day == this.day
                    && b.slot.id == s.id
                ) || (this.day == (new Date).toISOString().split('T')[0]
                  && Number(s.start.split(':')[0]) <= (new Date).getHours())
                ) s.disabled = true;
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
              class="inline-block w-full mt-1 mb-1"
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

        </div> {{-- End Alpine slot --}}

        <div class="flex justify-between mt-8">
          <a href="{{ route('bookings.index') }}">
            <x-secondary-button type="button">{{ __('Cancel') }}</x-secondary-button>
          </a>

          <x-primary-button>
            {{ __('Save') }}
          </x-primary-button>
        </div>

      </form>

    </div>
  </div>
</div>
