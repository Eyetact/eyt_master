<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class storeSubPostRequest extends FormRequest
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
            'parent_id' => 'required',
            'attr_id' => 'required',
            'name' => 'required',
            'code' => 'required',
            'path' => 'required',
            'sidebar_name' => 'required'
        ];
    }
}
