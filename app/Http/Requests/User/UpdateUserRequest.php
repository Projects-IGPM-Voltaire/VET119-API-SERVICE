<?php

namespace App\Http\Requests\User;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateUserRequest extends FormRequest
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
            'first_name' => 'required|min:2|string',
            'last_name' => 'required|min:2|string',
            'birthday' => 'required|date_format:Y-m-d',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
