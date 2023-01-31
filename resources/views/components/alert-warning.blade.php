{{-- Display session alerts --}}
@props([
    'key' => '',
])

@error($key)
<div class="bg-amber-400/50 mt-2 p-4  border-l-4 border-amber-500"  >
    <span>{{ __($message) }} </span>
</div>
@enderror