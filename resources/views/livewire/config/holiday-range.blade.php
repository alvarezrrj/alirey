{{-- Be like water. --}}
<tr class="even:bg-gray-100 even:dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-700">

    <td class="p-2">{{ $range[0] }}</td>

    <td class="p-2">{{ $range[1] != $range[0] 
            ? $range[1]
            : '-' 
        }}
    </td>

    <td class="grid place-items-center p-2">
        <x-danger-button
            :small="true" 
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', '{{ $range[0] }}{{ $range[1] }}')"
        >{{ __('delete') }}
        </x-danger-button>
        <x-input-error :messages="$errors->get('range')" class="mt-2" />
    </td>

    <td>
        <x-modal name="{{ $range[0] }}{{ $range[1] }}" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete this holiday plan?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $range[0]}} @if($range[1] != $range[0]) {{ __('to') }} {{ $range[1] }} @endif
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

