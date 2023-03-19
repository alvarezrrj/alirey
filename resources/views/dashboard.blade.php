{{-- Dashboard: shows upcoming bookings --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <x-alert-message key="message" />

    <div class="py-12 space-y-6 xl:space-y-0">
      <div class="{{ $is_admin ? '' : 'max-w-7xl mx-auto '  }} grid grid-cols-12 gap-8 sm:px-6 lg:px-8 space-y-6 xl:space-y-0">

        <div class="col-span-12 xl:col-span-5 {{ $is_admin ? '2xl:col-span-4' :
        '' }} p-4 sm:p-8 text-gray-900 dark:text-gray-100 bg-white
        dark:bg-gray-800 shadow sm:rounded-lg ">
        
          <div class="max-w-2xl">
            <h3 class="mb-6 font-bold text-xl">{{ __('Upcoming session') }}</h3>

            @if(! $is_admin)
            <p class="mb-6">
              {{ __("Here are your next session's details, make sure your own
              details are correct so we have no trouble getting in touch with
              you. If you need to make any changes, you can") }}
              <a 
                class="text-brown underline hover:text-orange"
                href="{{ route('contact') }}"> 
              {{ __("contact us")}}. 
              </a>
            </p>
            @endif
          </div>

          @if(!$booking && !$is_admin)

            <p class="mt-6">
              {{ __('There\'s nothing over here')}}
              <x-primary-button class="mt-6">
                {{ __('Book yourself a slot') }}
              </x-primary-button>
            </p>

          @elseif(!$booking)

            <p class="mt-6">
              {{ __('There\'s nothing over here')}}
            </p>

          @else

            <livewire:booking 
              :booking="$booking"
            />

          @endif
          
        </div>

        @if($is_admin)
          <div class="col-span-12  xl:col-span-7 2xl:col-span-8 h-full">
            <livewire:appointments-calendar
              week-starts-at="1"
              initialLocale="{{ config('app.locale') }}"
              :drag-and-drop-enabled="false"
              :event-click-enabled="false"
              :day-click-enabled="false"/>
          </div>
        @endif

      </div>
    </div>

</x-app-layout>
