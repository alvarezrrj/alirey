{{-- Display a session's flash messages --}}
@props([
    'key' => 'message',
])

@if (session()->has($key))
<div {{ $attributes->merge(['class' => "bg-green-500/50 mt-2 p-4  border-l-4 border-green-500"]) }}  >
    {{ __(session($key)) }}
</div>
@endif
