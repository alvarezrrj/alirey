<x-guest-layout>

  <x-slot:header>
    <h1 class="text-left font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Contact site administrator') }}
    </h1>
  </x-slot>

  <div class="rounded-md mb-4 mt-2 overflow-hidden">
    <x-alert-message key="message"/>
  </div>

  <form method="POST" action="{{ route('contact.webmaster.send') }}"  enctype="multipart/form-data">
      @csrf

      <!-- Name -->
      {{-- <div>
          <x-input-label for="name" :value="__('Name')" />
          <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
            :value="old('name')" autofocus />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div> --}}

      <!-- Email Address -->
      {{-- <div class="mt-4">
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div> --}}

      <!-- About -->
      <div class="mt-4">
        <x-input-label for="subject" :value="__('Subject')" />
        <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" :placeholder="__('Optional')"/>
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
      </div>

      <!-- Screenshot -->
      <div class="mt-4">
        <x-input-label for="screenshot" :value="__('Screenshot').' '.__('(optional)')" />
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __("Including one helps us identify the issue.") }}
        </p>
        <x-text-input accept="image/*" id="screenshot" class="block mt-1 w-full" type="file" name="screenshot" :value="old('screenshot')"/>
        <x-input-error :messages="$errors->get('screenshot')" class="mt-2" />
      </div>

      <!-- Message -->
      <div class="mt-4">
        <x-input-label for="message" :value="__('Message')" />
        <x-text-area id="message" class="block mt-1 w-full" type="text" name="message" required>{{ old('message') }}</x-text-area>
        <x-input-error :messages="$errors->get('message')" class="mt-2" />
      </div>

      <div class="flex justify-end">
      <x-primary-button class="mt-4" type="submit">
          {{ __('Send') }}
      </x-primary-button>
      </div>

  </form>
</x-guest-layout>
