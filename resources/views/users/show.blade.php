{{-- User details --}}
<x-app-layout>

  <x-slot name="header">

    <div class="flex items-center">
        <a href="{{ route('users.index') }}">
            <x-bi-caret-left class="text-gray-800 dark:text-gray-200" width="20" height="20"/>
        </a>

      <h2 class="inline-block ml-2 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ __('User details') }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12 space-y-6">
    <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

      <x-alert-message key="message" />

      <div class="p-4 text-gray-900 bg-white shadow sm:p-8 dark:text-gray-100 dark:bg-gray-800 sm:rounded-lg ">

        <div class="max-w-xl sm:pl-8">

          <div class="flex justify-between">
            <h3 class="font-bold ">
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

          <table class="w-full mt-6 text-gray-900 dark:text-gray-100">
            <tbody class="divide-y">
              <tr>
                <th class="p-2 text-left ">
                  {{ __('Name') }}
                </th>
                <td class="p-2 text-right ">
                  {{ $user->firstName }}
                  {{ $user->lastName }}
                </td>
              </tr>

              <tr>
                <th class="p-2 text-left ">
                  {{ __('Email') }}<br>
                </th>
                <td class="p-2 text-right ">
                  {{ $user->email }}
                </td>
              </tr>

              <tr>
                <th class="p-2 text-left ">
                  {{ __('Email veirified') }}
                </th>
                <td class="p-2 text-right">
                  {{ $user->email_verified_at ? '✅' : '❌'}}
                </td>
              </tr>

              <tr>
                <th class="p-2 text-left ">
                  {{ __('Phone') }}
                </th>
                <td class="p-2 text-right">
                @if(isset($user->code))
                  +{{ $user->code->code }} {{ $user->phone }}
                @else
                  -
                @endif
                </td>
              </tr>

              <tr>
                <th class="p-2 text-left ">
                  {{ __('Registration date') }}
                </th>
                <td class="p-2 text-right">
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
