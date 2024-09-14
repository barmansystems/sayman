<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'family' => 'required',
            'gender' => 'required',
            'phone' => 'required|size:11|unique:users',
            'role' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.size' => 'شماره موبایل باید 11 رقم باشد'
        ];
    }
}
