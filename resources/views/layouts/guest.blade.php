<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @livewireScripts

        <!-- Third party styles -->
        @stack('third-party-styles')

        <!-- Own styles -->
        @stack('styles')
    </head>
    <body class="min-h-screen font-sans antialiased text-gray-900 bg-gray-100 dark:bg-gray-900">
      <header class="sticky top-0">
        @include('layouts.guest-navigation')
      </header>
        <div class="flex flex-col items-center pt-6 mb-8 sm:justify-center sm:pt-0">
            <div>
                <a href="/">
                    <x-vertical-logo class="text-gray-500 fill-current w-52 h-52" />
                </a>
            </div>

            {{ $header ?? '' }}

            <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-lg dark:bg-gray-800 sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        {{-- Push stuff into this stack with @push('scripts') <foo> @endpush
             from within the view --}}
        @stack('libraries')
        @stack('scripts')

    </body>
</html>
