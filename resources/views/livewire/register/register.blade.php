{{-- Nothing in the world is as soft and yielding as water. --}}

@push('styles')
{{-- Fix searchable select dark mode issue untill filament team sort it out --}}
<style>
  .choices {
    background: inherit;
    border-color: inherit;
    color: inherit;
    border-radius: inherit;
  }
  .choices__inner {
    background: inherit;
    border-color: inherit;
    color: inherit;
    border-radius: inherit;
  }
  .choices__list--dropdown {
    background: inherit !important;
    border-color: inherit !important;
    color: inherit !important;
    border-radius: inherit !important;
  }
</style>

@endpush

@php($action = isset($user) ? 'update' : 'insert')
<form wire:submit.prevent="{{ $action }}">

  {{ $this->form }}

  <div class="flex items-center justify-end mt-4">
    @unless(isset($user) || Auth::user())
    <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
        {{ __('Already registered?') }}
    </a>
    @endunless

    <x-primary-button class="ml-4 min-w-[10rem]">
      <span wire:loading.class='hidden'>
        @unless(isset($user) || Auth::user())
          {{ __('Register') }}
        @else
          {{ __('Save') }}
        @endunless
      </span>
      <span wire:loading wire:target="insert,update">
        <x-spinner />
      </span>
    </x-primary-button>
  </div>

  {{-- <pre class="text-white">
    {{ var_dump($authenticated) }}
  </pre> --}}

</form>

