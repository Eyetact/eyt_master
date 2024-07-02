<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ModulePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $menuId = $this->route('menu');
        return [

            'name' => 'required|unique:modules,name' . $menuId . '|min:3|regex:/^[^0-9]*$/',
            'path' => 'required | unique:menus,path,' . $menuId . '|regex:/^[^0-9]*$/',
            'code' => 'required| unique:modules,code' . '|regex:/^[^0-9]*$/',
            'mtype' => 'required',
            'sidebar_name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the name is required',
            'name.unique' => 'this name has already been taken!',
            'name.regex' => 'name should not contain numbers!',

            'path.required' => 'the path is required',
            'path.unique' => 'this path has already been taken!',
            'path.regex' => 'path should not contain numbers!',

            'code.required' => 'the code is required',
            'code.unique' => 'this code has already been taken!',
            'code.regex' => 'code should not contain numbers!',

            'mtype.required' => 'the module type is required',

            'sidebar_name.required' => 'the sidebar type is required',



        ];
    }
}
