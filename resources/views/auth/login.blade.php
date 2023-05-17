<x-guest-layout>

  <x-slot:header>
    <p class="px-2 font-semibold leading-tight text-left text-gray-800 sm:px-0 dark:text-gray-200">
      {{ __('Log in to go forward. If you haven\'t got an acount, you can') }}
      <a class="underline transition-all text-brown hover:text-orange" href="{{ route('register') }}">{{ __('register') }}</a>.
    </p>
  </x-slot>

  <x-alert-error key="login" />

  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />

  <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Address -->
      <div>
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      <!-- Password -->
      <div class="mt-4">
          <x-input-label for="password" :value="__('Password')" />

          <x-text-input id="password" class="block w-full mt-1"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

          <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>

      <!-- Remember Me -->
      <div class="block mt-4">
          <label for="remember_me" class="inline-flex items-center">
              <input id="remember_me" type="checkbox" class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
              <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
          </label>
      </div>

      <div class="flex items-center justify-end mt-4">
          @if (Route::has('password.request'))
              <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                  {{ __('Forgot your password?') }}
              </a>
          @endif

          <x-primary-button class="ml-3">
              {{ __('Log in') }}
          </x-primary-button>

      </div>

      <div class="flex justify-center h-12 mb-12 border-b">
        <span class="relative px-4 text-center text-gray-700 bg-white top-9 dark:bg-gray-800 dark:text-gray-300">{{ __('or') }} 👇</span>
      </div>

      {{-- Sign in with Google button --}}
      <x-google-sign-in />

  </form>
</x-guest-layout>



