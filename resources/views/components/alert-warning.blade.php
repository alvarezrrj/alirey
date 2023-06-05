{{-- Display session alerts --}}
@props([
    'key' => '',
    'message' => null
])

@if($key)

  @error($key)
  <div class="p-4 border-l-4 bg-amber-400/50 border-amber-500"  >
      <span>{{ __($message) }} </span>
  </div>
  @enderror

@else

  <div class="p-4 border-l-4 bg-amber-400/50 border-amber-500"  >
      <span>{{ __($message) }} </span>
  </div>

@endif
