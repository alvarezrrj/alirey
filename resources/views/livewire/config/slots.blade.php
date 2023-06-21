{{-- Nothing in the world is as soft and yielding as water. --}}

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Slots') }}
        </h2>

        <p class="text-gray-600 dark:text-gray-400">
            {{ __('Set up your daily schedule') }}
        </p>

        <x-alert-message />

        <div class="max-w-xl sm:pl-8">

            <form wire:submit.prevent="submit" class="space-y-4">
                <div class="flex space-x-5">
                <x-input-label for="start">{{ __('Start') }}
                    <x-text-input
                        class="block mt-1" id="start"
                        name="start"
                        required
                        type="time"
                        wire:model="slot.start" />
                    </x-input-label>
                <x-input-error :messages="$errors->get('start')" class="mt-2" />

                <x-input-label for="end">{{ __('End') }}
                    <x-text-input
                        class="block mt-1" id="end"
                        :min="date('Y-m-d')"
                        name="end"
                        required
                        type="time"
                        wire:model="slot.end" />
                </x-input-label>
                </div>
                <x-input-error :messages="$errors->get('end')" class="mt-2" />
                <x-input-error :messages="$errors->get('invalid')" class="mt-2" />
                <x-input-error :messages="$errors->get('overlaps')" class="mt-2" />

                <x-primary-button
                    :disabled="$overlaps || $invalid"
                >{{ __('Save') }}</x-primary-button>
            </form>

            @if(count($slots))

            <table class="w-full text-left text-gray-900 dark:text-gray-100 mt-10">
                <thead>
                    <tr class="odd:bg-gray-100 dark:odd:bg-gray-900">
                        <th class="p-2">{{ __('From') }}</th>
                        <th class="p-2">{{ __('Until') }}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($slots as $slot)

                    <livewire:config.slot
                        :slot="$slot"
                        :wire:key="$slot->id"/>

                @endforeach
                </tbody>
            </table>

            @endif

        </div>
    </div>
</div>



