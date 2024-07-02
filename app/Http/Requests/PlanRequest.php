<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
            'name' => 'required|string|min:4|max:255',
            'details' => 'required|string|min:4|max:10000',
            'period'=>'required|integer|min:0',
            'price'=>'required|numeric|min:0',
            'image'=>'nullable|mimes:jpg,jpeg,gif,png|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [

        ];
    }
}
