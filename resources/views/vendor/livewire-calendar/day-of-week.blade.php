<div class="flex-1 h-12 border dark:border-gray-900 -mt-px -ml-px flex items-center justify-center bg-brown/25 dark:bg-brown/25 text-gray-900 dark:text-gray-500 min-w-[3rem]"
>

    <p class="text-sm hidden md:block font-semibold dark:font-bold">
        {{ $day->format('l') }}
    </p>

    <p class="text-sm md:hidden font-semibold">
        {{ Str::upper(Str::limit($day->format('l'), 1, '')) }}
    </p>

</div>
