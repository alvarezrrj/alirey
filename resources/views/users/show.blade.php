{{-- User details --}}
<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
        <a href="{{ route('users.index') }}">
            <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
        </a>

      <h2 class="inline-block ml-2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('User details') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      <x-alert-message key="message" />

      <div class="p-4 sm:p-8 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 shadow sm:rounded-lg ">

        <div class="max-w-xl sm:pl-8">

          <div class="flex justify-between">
            <h3 class=" font-bold">
              {{ __('User ID') }}:&nbsp;{{ $user->id }}
            </h3>
            <a href="{{ route('users.edit', $user) }}">
              <x-primary-button class="w-full sm:w-auto">
                <x-antdesign-edit-o width="18" height="18"/>
                &nbsp;
                <span>
                {{ __('Edit') }}
                </span>
              </x-primary-button>
            </a>
          </div>

          <table class="text-gray-900 dark:text-gray-100 w-full mt-6">
            <tbody class="divide-y">
              <tr>
                <th class="text-left p-2 ">
                  {{ __('Name') }}
                </th>
                <td class="text-right p-2 ">
                  {{ $user->firstName }}
                  {{ $user->lastName }}
                </td>
              </tr>

              <tr>
                <th class="text-left p-2 ">
                  {{ __('Email') }}<br>
                </th>
                <td class="text-right p-2 ">
                  {{ $user->email }}
                </td>
              </tr>

              <tr>
                <th class="text-left p-2 ">
                  {{ __('Email veirified') }}
                </th>
                <td class="text-right p-2">
                  {{ $user->email_verified_at ? '✅' : '❌'}}
                </td>
              </tr>

              <tr>
                <th class="text-left p-2 ">
                  {{ __('Phone') }}
                </th>
                <td class="text-right p-2">
                  +{{ $user->code->code }}&nbsp;
                  {{ $user->phone }}
                </td>
              </tr>

              <tr>
                <th class="text-left p-2 ">
                  {{ __('Registration date') }}
                </th>
                <td class="text-right p-2">
                  {{ $user->created_at->format('d/m/Y') }}
                </td>
              </tr>
            </tbody>
          </table>

        </div>

      </div>

    </div>
  </div>

</x-app-layout>
