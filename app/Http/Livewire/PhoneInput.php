<?php

namespace App\Http\Livewire;

use App\Models\Code;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PhoneInput extends Component implements HasForms
{
    use InteractsWithForms;

    public $codes;

    public function mount()
    {
        $this->codes = DB::table('codes')
            ->orderBy('country', 'asc')
            ->get()
            ->keyBy('id')
            ->map(function ($code) {
                return $code->flag . ' +' . $code->code . ' ' . $code->country;
            });
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make([
                'default' => 1,
                'sm' => 4,
            ])->schema([
                Select::make('code_id')
                    ->columnSpan([
                        'sm' => 2,
                    ])
                    ->label(__('Country code'))
                    ->allowHtml()
                    ->searchable()
                    ->placeholder(__('Country'))
                    ->disablePlaceholderSelection()
                    ->options($this->codes)
                    ->exists(table: Code::class, column: 'id')
                    ->required()
                    ->extraAttributes(['class' => 'dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg']),
                TextInput::make('phone')
                    ->columnSpan([
                        'sm' => 2,
                    ])
                    ->label(__('Phone'))
                    ->inputMode('numeric')
                    ->string()
                    ->required()
                    ->rules(['max:255']),
            ])
        ];
    }

    public function render()
    {
        return view('livewire.phone-input');
    }

    public function save(Request $request)
    {
        $this->validate();

        $request->user()->update($this->form->getState());

        $this->emit('phone_saved');
    }
}
