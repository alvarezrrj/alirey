@props([
  'url' => null,
  'method'
])

@if($url)
  <script id="{{ now() }}">
    window.open(
      '{{ $url }}',
      'oauthPopup',
      'popup,width=500,height=815,left=100,top=100'
      );
  </script>
@endif

<div
  class="hidden"
  x-data
  {{-- A message is 'posted' from the popup window when the OAuth flow is completed --}}
  x-on:message.window="
    if($event.data.oAuthResult === 'ok') {
      $wire.{{ $method }}();
    } else if($event.data.oAuthResult === 'error') {
      notif.i('{{ __('Something went wrong') }}', false)
    }">
</div>
