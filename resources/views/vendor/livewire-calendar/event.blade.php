{{-- Big calendar --}}
<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="bg-white dark:bg-gray-600 dark:text-gray-100 dark:border-none
    rounded-lg border py-1 md:py-2 px-2 md:px-3 shadow-md hidden sm:block">

    <div class="text-sm font-medium  flex justify-between 
    items-center">
      <p class="text-ellipsis overflow-hidden">
        {{ $event['title'] }}
      </p>
      <x-overflow-dropdown align="right" width="40">
        <x-slot name="trigger">
          <x-bi-three-dots-vertical class="cursor-pointer"/>
        </x-slot>

        <x-slot name="content" class="py-2">

          <x-dropdown-link :href="route('bookings.show', $event['id'])" 
            class="flex justify-between">
            <span>{{ __('View booking') }}</span>
            <span><x-antdesign-swap-right-o class="h-4 inline-block ml-1"/></span>
          </x-dropdown-link>
        </x-slot>
      </x-overflow-dropdown>
    </div>
    <p class="mt-px text-xs">
        {{ $event['description'] ?? '' }}
    </p>
</div>

{{-- Small calendar (mobile) --}}
  <x-overflow-dropdown width="40" triggerClasses="w-full">
    <x-slot name="trigger">
      <div class="bg-white dark:bg-gray-600 dark:text-gray-100 dark:border-none border
      h-4 w-full shadow cursor-pointer sm:hidden rounded-full text-xs flex
      items-center justify-center leading-none" >
        <span class="w-full inline-block text-center">
          {{ Str::limit($event['title'], 1, '') }}
        </span>
      </div>
    </x-slot>

    <x-slot name="content" class="py-2">
      <p class="px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 cursor-default">
        {{ $event['title'] }} &middot; {{ $event['description'] ?? '' }}
      </p>

      <x-dropdown-link :href="route('bookings.show', $event['id'])" 
        class="flex justify-between">
        <span>{{ __('View booking') }}</span>
        <span><x-antdesign-swap-right-o class="h-4 inline-block ml-1"/></span>
      </x-dropdown-link>
    </x-slot>
  </x-overflow-dropdown>