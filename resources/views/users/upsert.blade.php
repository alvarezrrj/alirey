{{-- A form for the admin to edit users --}}
<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
      @unless(url()->previous() == url()->current())
        <a href="{{ url()->previous() }}">
            <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
        </a>
      @endunless

      <h2 class="inline-block ml-2 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ __('Edit User') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

      <x-alert-message key="message" />

      <div class="p-4 text-gray-900 bg-white shadow sm:p-8 dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg ">

        <div class="max-w-xl sm:pl-8">
          <livewire:register :user="$user" :back_url="$back_url ?? null"/>
        </div>

      </div>

    </div>
  </div>

</x-app-layout>
