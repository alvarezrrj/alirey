{{-- Admin configurations --}}
<x-app-layout>

    @push('styles')
        <link
            href="{{ Vite::asset('resources/css/toggle.css') }}"
            rel="stylesheet"
            type="text/css"  />
    @endpush

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-6">

        <livewire:config.working-days />

        <livewire:config.anticipation />

        <livewire:config.price />

        <livewire:config.holiday />

        <livewire:config.slots />

        <livewire:config.google-sync />

    </div>

</x-app-layout>
