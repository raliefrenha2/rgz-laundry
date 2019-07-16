<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutletRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:100',
            'address' => 'required|min:5|string',
            'phone' => 'required|min:8|max:13'
        ];

        if (request()->isMethod('put')) {
            $rules['code'] = 'required|exists:outlets,code';
        }
        if (request()->isMethod('post')) {
            $rules['code'] = 'required|unique:outlets,code';
        }

        return $rules;
    }
}
