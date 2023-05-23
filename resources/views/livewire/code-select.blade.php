  {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div>
  @push('styles')
  {{-- Fix searchable select dark mode issue untill filament team sort it out --}}
  <style>
    .choices {
      background: inherit;
      border-color: inherit;
      color: inherit;
      border-radius: inherit;
    }
    .choices__inner {
      background: inherit;
      border-color: inherit;
      color: inherit;
      border-radius: inherit;
    }
    .choices__list--dropdown {
      background: inherit !important;
      border-color: inherit !important;
      color: inherit !important;
      border-radius: inherit !important;
    }
  </style>
  @endpush

    {{ $this->form }}
    <input type="hidden" name="code_id" wire:model='code_id' />
</div>
