<?php

/**
 * Request validation for a single slot holiday
 */

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class SingleSlotHolidayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $last_day = $this->user()->config->allways_open
        ? Carbon::today()->addDays($this->user()->config->anticipation)
        : Carbon::create($this->user()->config->open_until);

        $last_day_string = $last_day->toDateString();
        return [
            'day' => "required|date|after_or_equal:today|before_or_equal:$last_day_string",
            'slot_id' => [
                'required',
                'int',
                Rule::in(Slot::pluck('id')->all())
            ]
        ];
    }

    // Ensure another booking doesn't exist for same day and slot
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // var_dump( $validator->errors()->get('day'));
            // exit;
            $validated = $validator->safe()->only(['day', 'slot_id']);

            if( count(Booking::whereDate('day', $validated['day'])
                                ->where('slot_id', $validated['slot_id'])
                                ->get() ))
            {
                $validator->errors()->add('overlap', 'Woops! a booking has just come in for that day and time, you might wanna get in touch with the client.');
            }
        });
    }
}
