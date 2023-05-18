@php($therapist = App\Models\Role::where('role',
SD::admin)->first()->users()->first())

@push('styles')
  @vite('resources/css/legal-styles.css')
@endpush

<x-site-layout>

  <main
    class="max-w-screen-md px-4 mx-auto space-y-14 dark:text-gray-200 sm:px-16 lg:px-18 xl:px-22 2xl:px-24">

    <h1 class="mt-8 text-xl text-center">{{ __('legal.privacy.header') }}</h1>
    <p>
      {{ __('legal.privacy.intro') }}
    </p>
    <ul class="list-disc list-inside">
      <li>
        {{ __('legal.privacy.item1') }}
      </li>
      <li>
        {{ __('legal.privacy.item2') }}
        </li>
      <li>
        {{ __('legal.privacy.item3') }}
        </li>
      <li>
        {{ __('legal.privacy.item4') }}
      </li>
    </ul>

    <h2>1. {{ __('legal.privacy.collection_heading') }}</h2>
    <p>{{ __('legal.privacy.collection1') }}</p>
    <p>{{ __('legal.privacy.collection2') }}</p>
    <p>{{ __('legal.privacy.collection3') }}</p>

    <h2>2. {{ __('legal.privacy.access_heading') }}</h2>
    <p>{{ __('legal.privacy.access_intro') }}</p>
    <ul class="list-disc list-inside">
      <li>{{ __('legal.privacy.access1') }}</li>
      <li>{{ __('legal.privacy.access2') }}</li>
      <li>{{ __('legal.privacy.access3') }}</li>
      <li>{{ __('legal.privacy.access4') }}</li>
    </ul>

    <h2>3. {{ __('legal.privacy.security_heading') }}</h2>
    <p>{{ __('legal.privacy.security1') }}</p>
    <p>{{ __('legal.privacy.security3') }}</p>

    <h2>4. {{ __('legal.privacy.updates_heading') }}</h2>
    <p>{{ __('legal.privacy.updates1') }}</p>
    <p>{{ __('legal.privacy.updates2') }}</p>

    </main>
</x-site-layout>
