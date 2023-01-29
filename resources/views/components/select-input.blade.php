@props([
    'disabled' => false,
    'rounded' => 'rounded-md',
    'options',
    'label',
])

<select {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm '.$rounded]) !!}>
    <option value="" disabled selected>{{ __($label) }}</option>
    @foreach ($options as $option)
    <option value="{{ $option->id }}">
        {{ $option->content }}
    </option>
    @endforeach
</select>