@props([
    'disabled' => false,
    'rounded' => 'rounded-lg',
    'placeholder' => 'Select',
])

<select {!! $attributes->merge(['class' => 'py-2 pl-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 focus:ring-inset shadow-sm '.$rounded]) !!}>
    <option value="" disabled>{{ __($placeholder) }}</option>
    {{ $slot }}
</select>