{{-- Stop trying to control. --}}
<div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
  <div class="p-4 space-y-6 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">

      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ __('Sync with Google Calendar') }}
      </h2>

      <p class="text-gray-600 dark:text-gray-400">
          {{ __('Get all incoming bookings to be sent to your Google Calendar automatically') }}
      </p>

      <x-alert-message />

      <div class="max-w-xl sm:pl-8">
        <x-toggle
          id="google-sync-toggle"
          wire:model='config.google_sync'
          wire:click='save'/>
        <label class="inline-block text-gray-800 form-check-label dark:text-gray-200"
        for="google-sync-toggle">{{ __('Synchronise') }}
        </label>

        <x-oauth-popup :url="$popup_url" method="save" />
        @if($popup_url)
          <script id="{{ now() }}">
            window.open(
              '{{ $popup_url }}',
              'oauthPopup',
              'popup,width=500,height=815,left=100,top=100'
              );
          </script>
        @endif

      </div>

  </div>
</div>
