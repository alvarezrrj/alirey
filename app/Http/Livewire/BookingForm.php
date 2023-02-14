<?php

namespace App\Http\Livewire;

use Filament\Forms\Components\ViewField;
use App\Models\Code;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class BookingForm extends Component implements HasForms
{
    use InteractsWithForms;

    // Data coming from BookingController
    public $data;
    public $codes;
    public $booking;

    // Booking data
    public $firstName;
    public $lastName;
    public $email;
    public $code_id;
    public $phone;
    public $virtual;
    public $day;
    public $slot_id;

    public $datepicker;

    public function mount()
    {
        $this->firstName = $this->booking?->user->firstName;
        $this->lastName = $this->booking?->user->lastName;
        $this->email = $this->booking?->user->email;
        $this->code_id = $this->booking?->user->code_id;
        $this->phone = $this->booking?->user->phone;
        $this->virtual = $this->booking?->virtual;
        $this->day = $this->booking?->day;
        $this->slot_id = $this->booking?->slot_id;
        $this->form->fill([
            'datepicker' => $this->day,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('datepicker')
                ->label(__('Date'))
                ->displayFormat('l jS F Y')
                ->format('Y-m-d')
                ->minDate($this->data['first_day'])
                ->maxDate($this->data['last_day'])
                ->disabledDates($this->data['disabled'])
                ->extraAttributes(['class' => 'dark:text-gray-300'])
        ];
    }

    public function render()
    {
        return view('livewire.booking-form');
    }

    public function updatedEmail()
    {
        $user = User::where('email', $this->email)->first();
        if($user)
        {
            $this->firstName = $user->firstName;
            $this->lastName = $user->lastName;
            $this->code_id = $user->code_id;
            $this->phone = $user->phone;
        }
    }

    public function calClickHandler()
    {
        /* DatePicker is not automatically updating the value of $this->day on
         * click, for this reason this property is being used to comunicate
         * the currently selected day to the Alpine component 'slot' so it can
         * render available days
         */
        
        //$this->selected_day = $this->form->getState()['day'];
        $this->day = $this->form->getState()['datepicker'];
    }
}