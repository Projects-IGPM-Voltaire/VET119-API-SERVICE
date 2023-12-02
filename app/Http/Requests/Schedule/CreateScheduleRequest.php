<?php

namespace App\Http\Requests\Schedule;

use App\Http\Requests\FormRequest;

class CreateScheduleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'health_center_id' => 'required|integer',
            'user_id' => 'nullable|integer',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'birthday' => 'nullable|string',
            'date' => 'required|date_format:Y-m-d',
            'time_from' => 'required|required|date_format:H:i:s',
            'time_to' => 'required|required|date_format:H:i:s',
        ];
    }
}
