@php($therapist = App\Models\Role::where('role',
SD::admin)->first()->users()->first())
@push('styles')
@vite('resources/css/legal-styles.css')
@endpush
<x-site-layout>

  <main
    class="max-w-screen-md px-4 mx-auto space-y-14 dark:text-gray-200 sm:px-16 lg:px-18 xl:px-22 2xl:px-24">

    <h1 class="mt-8 text-xl text-center">{{ __('legal.terms.header') }}</h1>
    <h2>1. {{ __('legal.terms.terms') }}</h2>
    <p>
      {{ __('legal.terms.welcome') }}
    </p>
    <p>
      {{ __('legal.terms.intro') }}
    </p>
    <h2>2. {{ __('legal.terms.use_license') }}</h2>
    <p>
      {{ __('legal.terms.permission') }}
    </p>
    <ul class="list-disc list-inside">
      <li>{{ __('legal.terms.permission1') }} </li>
      <li>{{ __('legal.terms.permission2') }}</li>
      <li>{{ __('legal.terms.permission3') }}</li>
      <li>{{ __('legal.terms.permission4') }}</li>
    </ul>
    <p>
      {{ __('legal.terms.terminate') }}
    </p>
    <h2>3. {{__('legal.terms.disclaimer_heading') }}</h2>
    <p>
      {{ __('legal.terms.disclaimer') }}
    </p>
    <h2>4. {{ __('legal.terms.limitations_heading') }}</h2>
    <p>
      {{ __('legal.terms.limitations')}}
    </p>
    <h2>5. {{ __('legal.terms.revision_heading') }}</h2>
    <p>
      {{ __('legal.terms.revision') }}
    </p>
    <h2>6. {{ __('legal.terms.links_heading') }}</h2>
    <p>
      {{ __('legal.terms.links')}}
    </p>
    <h2>7. {{ __('legal.terms.terms_mods_heading') }}</h2>
    <p>
      {{ __('legal.terms.terms_mods') }}

    </p>
    <h2>8. {{ __('legal.terms.your_privacy_heading') }}</h2>
    <p>
      {{ __('legal.terms.your_privacy') }}
    <h2>9. {{ __('legal.terms.govering_heading') }}</h2>
    <p>
      {{ __('legal.terms.governing') }}
    </p>

    <h2>10. {{ __('legal.terms.changes_heading') }}</h2>
    <p>{{ __('legal.terms.changes_intro') }}</p>

    <p>{{ __('legal.terms.changesA') }}</p>
    <p>{{ __('legal.terms.changesB') }}</p>
    <p>{{ __('legal.terms.changesC') }}</p>
    <p>{{ __('legal.terms.changesD') }}</p>
    <p></p>

    <p>
      {{ __('legal.terms.footer') }}
       <a
      href="https://www.termsfeed.com/terms-service-generator/">Terms Of
      Service Generator</a>.
    </p>

  </main>
</x-site-layout>
