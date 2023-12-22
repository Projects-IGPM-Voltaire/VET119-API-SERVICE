<?php

namespace App\Http\Requests\HealthCenter;

use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class CreateHealthCenterRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'name' => 'required|min:2|string',
            'city_code' => 'required|string',
            'barangay_code' => 'required|string',
            'house_number' => 'required|string',
            'street' => 'required|string',
            'map_url' => 'required|string',
        ];
    }
}
