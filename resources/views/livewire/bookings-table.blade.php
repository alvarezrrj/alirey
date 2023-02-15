{{-- The whole world belongs to you. --}}

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
  <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

    <div
      class="inline-flex bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border dark:border-gray-600 rounded-md divide-x">
      <span class="table-select">
        {{ __('All') }}
      </span>
      <span class="table-select" wire:click="filter({{ $filters[0] }})">
        {{ __($filters[0]) }}
      </span>
      <span class="table-select" wire:click="filter({{ $filters[1] }})">
        {{ __($filters[1]) }}
      </span>
      <span class="table-select" wire:click="filter({{ $filters[2] }})">
        {{ __($filters[2]) }}
      </span>
    </div>

    <div class="lg:pl-8 space-y-2 mt-8">

      <ul class=>
        {{-- Header --}}

        @php($header_class = $is_admin
        ? "px-1 py-3 hidden sm:grid grid-cols-7 md:grid-cols-9 p-2 text-gray-900 font-bold dark:text-gray-100"
        : "px-1 py-3 hidden sm:grid grid-cols-5 md:grid-cols-7 p-2 text-gray-900 font-bold dark:text-gray-100")

        <li class="{{ $header_class }}">
          <div>
            {{ __('ID') }}
          </div>
          <div>
            {{ __('Name') }}
          </div>
          <div class="col-span-2">
            {{ __('Date') }}
          </div>
          <div>
            {{ __('Type') }}
          </div>
          @if($is_admin)
          <div>
            {{ __('Payment') }}
          </div>
          @endif
          <div>
            {{ __('Status') }}
          </div>
        </li>
        @foreach($bookings as $booking)

        @php($li_class = $is_admin
        ? "my-6 rounded-md border border-gray-300 dark:border-gray-700 md:border-0 md:my-0 md:rounded-none px-4 py-3 grid gap-y-2 gap-x-1 grid-cols-7 sm:grid-cols-7 md:grid-cols-9 grid-rows-3 sm:grid-rows-2 md:grid-rows-1 even:bg-gray-100 even:dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100"
        : "my-6 rounded-md border border-gray-300 dark:border-gray-700 md:border-0 md:my-0 md:rounded-none px-4 py-3 grid gap-y-2 gap-x-1 grid-cols-5 sm:grid-cols-5 md:grid-cols-7 grid-rows-3 sm:grid-rows-2 md:grid-rows-1 even:bg-gray-100 even:dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100")
        <li
          class="{{ $li_class }}">
          <div class="row-span-3 sm:row-span-2 md:row-span-1">
            {{ $booking->id }}
          </div>
          <div class="col-span-2 sm:col-span-1">
            {{ $booking->user->firstName }}
          </div>
          <div class="col-span-4 sm:col-span-2">
            {{ $booking->day->format('d/m/y') }}&nbsp;&middot;
            {{ $booking->slot->start->format('H:i') }}
          </div>
          <div class="col-span-2 sm:col-span-1">
            {{ $booking->virtual ? __('Virtual') : __('In-person') }}
          </div>
          @if($is_admin)
          <div class="col-span-2 sm:col-span-1">
            <span class="text-sm text-gray-600 dark:text-gray-400 sm:hidden">
              {{ __('Payment') }}
            </span>
            {{ __($booking->payment->status) }}
          </div>
          @endif
          <div class="col-span-2 sm:col-span-1">
            <span class="text-sm text-gray-600 dark:text-gray-400 sm:hidden">
              {{ __('Status') }}
            </span>
            {{ __($booking->status) }}
          </div>

          @php($buttons_wrapper = $is_admin
          ? "md:mt-0 col-span-6 md:col-span-2 flex items-center justify-between md:justify-end"
          : "md:mt-0 col-span-5 md:col-span-1 flex items-center justify-between md:justify-end")
          @php($button_class = $is_admin
          ? "w-2/5"
          : "w-full")
          <div class="{{ $buttons_wrapper }}">
            <a href="{{ route('bookings.show', $booking->id) }}" class={{ $button_class }}>
              <x-primary-button :small="true" class="w-full">
                {{ __('View') }}
              </x-primary-button>
            </a>

            @if($is_admin)
            <x-danger-button :small="true" x-data=""
              x-on:click.prevent="$dispatch('open-modal', '{{ $booking->id }}-modal')" class="w-2/5 md:ml-1">
              {{ __('Delete') }}
            </x-danger-button>
            @endif
          </div>

          {{-- Delete booking modal --}}
          <x-modal name="{{ $booking->id }}-modal" focusable>
            <div class="p-6">
              <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete this booking?') }}
              </h2>

              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Booking number') }}:&nbsp;{{ $booking->id}}<br />
                {{ __('Name') }}:&nbsp;{{ $booking->user->firstName }} {{ $booking->user->lastName }}<br />
                {{ __('Date') }}:&nbsp;{{ $booking->day->format('d/m/y') }} - {{ $booking->slot->start->format('h:i') }}
              </p>

              <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                  {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ml-3" wire:click.prevent="delete({{ $booking->id }})">
                  {{ __('Delete') }}
                </x-danger-button>

              </div>
            </div>
          </x-modal>

        </li>

        @endforeach
      </ul>

    </div>
  </div>
</div>
