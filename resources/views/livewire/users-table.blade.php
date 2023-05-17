{{-- In work, do what you enjoy. --}}

<div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
  <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg ">

    <div class="mt-8 space-y-2 lg:pl-8">

      <x-text-input

        wire:model="search"
        class="block w-full px-4 py-2"
        placeholder="🔎 {{ __('Search') }}"/>

      <ul>
        {{-- Header --}}
        <li class="hidden grid-cols-10 p-2 px-4 py-3 font-bold text-gray-900 sm:grid dark:text-gray-100">
          <div class="col-span-1">{{ __('ID') }}</div>
          <div class="col-span-2">{{ __('Name') }}</div>
          <div class="col-span-3">{{ __('Email') }}</div>
          <div class="col-span-2">{{ __('Phone') }}</div>
          <div class="col-span-2"></div>
        </li>

        @if(count($users) == 0)
          <li class="px-4 py-3 my-6 mt-6 text-gray-900 md:my-0 dark:text-gray-100">
            {{ __('There\'s nothing over here 🏜') }}
          </li>
        @endif

        @foreach($users as $user)
          <li class="grid grid-cols-10 grid-rows-4 px-4 py-3 my-6 text-gray-900 border border-gray-300 rounded-md dark:border-gray-700 md:border-0 md:my-0 md:rounded-none gap-x-1 md:grid-cols-10 md:grid-rows-1 even:bg-gray-100 even:dark:bg-gray-900 hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-gray-100"
            wire:key='{{ $user->id }}'>
            <div class="col-span-1 row-span-4">
              {{ $user->id }}
            </div>
            <div class="col-span-9 overflow-hidden md:col-span-2 text-ellipsis">
              {{ $user->firstName }} {{ $user->lastName }}
            </div>
            <div class="col-span-9 overflow-hidden md:col-span-3 text-ellipsis">
              {{ $user->email }}
            </div>
            <div class="col-span-9 overflow-hidden md:col-span-2 text-ellipsis">
              @if(isset($user->code))
                +{{ $user->code->code }} {{ $user->phone }}
              @else
                -
              @endif
            </div>
            <div class="flex items-center justify-between col-span-9 md:mt-0 md:col-span-2 md:justify-end">
              <a href="{{ route('users.show', $user) }}" class="w-2/5">
                <x-primary-button :small="true" class="w-full">
                  {{ __('View') }}
                </x-primary-button>
              </a>

              <x-danger-button :small="true" x-data=""
                x-on:click.prevent="$dispatch('open-modal', '{{ $user->id }}-modal')"
                class="w-2/5 md:ml-1">
                {{ __('Delete') }}
              </x-danger-button>
            </div>

            {{-- Delete user modal --}}
            <x-modal name="{{ $user->id }}-modal" focusable>
              <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                  {{ __('Delete Account') }}
                </h2>

                <p class="mt-1 text-sm text-gray-800 dark:text-gray-200">
                  {{ __("Are you sure you want to delete this account? Once the account is deleted, all of its resources and data will be permanently deleted.") }}
                </p>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                  {{ __('ID') }}:&nbsp;{{ $user->id}}<br />
                  {{ __('Name') }}:&nbsp;{{ $user->firstName }} {{ $user->lastName }}<br />
                </p>

                <div class="flex justify-end mt-6">
                  <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                  </x-secondary-button>

                  <x-danger-button
                    class="ml-3"
                    x-on:click="$dispatch('close')"
                    wire:click.prevent="delete({{ $user->id }})">
                    {{ __('Delete') }}
                  </x-danger-button>

                </div>
              </div>
            </x-modal>

          </li>
        @endforeach

      </ul>

    </div>

  </div>
</div>
