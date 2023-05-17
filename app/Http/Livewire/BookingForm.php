<?php

namespace App\Http\Livewire;

use App\Http\Controllers\BookingController;
use App\Models\Booking;
use App\Models\Code;
use App\Models\Payment;
use App\Models\User;
use App\Models\Slot;
use App\SD\SD;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Validation\Rule;

class BookingForm extends Component implements HasForms
{
    use InteractsWithForms;
    use AuthorizesRequests;

    public $data;
    public $codes;
    public $is_admin;

    public $booking;

    public $virtual;
    public $day;
    public $slot_id;
    public $amount;

    // Booking type modal acknowledgement
    public $ackowledged;

    // Select between booking and single-slot holiday
    public $is_booking = true;

    public $client;
    public User $therapist;

    protected function rules()
    {
        $last_day = $this->therapist->config->allways_open
        ? Carbon::today()->addDays($this->therapist->config->anticipation)
        : Carbon::create($this->therapist->config->open_until);

        $last_day_string = $last_day->toDateString();

        return [
            'amount' => 'Integer|nullable',
            'virtual' => 'required|boolean',
            'day' => "required|date|after_or_equal:today|before_or_equal:$last_day_string",
            'slot_id' => [
                'required',
                'int',
                Rule::in(Slot::pluck('id')->all())
            ]
        ];
    }

    public function mount()
    {
        $this->data = BookingController::getData($this->booking ?? null);
        $this->codes = Code::all();
        $this->is_admin = Auth::user()->isAdmin();

        if ($this->is_admin) {
            $this->therapist = Auth::user();
        } else {
            $this->client = Auth::user();
        }

        $this->virtual = $this->booking?->virtual ?? 1;
        $this->day = $this->booking?->day;
        $this->slot_id = $this->booking?->slot_id;
        $this->amount = $this->booking?->payment->amount;
        $this->dayForm->fill([
            'day' => $this->booking?->day,
        ]);
        $this->clientForm->fill([
            'client' => $this->booking?->user_id,
        ]);
    }

    protected function getClientFormSchema(): array
    {
        return [
            Select::make('client')
                ->label(__('Client'))
                ->placeholder(__('Find client'))
                ->hint('['.__('Client not found? Register one.').']('.route('users.create').')')
                ->hintColor('primary')
                ->hintIcon('heroicon-o-user-add')
                ->disablePlaceholderSelection()
                ->allowHtml()
                ->searchable()
                ->getSearchResultsUsing(function (string $search) {
                    $users = User::where('firstName', 'like', "%$search%")
                    ->orWhere('lastName', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->where('id', '!=', $this->therapist->id)
                    ->limit(50)
                    ->get();
                    return $users->keyBy('id')
                        ->map(fn ($user) => view('bookings.user-select')
                            ->with(['user' => $user])
                            ->render()
                        );
                })
                ->getOptionLabelUsing(function ($value) {
                    if (! $value) return null;
                    return view('bookings.user-select')
                        ->with(['user' => User::find($value)])
                        ->render();
                })
                ->extraAttributes(['class' => 'dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded-lg'])
                ->reactive()
                ->required(fn () => $this->is_admin && $this->is_booking)
                ->disabled(fn () => isset($this->booking))
        ];
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
                ->extraAttributes(['class' => 'dark:text-gray-300'])
                ->required()
                ->reactive(),
        ];
    }

    protected function getForms(): array
    {
        return [
            'dayForm' => $this->makeForm()
                ->schema($this->getDayFormSchema()),
            'clientForm' => $this->makeForm()
                ->schema($this->getClientFormSchema()),
        ];
    }

    public function render()
    {
        return view('livewire.bookings.booking-form');
    }

    public function insert(Request $request)
    {
        $validated = Arr::only(
            $this->validate(),
            ['day', 'slot_id', 'virtual']
        );
        $bookings = Booking::whereDate('day', $validated['day'])
            ->where('status', '!=', SD::BOOKING_CANCELLED)
            ->where('slot_id', $validated['slot_id']);

        return DB::transaction(function () use ($validated, $request, $bookings) {
            if ( $bookings->count() ) {
                if ($this->is_booking) {
                    $this->addError('overlap', 'Sorry, that slot is no longer available. Please try again.');
                } else {
                    $this->addError('overlap', 'Woops! a booking has just come in for that day and time, you might want to get in touch with the client.');
                }
                return;
            }
            $payment = Payment::create([
                'amount' => 0,
                'status' => SD::PAYMENT_PENDING,
            ]);
            $validated['payment_id'] = $payment->id;
            $validated['status'] = SD::BOOKING_PENDING;
            $validated['therapist_id'] = $this->therapist->id;

            // Admin booking for client
            if ($this->is_admin && $this->is_booking) {
                $booking = User::find($this->client)->bookings()->create($validated);
                session()->flash('message', 'Booking saved.');
                return redirect(route('bookings.show', $booking));
            // Single slot holiday
            } else if (! $this->is_booking) {
                $booking = $this->therapist->bookings()->create($validated);
                $booking->update([
                    'is_booking' => false
                ]);
                session()->flash('message', 'Changes saved.');
                return;
            // Client booking for themselves
            } else {
                $booking = $request->user()->bookings()->create($validated);
                return redirect()->route('bookings.checkout', $booking);
            }
        });
    }

    public function update()
    {
        $this->authorize('update', $this->booking);

        $validated = Arr::only(
            $this->validate(),
            ['day', 'slot_id', 'virtual']
        );
        $bookings = Booking::whereDate('day', $validated['day'])
            ->where('slot_id', $validated['slot_id'])
            ->where('status', '!=', SD::BOOKING_CANCELLED)
            ->whereNot('id', $this->booking?->id);

        return DB::transaction(function() use ($bookings, $validated) {
            if ( $bookings->count() ) {
                $this->addError('overlap', 'Sorry, that slot is no longer available. Please try again.');
                return;
            }
            $this->booking->update($validated);
            $payment = $this->validate()['amount'] ?? 0;
            $this->booking->payment()->update(['amount' => $payment]);

            session()->flash('message', 'Changes saved.');
            return redirect(route('bookings.show', $this->booking));
        });
    }
}
