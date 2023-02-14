<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
    :codes="$codes"
>

@push('third-party-styles')
<link href="{{ Vite::asset('resources/libraries/flexselect/flexselect.css') }}"
    rel="stylesheet" type="text/css" />
@endpush

  <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}').defer }">
    <!-- Interact with the `state` property in Alpine.js -->
    <select wire.model="{{ $getStatePath() }}" class="block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-500 focus:ring-1 focus:ring-inset focus:ring-primary-500 disabled:opacity-70 dark:bg-gray-700 dark:text-white dark:focus:border-primary-500 border-gray-300 dark:border-gray-600">
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
  </div>

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

</x-dynamic-component>