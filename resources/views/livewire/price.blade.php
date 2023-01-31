{{-- The Master doesn't talk, he acts. --}}

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg space-y-6">

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Price') }}
        </h2>

        <div class="max-w-xl pl-8 space-y-2">

            <span class="text-gray-900 dark:text-gray-100">$&nbsp;</span>
            <x-text-input 
                class="inline-block" id="price" 
                min="0" 
                intputmode="numeric" 
                type="number" 
                step="100"
                name="price"
                wire:model.debounce.750ms="config.price"
                
            />
            <x-input-error :messages="$errors->get('config.price')" class="mt-2" />
            
        </div>
    </div>
</div>


