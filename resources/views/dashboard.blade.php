<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                  <h3 class="font-medium">
                    {{ __("Upcoming session") }}
                  </h3>

                  @if(!$booking && !$is_admin)

                    <p class="mt-6">
                      {{ __('There\'s nothing over here')}}
                      <x-primary-button class="mt-6">
                        {{ __('Book yourself a slot') }}
                      </x-primary-button>
                    </p>

                  @elseif(!$is_admin)

                    <livewire:booking 
                      :booking="$booking"
                    />

                  @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
