<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
  <!-- Primary Navigation Menu -->
  <div class="mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
    <div class="flex justify-between h-16">
      <div class="flex">
        <!-- Logo -->
        <div class="shrink-0 flex items-center">
          <a href="/">
            <x-small-logo class="block h-9 w-auto" />
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link :href="route('user.bookings.create')" :active="request()->is('user.bookings.create')">
            {{ __('New booking') }}
          </x-nav-link>
        </div>
      </div>

      <!-- Settings Dropdown -->
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        <x-nav-link :href="route('login')" :active="request()->is('login')">
          {{ __('Log in') }}
        </x-nav-link>

        <x-nav-link :href="route('register')" :active="request()->is('register')">
          {{ __('Register') }}
        </x-nav-link>
      </div>

      <!-- Hamburger -->
      <div class="-mr-2 flex items-center sm:hidden">
        <button @click="open = ! open"
          class="relative inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>

          @if(session()->has('pending_payment'))
          <div class="absolute top-0 left-0 border-4 border-red-700 rounded-full">
          </div>
          @endif
        </button>
      </div>
    </div><!-- End Hamburger -->

  </div><!-- End Primary Navigation Menu -->

  <!-- Responsive Navigation Menu -->
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

    <div class="pt-2 pb-3 space-y-1">
      <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('New booking') }}
      </x-responsive-nav-link>
    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
      <div class="mt-3 space-y-1">

        <x-responsive-nav-link :href="route('login')"
          :active="request()->routeIs('login')">
            {{ __('Log in') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('register')"
          :active="request()->routeIs('register')">
            {{ __('Register') }}
        </x-responsive-nav-link>

      </div>
    </div>

  </div><!-- End Responsive Navigation Menu -->
</nav>
