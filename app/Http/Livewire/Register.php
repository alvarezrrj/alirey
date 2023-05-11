<?php

namespace App\Http\Livewire;

use App\Models\Code;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\SD\SD;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component implements HasForms
{
    use InteractsWithForms;

    private $codes;
    public $data;
    public $role_id;

    public function mount()
    {
        $this->codes = DB::table('codes')
            ->orderBy('country', 'asc')
            ->get()
            ->keyBy('id')
            ->map(function ($code) {
                return $code->flag . ' +' . $code->code . ' ' . $code->country;
            });

        // Initialize filament form
        $this->role_id =  Role::where('role', SD::client)->first()->id;
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.register');
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('firstName')
                ->label(__('Name'))
                ->autofocus()
                ->required()
                ->rules(['max:255'])
                ->string(),
            TextInput::make('lastName')
                ->label(__('Last name'))
                ->required()
                ->rules(['max:255'])
                ->string(),
            TextInput::make('email')
                ->label(__('Email'))
                ->email()
                ->rules(['max:255'])
                ->required()
                ->unique(table: User::class),
            Select::make('code_id')
                ->label(__('Country code'))
                ->allowHtml()
                ->searchable()
                ->placeholder(__('Country'))
                ->disablePlaceholderSelection()
                ->options($this->codes)
                ->required()
                ->exists(table: Code::class, column: 'id')
                ->extraAttributes(['class' => 'dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg']),
            TextInput::make('phone')
                ->label(__('Phone'))
                ->inputMode('numeric')
                ->string()
                ->rules(['max:255'])
                ->required(),
            TextInput::make('password')
                ->confirmed()
                ->label(__('Password'))
                ->password()
                ->rules([Password::defaults()])
                ->required(),
            TextInput::make('password_confirmation')
                ->label(__('Confirm password'))
                ->password()
                ->required(),
        ];
    }

    public function submit(): RedirectResponse
    {
        $user = User::create(Arr::add(
            $this->form->getState(),
            'role_id',
            $this->role_id,
        ));

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
