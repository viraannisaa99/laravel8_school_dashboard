<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected $rules = [];

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
        $method = $this->method();
        if (null !== $this->get('_method', null)) {
            $method = $this->get('_method');
        }
        $this->offsetUnset('_method');

        switch ($method) {
            case 'POST':
                $this->rules = [
                    'name'      => 'required',
                    'email'     => 'required|email|unique:users,email',
                    'roles'     => 'required'
                ];
                break;
            case 'PUT':
                $this->rules = [
                    'name'      => 'required',
                    'email'     => 'required|email|unique:users,email',
                    'password'  => 'same:confirm-password',
                    'roles'     => 'required'
                ];
                break;
            default:
                break;
        }
        return $this->rules;
    }
}
