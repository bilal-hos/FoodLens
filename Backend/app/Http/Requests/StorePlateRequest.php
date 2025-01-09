<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePlateRequest extends FormRequest
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
        $user = Auth::user();
        $id = $user->id;
        return [
            'image' =>  ['nullable', 'mimes:jpeg,png,jpg,pdf'],
            'total_calories' => 'nullable',
            'total_carb' => 'nullable',
            'total_fat' => 'nullable',
            'total_protein' => 'nullable',
            'total_mass' => 'nullable',
            'mass' => 'nullable',
            'category' => 'nullable',
            
            

        ];
    }
}
