{{-- Success is as dangerous as failure. --}}
<tr class="even:bg-gray-100 even:dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-700">

    <td class="p-2">{{ $slot->start->format('H:i') }}</td>

    <td class="p-2">{{ $slot->end->format('H:i') }}</td>

    <td class="grid place-items-center p-2">
        <x-danger-button
            :small="true" 
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', '{{ $slot->id }}-modal')"
        >{{ __('delete') }}
        </x-danger-button>
        <x-input-error :messages="$errors->get('slot')" class="mt-2" />
    </td>

    <td>
        <x-modal name="{{ $slot->id }}-modal" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete this slot?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $slot->start->format('H:i') }} - {{ $slot->end->format('H:i') }}
                </p>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button wire:click="delete" x-on:click="$dispatch('close')" class="ml-3">
                        
                        {{ __('Delete') }}
                    </x-danger-button>
                </div>
            </div>
        </x-modal>
    </td>

</tr>


