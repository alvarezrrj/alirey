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

<button {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md text-xs text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'.$size]) }}>
    {{ $slot }}
</button>
