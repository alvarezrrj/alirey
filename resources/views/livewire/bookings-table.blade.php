{{-- The whole world belongs to you. --}}

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <div class="inline-flex bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border dark:border-gray-600 rounded-md divide-x">
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
                <li class="px-1 py-3 hidden sm:grid grid-cols-7 md:grid-cols-9 p-2 text-gray-900 font-bold dark:text-gray-100">
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
                    <div>
                        {{ __('Payment') }}
                    </div>
                    <div>
                        {{ __('Status') }}
                    </div>
                </li>
            @foreach($bookings as $booking)

                <li class="px-1 py-3 grid gap-y-2 gap-x-1 grid-cols-7 sm:grid-cols-7 md:grid-cols-9 grid-rows-3 sm:grid-rows-2 md:grid-rows-1 even:bg-gray-100 even:dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-700  text-gray-900 dark:text-gray-100">
                    <div class="row-span-3 sm:row-span-2 md:row-span-1">
                        {{ $booking->id }}
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        {{ $booking->user->firstName }}
                    </div>
                    <div class="col-span-4 sm:col-span-2">
                        {{ $booking->day->format('d/m/y') }}&nbsp;&middot;
                        {{ $booking->slot->start->format('h:i') }}
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        {{ $booking->virtual ? __('Virtual') : __('In-person') }}
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                      <span class="text-sm text-gray-600 dark:text-gray-400 sm:hidden">
                        {{ __('Payment') }}
                      </span>
                        {{ __($booking->payment->status) }}
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                      <span class="text-sm text-gray-600 dark:text-gray-400 sm:hidden">
                        {{ __('Status') }}
                      </span>
                        {{ __($booking->status) }}
                    </div>
                    <div class="md:mt-0 col-span-6 md:col-span-2 flex items-center justify-between md:justify-end md:grid-rows-2">
                        <a href="{{ route('bookings.show', $booking->id) }}"
                          class="w-2/5 mr-1">
                          <x-primary-button :small="true" class="w-full ">
                            {{ __('View') }}
                          </x-primary-button>
                        </a>

                        <x-danger-button 
                            :small="true" 
                            x-data="" 
                            x-on:click.prevent="$dispatch('open-modal', '{{ $booking->id }}-modal')" 
                            class="w-2/5">
                            {{ __('Delete') }}
                        </x-danger-button>
                    </div>

                    {{-- Delete booking modal --}}
                    <x-modal name="{{ $booking->id }}-modal" focusable>
                        <div class="p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Are you sure you want to delete this booking?') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Booking number') }}:&nbsp;{{ $booking->id}}<br/>
                                {{ __('Name') }}:&nbsp;{{ $booking->user->firstName }} {{ $booking->user->lastName }}<br/>
                                {{ __('Date') }}:&nbsp;{{ $booking->day->format('d/m/y') }} - {{ $booking->slot->start->format('h:i') }}
                            </p>

                            <div class="mt-6 flex justify-end">
                                <x-secondary-button x-on:click="$dispatch('close')">
                                    {{ __('Cancel') }}
                                </x-secondary-button>

                                <x-danger-button 
                                    class="ml-3"
                                    wire:click.prevent="delete({{ $booking->id }})">
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


