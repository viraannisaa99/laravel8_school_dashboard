<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequest extends FormRequest
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
        return [
            'name'  => 'required',
            'nim'   => 'required|min:10|max:10',
            'phone' => 'required',
            'email' => 'required|email',
            'roomId' => 'required',
            'photo' => 'mimes:jpg,bmp,png|max:1024',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'nim.required' => 'NIM is required!',
            'phone.required' => 'Phone is required!',
            'email.required' => 'Email is required!',
            'roomId.required' => 'Room is required!'
        ];
    }

    public function validate()
    {
        $instance = $this->getValidatorInstance();
        if ($instance->fails()) {
            throw new HttpResponseException(response()->json($instance->errors(), 422));
        }
    }
}
