{{-- Big calendar --}}
@php
$is_holiday = $event['title'] == __('Closed');
$background = $is_holiday
? 'bg-red-300 dark:bg-red-700/40'
: 'bg-white dark:bg-gray-600';

if ($day->dayOfWeek == 1
 || $day->dayOfWeek == 2
 || $day->dayOfWeek == 3
 || $day->dayOfWeek == 4) {
  if ($weekIndex == 0 || $weekIndex == 1 || $weekIndex == 2)
    $alignment = 'top-left';
  if ($weekIndex == 3 || $weekIndex == 4) $alignment = 'bottom-left';
}
else {
  if ($weekIndex == 0 || $weekIndex == 1 || $weekIndex == 2)
    $alignment = 'top-right';
  if ($weekIndex == 3 || $weekIndex == 4) $alignment = 'bottom-right';
}
@endphp

<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="{{ $background }} dark:text-gray-100 dark:border-none
    rounded-lg border py-1 md:py-2 px-2 md:px-3 shadow-md hidden sm:block">

    <div class="text-sm font-medium  flex justify-between
    items-center">
      <p class="text-ellipsis overflow-hidden">
        {{ $event['title'] }}
      </p>
      <x-overflow-dropdown align="{{ $alignment }}" width="40">
        <x-slot name="trigger">
          <x-bi-three-dots-vertical class="cursor-pointer"/>
        </x-slot>

        <x-slot name="content" class="py-2">

          @unless($is_holiday)
          <x-dropdown-link :href="route('bookings.show', $event['id'])"
            class="flex justify-between">
            <span>{{ __('View booking') }}</span>
            <span><x-antdesign-swap-right-o class="h-4 inline-block ml-1"/></span>
          </x-dropdown-link>
          @else
            {{-- <form action={{ route('bookings.destroy', $event['id']) }}
            method="POST">
              @csrf
              @method('DELETE') --}}
              <x-dropdown-link class="flex justify-between"
                wire:click="destroy({{ $event['id'] }})">
                {{-- <button type="submit"> --}}
                <span>{{ __('Re-open') }}</span>
                <span><x-antdesign-swap-right-o class="h-4 inline-block ml-1"/></span>
                {{-- </button> --}}
              </x-dropdown-link>
            {{-- </form> --}}
          @endunless
        </x-slot>
      </x-overflow-dropdown>
    </div>
    <p class="mt-px text-xs">
        {{ $event['description'] ?? '' }}
    </p>
</div>

{{-- Small calendar (mobile) --}}
@php($mobile_title = $is_holiday
? '🚫'
: Str::limit($event['title'], 1, ''))
<x-overflow-dropdown width="w-40" triggerClasses="w-full" align="{{ $alignment }}">
  <x-slot name="trigger">
    <div class="{{ $background }} dark:text-gray-100 dark:border-none border
    h-4 w-full shadow cursor-pointer sm:hidden rounded-full text-xs flex
    items-center justify-center leading-none" >
      <span class="w-full inline-block text-center">
        {{ $mobile_title }}
      </span>
    </div>
  </x-slot>

  <x-slot name="content" class="py-2">
    <p class="px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 cursor-default">
      {{ $event['title'] }} &middot; {{ $event['description'] ?? '' }}
    </p>

    @unless($is_holiday)
      <x-dropdown-link :href="route('bookings.show', $event['id'])"
        class="flex justify-between">
        <span>{{ __('View booking') }}</span>
        <span><x-antdesign-swap-right-o class="h-4 inline-block ml-1"/></span>
      </x-dropdown-link>
    @else
      <form action={{ route('bookings.destroy', $event['id']) }}
      method="POST">
        @csrf
        @method('DELETE')
        <x-dropdown-link
          type="submit"
          class="flex justify-between">
          <span>{{ __('Re-open') }}</span>
          <span><x-antdesign-swap-right-o class="h-4 inline-block ml-1"/></span>
        </x-dropdown-link>
      </form>
    @endunless
  </x-slot>
</x-overflow-dropdown>
