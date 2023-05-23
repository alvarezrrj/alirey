<?php

namespace App\Http\Livewire;

use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CodeSelect extends Component implements HasForms
{
    use InteractsWithForms;

    public $codes;

    public $code_id;

    public function mount()
    {
        $this->codes = DB::table('codes')
            ->orderBy('country', 'asc')
            ->get()
            ->keyBy('id')
            ->map(function ($code) {
                return $code->flag . ' +' . $code->code . ' ' . $code->country;
            });
        $this->form->fill([
            'code_id' => $this->code_id ?? null
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('code_id')
                ->label(__('Country code'))
                ->allowHtml()
                ->searchable()
                ->placeholder(__('Country'))
                ->disablePlaceholderSelection()
                ->options($this->codes)
                ->exists(table: Code::class, column: 'id')
                ->reactive()
                ->extraAttributes(['class' => 'dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg'])
        ];
    }
    public function render()
    {
        return view('livewire.code-select');
    }
}
