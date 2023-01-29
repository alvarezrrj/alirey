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
                {{ json_encode($working_days)}}
            @foreach($days as $day)
            <li>
                <input class="toggle checked:bg-brown checked:hover:bg-brown checked:border-brown focus:ring-brown focus:checked:bg-brown appearance-none w-9 -ml-10 rounded-full float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer shadow-sm" 
                type="checkbox" 
                @checked($day)
                role="switch" 
                id="toggle-{{ $loop->index }}"
                name="index"
                value="1"
                wire:model='working_days.{{$loop->index}}'
                />
                <label class="form-check-label inline-block text-gray-800 dark:text-gray-200" for="toggle-{{ $loop->index }}">{{ __($week_days[$loop->index]) }}</label>
            </li>
            @endforeach
            </ul>
        </div>
    </div>
</div>