<x-guest-layout>

  <x-slot:header>
    <h1 class="text-left font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Contact your therapist') }}
    </h1>
  </x-slot>

  <div class="rounded-md mb-4 overflow-hidden">
    <x-alert-message key="message"/>
  </div>

  <form method="POST" action="{{ route('contact.send') }}">
      @csrf

      <input type="hidden" name="therapist_id" value="{{ $therapist->id }}">
      <input type="hidden" name="user_id" value="{{ $user->id }}">

      <!-- Name -->
      <div>
          <x-input-label for="name" :value="__('Name')" />
          <x-text-input id="name" class="block mt-1 w-full" :readonly="$user" type="text" name="name"
            :value="old('name', $user?->firstName.' '.$user?->lastName)" required autofocus />
          <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div>

      <!-- Email Address -->
      <div class="mt-4">
          <x-input-label for="email" :value="__('Email')" />
          <x-text-input id="email" class="block mt-1 w-full" :readonly="$user" type="email" name="email" :value="old('email', $user?->email)" required />
          <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      <!-- About -->
      <div class="mt-4">
        <x-input-label for="subject" :value="__('Subject')" />
        <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" required />
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
      </div>

      <!-- Message -->
      <div class="mt-4">
        <x-input-label for="message" :value="__('Message')" />
        <x-text-area id="message" class="block mt-1 w-full" type="text" name="message" :value="old('message')" required></x-text-area>
        <x-input-error :messages="$errors->get('message')" class="mt-2" />
      </div>

      <div class="flex justify-end">
      <x-primary-button class="mt-4" type="submit">
          {{ __('Send') }}
      </x-primary-button>
      </div>

  </form>
</x-guest-layout>
