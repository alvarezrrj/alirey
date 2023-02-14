<?php
/**
 * Request validation for admin updating a booking
 */
namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\Code;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Leave authorization to the policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        $last_day = $request->user()->config->allways_open 
        ? Carbon::today()->addDays($request->user()->config->anticipation)
        : Carbon::create($request->user()->config->open_until);

        $last_day_string = $last_day->toDateString();

        return [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->user()->id)
            ],
            'code_id' => [
                'required',
                'int',
                Rule::in(Code::pluck('id')->all()),
            ],
            'phone' => 'required|numeric',
            'amount' => 'required|int|min:0',
            'virtual' => 'required|boolean',
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
            $validated = $validator->safe()->only(['day', 'slot_id']);

            if( count(Booking::whereDate('day', $validated['day'])
                             ->where('slot_id', $validated['slot_id'])) )
            {
                $validator->errors()->add('overlap', 'Sorry, that slot is no longer available. Please try again.');
            }
        });
    }
}
