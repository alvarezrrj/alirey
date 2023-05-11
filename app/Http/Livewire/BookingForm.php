<?php

namespace App\Http\Livewire;

use App\Http\Controllers\BookingController;
use App\Models\Code;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookingForm extends Component implements HasForms
{
    use InteractsWithForms;

    public $is_admin;

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

    // Booking type modal acknowledgement
    public $ackowledged;

    public $userFound = false;
    public $is_booking = true;

    protected $rules = [
        'booking.payment.amount' => 'Integer'
    ];

    public function mount()
    {
        $this->data = BookingController::getData();
        $this->codes = Code::all();
        $this->is_admin = Auth::user()->isAdmin();

        $this->firstName = $this->booking?->user->firstName;
        $this->lastName = $this->booking?->user->lastName;
        $this->email = $this->booking?->user->email;
        $this->code_id = $this->booking?->user->code_id;
        $this->phone = $this->booking?->user->phone;
        $this->virtual = $this->booking?->virtual;
        $this->day = $this->booking?->day;
        $this->slot_id = $this->booking?->slot_id;
        $this->dayForm->fill([
            'day' => $this->booking?->day,
        ]);
        $this->codeForm->fill([
            'code_id' => $this->booking?->user->code->id,
        ]);
    }

    protected function getDayFormSchema(): array
    {

        return [
            DatePicker::make('day')
                ->label(__('Date'))
                ->displayFormat('l jS F Y')
                ->format('Y-m-d')
                ->minDate($this->data['first_day'])
                ->maxDate($this->data['last_day'])
                ->disabledDates($this->data['disabled'])
                ->extraAttributes(['class' => 'dark:text-gray-300']),
        ];
    }

    protected function getCodeFormSchema(): array
    {
        $codeOptions = $this->codes->keyBy('id')->map(function($code) {
            return $code->flag.' +'.$code->code.' '.$code->country;
        });
        return [
            Select::make('code_id')
                ->label(__('Country code'))
                ->allowHtml()
                ->searchable()
                ->placeholder(__('Country'))
                ->disablePlaceholderSelection()
                ->options($codeOptions)
                ->extraAttributes(['class' => 'dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg'])
        ];
    }

    protected function getForms(): array
    {
        return [
            'dayForm' => $this->makeForm()
                ->schema($this->getDayFormSchema()),
            'codeForm' => $this->makeForm()
                ->schema($this->getCodeFormSchema()),
        ];
    }

    public function render()
    {
        $form = $this->is_booking ? 'booking-form' : 'single-slot-holiday';
        return view('livewire.'.$form);
    }

    public function updatedEmail()
    {
        $user = User::where('email', $this->email)->first();
        if($user)
        {
            $this->userFound = true;
            $this->firstName = $user->firstName;
            $this->lastName = $user->lastName;
            $this->code_id = $user->code_id;
            $this->phone = $user->phone;
        }
        else {
            $this->userFound = false;
            $this->firstName = '';
            $this->lastName = '';
            $this->code_id = '';
            $this->phone = '';
        }
    }

    public function calClickHandler()
    {
        /* DatePicker is not automatically updating the value of $this->day on
         * click, for this reason this funtion is being used to comunicate
         * the currently selected day to the Alpine component 'slot' so it can
         * render available days.
         *
         * Calling getState() validates the form and populates its corresponding
         * public properties
         */
        $this->dayForm->getState();
    }

}
