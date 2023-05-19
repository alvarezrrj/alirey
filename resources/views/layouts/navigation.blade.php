<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
  @php($therapist = App\Models\Role::where('role', SD::admin)->first()->users()->first())
  <!-- Primary Navigation Menu -->
  @php($max_w = Auth::user()->isAdmin()
  ? ''
  : 'max-w-7xl')
  <div class="mx-auto px-4 sm:px-6 lg:px-8 {{ $max_w }}">
    <div class="flex justify-between h-16">
      <div class="flex">
        <!-- Logo -->
        <div class="flex items-center shrink-0">
          <a href="/">
            <x-small-logo class="block w-auto h-9" />
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
          <x-nav-link href="/"
            :active="request()->is('/')">
            {{ __('Home') }}
          </x-nav-link>

          <x-nav-link :href="route('dashboard')"
            :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-nav-link>

          @if(Auth::user()->isAdmin())

          <x-nav-link :href="route('config')"
            :active="request()->routeIs('config')">
            {{ __('Configuration') }}
          </x-nav-link>

          <x-nav-link :href="route('bookings.index')"
            :active="request()->routeIs('bookings.index')">
            {{ __('Bookings') }}
          </x-nav-link>

          <x-nav-link :href="route('users.index')"
            :active="request()->routeIs('users.index')">
            {{ __('Users') }}
          </x-nav-link>

          @else

          <x-nav-link :href="route('bookings.index')"
            :active="request()->routeIs('bookings.index')">
            {{ __('My bookings') }}
          </x-nav-link>

          <x-nav-link :href="route('bookings.create', $therapist)"
            :active="request()->routeIs('bookings.create', $therapist)">
            {{ __('New booking') }}
          </x-nav-link>

          @endif
        </div>
      </div>

      <!-- Settings Dropdown -->
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        <x-dropdown align="right">
          <x-slot name="trigger">
            <button tabindex="0"
              class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border-red-700 rounded-md hover:border-red-600 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300">
              <div class="relative border-inherit">
                @if (Auth::user()->avatar)
                  <img class="inline w-8 h-8 rounded-full me-2" src="{{ Auth::user()->avatar }}"
                    referrerpolicy="no-referrer">
                @endif
                {{ Auth::user()->firstName }}
                @if(session()->has('pending_payment'))
                <div
                  class="absolute top-0 left-0 -translate-y-1/2 border-4 rounded-full border-inherit -translate-x-3/4">
                </div>
                @endif

              </div>

              <div class="ml-1">
                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content" class="py-2">
            @if(session()->has('pending_payment'))
            <x-dropdown-link :href="route('bookings.checkout', session()->get('pending_payment'))"
              class="ml-px border-l border-red-700">
              {{ __('Checkout') }}
            </x-dropdown-link>
            @endif

            <x-dropdown-link :href="route('profile.edit')">
              {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-dropdown-link
                :href="route('logout')"
                onclick="event.preventDefault();
                 this.closest('form').submit();"
                 >
                {{ __('Log Out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>

      <!-- Hamburger -->
      <div class="flex items-center -mr-2 sm:hidden">
        <button @click="open = ! open"
          class="relative inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400">
          <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
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
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
      <x-responsive-nav-link href="/" :active="request()->is('/')">
        {{ __('Home') }}
      </x-responsive-nav-link>
      <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
      </x-responsive-nav-link>

      @if(Auth::user()->isAdmin())

      <x-responsive-nav-link :href="route('config')" :active="request()->routeIs('config')">
        {{ __('Configuration') }}
      </x-responsive-nav-link>

      <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')">
        {{ __('Bookings') }}
      </x-responsivenav-link>

      <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
        {{ __('Users') }}
      </x-responsive-nav-link>

      @else

      @if(session()->has('pending_payment'))
      <x-responsive-nav-link :href="route('bookings.checkout', session()->get('pending_payment'))"
        class="border-red-700 border-l-3">
        {{ __('Checkout') }}
      </x-responsive-nav-link>
      @endif

      <x-responsive-nav-link :href="route('bookings.index')"
        :active="request()->routeIs('bookings.index')">
        {{ __('My bookings') }}
      </x-responsive-nav-link>

      <x-responsive-nav-link :href="route('bookings.create', $therapist)"
        :active="request()->routeIs('bookings.create', $therapist)">
        {{ __('New booking') }}
      </x-responsive-nav-link>

      @endif
    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
      <div class="flex flex-wrap justify-between w-full px-4">
        <div>
        <span class="text-base font-medium text-gray-800 dark:text-gray-200">
          {{ Auth::user()->firstName }}
        </span><br>
        <span class="text-sm font-medium text-gray-500 ">
          {{ Auth::user()->email }}
        </span>
        </div>
        <img class="w-12 rounded-full" src="{{ Auth::user()->avatar }}"
        referrerpolicy="no-referrer">
      </div>

      <div class="mt-3 space-y-1">
        <x-responsive-nav-link :href="route('profile.edit')">
          {{ __('Profile') }}
        </x-responsive-nav-link>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">
            {{ __('Log Out') }}
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
  </div>
</nav>
