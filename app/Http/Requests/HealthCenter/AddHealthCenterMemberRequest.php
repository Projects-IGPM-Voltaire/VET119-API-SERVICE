<?php

namespace App\Http\Requests\HealthCenter;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class AddHealthCenterMemberRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png',
            'first_name' => 'required|min:2|string',
            'last_name' => 'required|min:2|string',
            'birthday' => 'required|date_format:Y-m-d',
            'mobile_number' => [
                'required',
                'regex:/^(09)\d{9}$/',
                'unique:users,mobile_number',
            ],
            'password' => 'required|min:6|required_with:password_confirmation',
            'password_confirmation' => 'same:password',
            'position' => 'required|string',
        ];
    }
}
