<div x-data="" x-on:click="$dispatch('open-modal', 'phone-modal')"
  class="text-sm text-gray-700 cursor-pointer dark:text-gray-300">
  <span class="flex items-center">
    <x-antdesign-question-circle class="w-4 h-4"/>&nbsp;
    {{ __('Why do you need my number?') }}
  </span>
</div>

<x-modal name="phone-modal" focusable>
  <div class="p-6 text-gray-900 dark:text-gray-100" >
    <h2 class="text-lg font-semibold">
      {{ __('Why do you need my number?') }}
    </h2>

    <p class="mt-6">
      {{ __('You can give us your phone number if you want us to call you, but that\'s optional. You can leave it out, in which case it will be your responsibility to call us at the time of your session.') }}
    </p>

    <div class="flex justify-end mt-6">
      <x-primary-button
        type="button"
        x-data=""
        x-on:click="$dispatch('close')">
        {{ __('Ok') }}
      </x-primary-button>
    </div>

  </div>
</x-modal>
