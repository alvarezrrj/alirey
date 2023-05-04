{{-- A form for the admin to edit users --}}
<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
      @unless(url()->previous() == url()->current())
        <a href="{{ url()->previous() }}">
            <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
        </a>
      @endunless

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit User') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      <x-alert-message key="message" />

      <div class="p-4 sm:p-8 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <div class="max-w-xl sm:pl-8">

          <form method="POST" action="{{ route('users.update', $user) }}">
              @csrf
              @method('PUT')

              <!-- ID -->
              <input type="hidden" name="user_id" value="{{ $user->id }}">

              <!-- First Name -->
              <div>
                  <x-input-label for="firstName" :value="__('Name')" />
                  <x-text-input id="firstName" class="block mt-1 w-full" type="text" name="firstName" :value="old('firstName', $user->firstName)" required autofocus />
                  <x-input-error :messages="$errors->get('firstName')" class="mt-2" />
              </div>

              <!-- Last Name -->
              <div class="mt-4">
                  <x-input-label for="Lastname" :value="__('Last Name')" />
                  <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastName" :value="old('lastName', $user->lastName)" required />
                  <x-input-error :messages="$errors->get('lastName')" class="mt-2" />
              </div>

              <!-- Email Address -->
              <div class="mt-4">
                  <x-input-label for="email" :value="__('Email')" />
                  <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                  <x-input-error :messages="$errors->get('email')" class="mt-2" />
              </div>

              <!-- Telephone Number -->
              <div class="mt-4">
                  <x-input-label for="phone" :value="__('Telephone Number')" />
                  <div class="flex">
                      <x-code-select :codes="$codes" :value="old('code', $user->code->id)" label="Country code" id="code" name="code_id" rounded="rounded-l-md"
                      class="flexselect inline-block mt-1 w-2/5" required />
                      <x-text-input id="phone" inputmode="numeric" rounded="rounded-r-md"
                      class="inline-block mt-1 w-3/5" type="text" name="phone" :value="old('phone', $user->phone)" required />
                  </div>
                  <x-input-error :messages="$errors->get('phone')" class="mt-2" />
              </div>

              <div class="flex items-center justify-between mt-4">
                <a href="{{ route('users.show', $user) }}">
                  <x-secondary-button type="button">
                    {{ __('Cancel') }}
                  </x-secondary-button>
                </a>

                <x-primary-button type="submit">
                  {{ __('Save') }}
                </x-primary-button>
              </div>
          </form>

        </div>
      </div>
    </div>
  </div>

</x-app-layout>