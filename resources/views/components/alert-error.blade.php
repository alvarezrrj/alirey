{{-- Display session errors --}}
@props([
    'key' => '',
])

@error($key)
<div class="bg-red-500/50 mt-2 p-4  border-l-4 border-red-700"  >
    <span>{{ __($message) }} </span>
</div>
@enderror