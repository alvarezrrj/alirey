{{-- Admin configurations --}}
<x-app-layout>

    @push('styles')
        <link
            href="{{ Vite::asset('resources/css/config.css') }}"
            rel="stylesheet"
            type="text/css"  />
    @endpush

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12 space-y-6">

        <livewire:working-days />

        <livewire:anticipation />

        <livewire:price />

        <livewire:holiday />

        <livewire:slots />

    </div>

</x-app-layout>
