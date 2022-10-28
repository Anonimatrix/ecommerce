<?php

namespace App\Http\Requests;

use App\Services\Shipping\ShippTypes;
use Illuminate\Foundation\Http\FormRequest;

class ShippQuoteRequest extends FormRequest
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
        $shipp_types = ShippTypes::TO_ADRESS . '|' . ShippTypes::TO_SUCURSAL;

        return [
            'shipp_type' => ['required',  "regex:/($shipp_types)/"],
            'postal_code' => 'required|numeric'
        ];
    }
}
