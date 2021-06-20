<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected $rules = [];

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
                    'name'       => 'required|unique:roles,name',
                    'permission' => 'required',
                ];
                break;
            case 'PUT':
                $this->rules = [
                    'name'       => 'required',
                    'permission' => 'required',
                ];
                break;
            default:
                break;
        }
        return $this->rules;
    }
}
