<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    {{ $this->form}}


    <div class="flex justify-end mt-3">
      <x-primary-button
        class="min-w-[12rem]"
        wire:click='save'>
        <span wire:loading.class='hidden'>
          {{ __('You call me') }}
        </span>
        <span wire:loading wire:target='save'>
          <x-spinner />
        </span>
      </x-primary-button>
    </div>
</div>
