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

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md  text-xs text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150'.$size]) }}>
    {{ $slot }}
</button>
