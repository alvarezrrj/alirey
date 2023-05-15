<?php

namespace App\Http\Livewire;

use App\Models\Code;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\SD\SD;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\Redirector;

/**
 * This is where therapists can register and update clients
 */

class Register extends Component implements HasForms
{
    use InteractsWithForms;

    private $codes;
    public $role_id;
    public ?string $back_url;

    public ?User $user;

    public function mount()
    {
        $this->codes = DB::table('codes')
            ->orderBy('country', 'asc')
            ->get()
            ->keyBy('id')
            ->map(function ($code) {
                return $code->flag . ' +' . $code->code . ' ' . $code->country;
            });

        $this->role_id =  Role::where('role', SD::client)->first()->id;
        // Initialize filament form
        $this->form->fill([
            'firstName' => $this->user->firstName ?? null,
            'lastName' => $this->user->lastName ?? null,
            'email' => $this->user->email ?? null,
            'code_id' => $this->user->code_id ?? null,
            'phone' => $this->user->phone ?? null,
        ]);
    }

    public function render()
    {
        return view('livewire.register');
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make([
                'default' => 1,
                'sm' => 4,
            ])->schema([
                TextInput::make('firstName')
                    ->columnSpan([
                        'sm' => 2,
                    ])
                    ->label(__('Name'))
                    ->autofocus()
                    ->required()
                    ->rules(['max:255'])
                    ->string(),
                TextInput::make('lastName')
                    ->columnSpan([
                        'sm' => 2,
                    ])
                    ->label(__('Last Name'))
                    ->required()
                    ->rules(['max:255'])
                    ->string(),
                TextInput::make('email')
                    ->columnSpan('full')
                    ->label(__('Email'))
                    ->email()
                    ->rules(['max:255'])
                    ->required()
                    ->unique(
                        table: User::class,
                        ignorable: $this->user ?? null),
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
                    ->required()
                    ->exists(table: Code::class, column: 'id')
                    ->extraAttributes(['class' => 'dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg']),
                TextInput::make('phone')
                    ->columnSpan([
                        'sm' => 2,
                    ])
                    ->label(__('Phone'))
                    ->inputMode('numeric')
                    ->string()
                    ->rules(['max:255'])
                    ->required(),
                TextInput::make('password')
                    ->columnSpan('full')
                    ->label(__('Password'))
                    ->password()
                    ->autocomplete('new-password')
                    ->confirmed()
                    ->rules([Password::defaults()])
                    ->required(! isset($this->user) && ! Auth::user())
                    ->hint(__('At least :x characters', ['x' => 8]))
                    ->hidden(isset($this->user) || Auth::user()),
                TextInput::make('password_confirmation')
                    ->columnSpan('full')
                    ->label(__('Confirm Password'))
                    ->password()
                    ->autocomplete('new-password')
                    ->required(! isset($this->user) && ! Auth::user())
                    ->hidden(isset($this->user) || Auth::user()),
            ])

        ];
    }

    public function insert(?User $admin): RedirectResponse | Redirector
    {
        // User is logged in (Admin is creating a new user)
        if ($admin) {
            // TO DO send email to user to set password
            $user = User::create(Arr::collapse([
                $this->form->getState(),
                [
                    'role_id' => $this->role_id,
                    'password' => Hash::make(uniqid())
                ]
            ]));
            session()->flash('message', 'User registered.');
            return redirect($this->back_url);
        }

        // New user is registering themselves
        $user = User::create(Arr::add(
            $this->form->getState(),
            'role_id',
            $this->role_id,
        ));
        $user->update([
            'password' => Hash::make($this->form->getState()['password']),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return Redirect::to(RouteServiceProvider::HOME);
    }

    public function update(): RedirectResponse
    {
        // TO DO send email to user to confirm email
        $this->user->update($this->form->getState());
        $this->user->save();

        session()->flash('message', 'Changes saved.');

        return Redirect::route('users.show', $this->user);
    }
}
