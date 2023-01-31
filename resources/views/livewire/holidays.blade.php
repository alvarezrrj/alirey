{{-- In work, do what you enjoy. --}}
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Holidays') }}
        </h2>

        <x-alert-error key="bookings" />

        <x-alert-warning key="holiday" />

        <x-alert-message />

        <div class="max-w-xl pl-8">

            <form wire:submit.prevent="submit" class="space-y-4">
                <x-input-label for="from">{{ __('From') }}
                    <x-text-input 
                        class="block mt-1" id="from" 
                        :min="date('Y-m-d')"
                        name="from"
                        required
                        type="date" 
                        wire:model="from" />
                    </x-input-label>
                <x-input-error :messages="$errors->get('from')" class="mt-2" />
                
                <x-input-label for="until">{{ __('Until') }}
                    <small class="text-gray-500 dark:text-gray-500">
                        &middot; {{ __("Omit it for one-day holidays")}}
                    </small>
                    <div class="flex items-center">
                        <x-text-input 
                            class="block mt-1" id="until" 
                            :min="date('Y-m-d')"
                            name="until"
                            type="date" 
                            wire:model="until" />
                        <x-secondary-button  class="ml-2" :small="true" type="button"
                            wire:click="resetUntil" >
                            ↩ borrar
                        </x-secondary-button>
                    </div>
                </x-input-label>
                <x-input-error :messages="$errors->get('until')" class="mt-2" />

                <x-primary-button
                    :disabled="$bookings_exist || $holiday_overlap"
                >{{ __('Save') }}</x-primary-button>
            </form>

            @if(count($holidays))

            <h3 class="text-gray-900 dark:text-gray-100 mt-10">
                {{ __('Holiday plans') }}
            </h3>
            <table class="w-full text-left text-gray-900 dark:text-gray-100 mt-2">
                <thead>
                    <tr class="odd:bg-gray-100 dark:odd:bg-gray-900">
                        <th class="p-2">{{ __('From') }}</th>
                        <th>{{ __('Until') }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($holidays as $holiday)

                    <livewire:holiday-range 
                        :index="$loop->index"
                        :range="$holiday" 
                        :wire:key="$loop->index"/>

                @endforeach
                </tbody>
            </table>

            @endif

        </div>
    </div>
</div>



