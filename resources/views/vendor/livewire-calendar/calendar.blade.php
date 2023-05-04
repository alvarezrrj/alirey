<div
@if($pollMillis !==null && $pollAction !==null)
  wire:poll.{{ $pollMillis }}ms="{{ $pollAction }}"
@elseif($pollMillis !==null)
  wire:poll.{{ $pollMillis }}ms
@endif>
  <div>
    @includeIf($beforeCalendarView)
  </div>

  <div class="flex shadow">
    {{-- <div class="overflow-x-auto w-full"> --}}
      <div class="min-w-full">

        <div class="w-full flex flex-row sticky top-0">
          @foreach($monthGrid->first() as $day)
            @include($dayOfWeekView, ['day' => $day])
          @endforeach
        </div>

        @foreach($monthGrid as $weekIndex => $week)
        <div class="w-full flex flex-row">
          @foreach($week as $day)
            @include($dayView, [
            'componentId' => $componentId,
            'day' => $day,
            'weekIndex' => $weekIndex,
            'dayInMonth' => $day->isSameMonth($startsAt),
            'isToday' => $day->isToday(),
            'events' => $getEventsForDay($day, $events),
            ])
          @endforeach
        </div>
        @endforeach
      {{-- </div> --}}
    </div>
  </div>

  <div>
    @includeIf($afterCalendarView)
  </div>

</div>
