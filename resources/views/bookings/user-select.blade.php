<div class="grid grid-cols-10 grid-rows-2">
  <div class="grid col-span-1 row-span-2 place-items-center">
    @if($user->avatar)
      <img class="w-full p-2 rounded-full" src="{{ $user->avatar }}"
      referrerpolicy="no-referrer">
    @else
      <x-bi-person-circle class="w-full h-full p-2"/>
    @endif
  </div>
  <p class="col-span-9 row-span-1 pt-2 ps-2">
    {{ $user->firstName }} {{ $user->lastName }}
  </p>
  <p class="col-span-9 row-span-1 pb-2 text-sm ps-2">
    {{ $user->email }}
  </p>
</div>
