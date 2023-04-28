@props([
    'codes',
    'label' => __('Select'),
    'rounded' => 'rounded-lg',
    'value' => null,
])

@push('third-party-styles')
<link href="{{ Vite::asset('resources/libraries/flexselect/flexselect.css') }}"
    rel="stylesheet" type="text/css" />
@endpush


<select {!! $attributes->merge(['class' => 'py-2 pl-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 focus:ring-inset shadow-sm '.$rounded]) !!}>
    <option value="" disabled @selected(!$value)>{{ __($label) }}</option>
    @foreach ($codes as $code)
    <option value="{{ $code->id }}" @selected($value == $code->id)>

        {!!
        preg_replace(
            '/U\+/',
            '&#x',
            preg_replace('/ /', ';', trim($code->flag))
            )
        !!}{!! $code->flag ? ';' : '&nbsp;&nbsp;&nbsp;&nbsp;' !!}

        &nbsp;+{{ $code->code }} {{ $code->country }}
    </option>
    @endforeach
</select>

{{-- Placeholder for flexselect's tw classes --}}
<div id="flexselect-classes" class="bg-slate-50  border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm rounded-md hidden"></div>


@push('libraries')

<script src="https://code.jquery.com/jquery-3.6.3.slim.min.js"
    type="text/javascript"></script>
<script src="{{ Vite::asset('resources/libraries/flexselect/liquidmetal.js') }}"
    type="text/javascript"></script>
<script src="{{ Vite::asset('resources/libraries/flexselect/jquery.flexselect.js') }}"
    type="text/javascript"></script>

@endpush

@push('scripts')

<script src="{{ Vite::asset('resources/js/register.js') }}"
    type="text/javascript"></script>

@endpush
