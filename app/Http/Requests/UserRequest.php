<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

//    public function authorize()
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'max:60', Rule::unique('users', 'email')->ignore($this->id)->whereNull('deleted_at')],
            'username' => ['required', 'max:60', Rule::unique('users', 'username')->ignore($this->id)->whereNull('deleted_at')],
            'role' => 'required',
            'password' => $this->id ? 'nullable' : 'required',
        ];
    }
}
