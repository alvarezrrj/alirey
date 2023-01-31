@props([
    'disabled' => false,
    'small' => false,
    'type' => 'submit',
])

@php 
    $size = $small 
    ? ' px-2 py-1 font-medium tracking-wider'
    : ' uppercase px-4 py-2 font-semibold tracking-widest';
@endphp

<button {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center bg-red-600 border border-transparent rounded-md text-xs text-white hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'.$size]) }}>
    {{ $slot }}
</button>
