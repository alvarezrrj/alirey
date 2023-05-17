{{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}

{{-- Working days livewire component --}}
@php($week_days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friyay', 'Saturday', 'Sunday'])
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Dias laborales
        </h2>
        <div class="max-w-xl pl-8">
            <ul class="space-y-2">

            @foreach($days as $day)
            <li>
                <x-toggle
                    id="toggle-{{ $loop->index }}"
                    :checked="$day"
                    value="1"
                    wire:model="working_days.{{ $loop->index }}" >
                </x-toggle>
                <label class="form-check-label inline-block text-gray-800 dark:text-gray-200" 
                    for="toggle-{{ $loop->index }}">{{ __($week_days[$loop->index]) }}
                </label>
            </li>
            @endforeach

            </ul>

            <x-input-error :messages="$errors->get('working_days')" class="mt-2" />

        </div>
    </div>
</div>