<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class OAuthSetPasswordRequest extends FormRequest
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
            'password' => 'min:8|required',
            'dni_type' => ['required', Rule::in(Config::get('user.data.dni_types'))],
            'dni_number' => 'int|required'
        ];
    }
}
