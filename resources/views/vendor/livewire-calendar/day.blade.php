
<div
    ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragover="onLivewireCalendarEventDragOver(event);"
    ondrop="onLivewireCalendarEventDrop(event, '{{ $componentId }}', '{{ $day }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }}, '{{ $dragAndDropClasses }}');"
    class="flex-1 h-24 sm:h-32 md:h-40 lg:h-48 border border-gray-200
    dark:border-gray-900 -mt-px -ml-px min-w-[3rem]"
    >

    {{-- Wrapper for Drag and Drop --}}
    <div
        class="w-full h-full"
        id="{{ $componentId }}-{{ $day }}">

        <div
            @if($dayClickEnabled)
                wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})"
            @endif
            class="w-full h-full p-2 grid grid-cols-1 grid-rows-6
            {{ $dayInMonth 
            ?  $isToday 
              ? 'bg-skin dark:bg-yellow-400/25'
              : ' bg-white dark:bg-gray-800' 
            : 'bg-gray-100 dark:bg-gray-700' }} ">

            {{-- Number of Day --}}
            <div class="flex items-center row-span-1">
                <p class="text-sm {{ $dayInMonth ? ' font-medium ' : 'text-gray-500' }}">
                    {{ $day->format('j') }}
                </p>
                {{-- event count
                <p class="text-xs text-gray-600 ml-4">
                    @if($events->isNotEmpty())
                        {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                    @endif
                </p>
                --}}
            </div>

            {{-- Events --}}
            
            <div class="h-full flex flex-col relative row-span-5">
              <div class="mt-1 overflow-y-scroll">
                  <div class="pb-1 grid grid-cols-1 sm:grid-cols-1 grid-flow-row gap-2 ">
                      @foreach($events as $event)
                          <div 
                              @if($dragAndDropEnabled)
                                  draggable="true"
                              @endif
                              ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                              @include($eventView, [
                                  'event' => $event,
                              ])
                          </div>
                      @endforeach
                  </div>
              </div>
            </div>

        </div>
    </div>
</div>
