@push('scripts')
  <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endpush
<x-guest-layout>

  <x-slot:header>
    <h1 class="text-xl font-semibold leading-tight text-left text-gray-800 dark:text-gray-200">
      {{ __('Contact site administrator') }}
    </h1>
  </x-slot>

  <div class="mt-2 mb-4 overflow-hidden rounded-md">
    <x-alert-message key="message"/>
  </div>

  <form method="POST" action="{{ route('contact.webmaster.send') }}"  enctype="multipart/form-data">
      @csrf

      {{-- About --}}
      <div class="mt-4">
        <x-input-label for="subject" :value="__('Subject')" />
        <x-text-input id="subject" class="block w-full mt-1" type="text" name="subject" :value="old('subject')" :placeholder="__('Optional')"/>
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
      </div>

      {{-- Screenshot --}}
      <div class="mt-4">
        <x-input-label for="screenshot" :value="__('Screenshot').' '.__('(optional)')" />
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          {{ __("Including one helps us identify the issue.") }}
        </p>
        <x-text-input accept="image/*" id="screenshot" class="block w-full mt-1" type="file" name="screenshot" :value="old('screenshot')"/>
        <x-input-error :messages="$errors->get('screenshot')" class="mt-2" />
      </div>

      {{-- Message --}}
      <div class="mt-4">
        <x-input-label for="message" :value="__('Message')" />
        <x-text-area id="message" class="block w-full mt-1" type="text" name="message" required>{{ old('message') }}</x-text-area>
        <x-input-error :messages="$errors->get('message')" class="mt-2" />
      </div>

      {{-- Cloudflare turnstile (captcha thing) --}}
      <div class="mt-4 cf-turnstile" data-sitekey="0x4AAAAAAAPPdpuInAQsFzEK"></div>

      <div class="flex justify-end">
        <x-primary-button class="mt-4" type="submit">
            {{ __('Send') }}
        </x-primary-button>
      </div>

  </form>
</x-guest-layout>
