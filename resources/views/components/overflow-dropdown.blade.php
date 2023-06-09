{{-- This dropdown doesn't have a relatively positioned parent, it needs to be
given one. This is useful when the dropdown is inside an overflow-y-scroll
element, which makes the dropdown not show past the horizontal edges of the
scrolling element. See https://css-tricks.com/popping-hidden-overflow/ --}}
@props([
  'align' => 'right',
  'width' => 'w-48',
  'contentClasses' => 'py-2 bg-white dark:bg-gray-700',
  'triggerClasses' => null])

@php
switch ($align) {
    case 'top-left':
        $alignmentClasses = 'origin-top-left left-0 top-0';
        break;
    case 'top-right':
        $alignmentClasses = 'origin-top-right right-0 top-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'bottom-left':
        $alignmentClasses = 'origin-bottom bottom-0 left-0';
        break;
    case 'bottom-right':
        $alignmentClasses = 'origin-bottom-right bottom-0 right-0';
        break;
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0';
        break;
}
@endphp

<div class="" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
  <div @click="open = ! open" class="{{ $triggerClasses}}">
      {{ $trigger }}
  </div>

  <div x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="transform opacity-0 scale-95"
    x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
    style="display: none;"
    @click="open = false">
    <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
        {{ $content }}
    </div>
  </div>
</div>
