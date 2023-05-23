<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="firstName" :value="__('Name')" />
            <x-text-input id="firstName" name="firstName" type="text" class="block w-full mt-1" :value="old('firstName', $user->firstName)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('firstName')" />
        </div>

        <div>
            <x-input-label for="lastName" :value="__('Last Name')" />
            <x-text-input id="lastName" name="lastName" type="text" class="block w-full mt-1" :value="old('lastName', $user->lastName)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('lastName')" />
        </div>

        <!-- Telephone Number -->
        <div class="mt-4">
            <livewire:code-select :code_id="old('code_id', $user->code_id)" />

            <x-input-label for="phone" :value="__('Telephone Number')" class="mt-6"/>
            <x-text-input id="phone" inputmode="numeric"
              class="inline-block w-full mt-1" type="text" name="phone"
              :value="old('phone', $user->phone )" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            <x-input-error :messages="$errors->get('code_id')" class="mt-2" />

            @if(! $user->isAdmin())
              <p class="grid grid-rows-2 mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                <span class="flex items-center">
                  <x-checkbox name="prefers_calling" value="1" :checked="old('prefers_calling', $user->prefers_calling)"/>
                  {{ __('I prefer to call you') }}
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                  {{ __('Check this box to stop us from asking your number.') }}
                </span>
              </p>
              <x-input-error :messages="$errors->get('prefers_calling')" class="mt-2" />
            @endif
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
