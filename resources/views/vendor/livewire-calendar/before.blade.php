<div class="flex space-x-2 mb-2" >

  <button class="w-11 h-7 inline-flex items-center justify-center  border
  border-brown rounded-md  hover:bg-gray-200/25 dark:hover:bg-gray-800/25
  focus:bg-gray-200/25 dark:focus:bg-gray-800/25 active:bg-gray-100/25
  dark:active:bg-gray-300/25 focus:outline-none focus:ring-2 focus:ring-orange
  dark:focus:ring-offset-gray-800 transition ease-in-out
  duration-150 px-4 py-2"
  wire:click="goToPreviousMonth">
    <x-antdesign-left-o class="w-6 text-brown"/>
  </button>{{-- 
    This comment is here to remove the line break between elements, which
    blade renders as a whitespace for some reason I don't yet know how to 
    bypass
  --}}<span 
  class="h-7 py-1 px-2 text-sm text-brown border border-brown rounded-md ">
    {{ Carbon\Carbon::now()->format('j F')}}
  </span>

  <button class="w-11 h-7 inline-flex items-center justify-center  border
  border-brown rounded-md  hover:bg-gray-200/25 dark:hover:bg-gray-800/25
  focus:bg-gray-200/25 dark:focus:bg-gray-800/25 active:bg-gray-100/25
  dark:active:bg-gray-300/25 focus:outline-none focus:ring-2 focus:ring-orange
  dark:focus:ring-offset-gray-800 transition ease-in-out
  duration-150 px-4 py-2"
  wire:click="goToNextMonth">
    <x-antdesign-right-o class="w-6 text-brown"/>
  </button>

  <span 
  class="inline-block text-center min-w-[10em] h-7 py-1 px-2 text-sm border border-gray-500 text-gray-500 rounded-md ">
    {{ $startsAt->format('F Y') }}
  </span>
</div>