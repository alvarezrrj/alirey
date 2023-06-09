{{-- The best athlete wants his opponent at his best. --}}
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Dates') }}
        </h2>

        <div class="max-w-xl pl-8 space-y-2">

            <label class="form-check-label inline-block text-gray-800 dark:text-gray-200" for="allways-open">{{ __('Always open') }}
                <x-toggle
                    id="allways-open"
                    :checked="$config->allways_open"
                    value="1"
                    wire:model="config.allways_open"
                ></x-toggle>
            </label>
            <x-input-error :messages="$errors->get('config.allways_open')" class="mt-2" />

            <x-input-label for="anticipation-input">{{ __('Anticipation') }}</x-input-label>
            <small class="text-gray-500 dark:text-gray-500">
                {{ __("Allows users to book with chosen anticipation (days)") }}
            </small>
            <x-text-input class="block" id="anticipation-input" min="0" 
                :disabled="(! $config->allways_open)" type="number" name="anticipation"
                wire:model.lazy="config.anticipation"
                >
            </x-text-input>
            <x-input-error :messages="$errors->get('config.anticipation')" class="mt-2" />

            <x-input-label class="!mt-6" for="open-until-input">{{ __('Open until') }}</x-input-label>
            <small class="text-gray-500 dark:text-gray-500">
                {{ __("Choose the last date to display on the calendar") }}
            </small>
            <x-text-input class="block" :disabled="$config->allways_open" type="date" 
                name="open_until" wire:model="config.open_until">
            </x-text-input>
            <x-input-error :messages="$errors->get('config.open_until')" class="mt-2" />
        </div>
    </div>
</div>


