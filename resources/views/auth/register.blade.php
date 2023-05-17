<x-guest-layout>
  <x-google-sign-in />

  <div class="flex justify-center h-12 mb-12 border-b">
    <span class="relative px-4 text-center text-gray-700 bg-white top-9 dark:bg-gray-800 dark:text-gray-300">{{ __('or sign up') }} 👇</span>
  </div>

  <livewire:register />

</x-guest-layout>
