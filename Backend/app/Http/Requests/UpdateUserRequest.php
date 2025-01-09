<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'email' => 'sometimes|email',
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|numeric',
            'height' => 'sometimes|numeric',
            'weight' => 'sometimes|required|numeric',
            'age' => 'sometimes|required|numeric',
            'gender' => ['sometimes', 'required'],
            'address' => 'nullable',
            'password' => 'nullable', // Password is only required if provided
            'needed_calories' => 'nullable',
        ];
    }
}
