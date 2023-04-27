<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        return [
            'firstName' => ['string', 'max:255'],
            'lastName' => ['string', 'max:255'],
            'code_id' => ['integer'],
            'phone' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique('users')->ignore($this->user_id)],
        ];
    }
}
