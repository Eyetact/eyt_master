<?php

namespace App\Http\Requests;

use App\Rules\attributeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AttributePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('attributes')->where(function ($query) {
                return $query->where('module', $this->module);
            })],

            'code' => [
                'required',
                Rule::unique('attributes')->where(function ($query) {
                    return $query->where('module', $this->module);
                })
            ],

            // 'code' => 'required|unique:attributes,code',

            'module' => 'required',
            'input_types' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'name is required',
            'name.unique' => 'name should be unique in the module',

            'module.required' => 'module is required',

            'input_types.required' => 'attribute type is required',

            'code.unique' => 'code should be unique over all the system',
            'code.required' => 'code is required'
        ];
    }
}
