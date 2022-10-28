<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
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
            'order_id' => 'required|integer|exists:orders,id',
            'reason' => 'required|max:255|string',
            'photos' => 'required|array',
            'photos.*' => 'required|file|mimes:jpeg,jpg,gif,png',
        ];
    }
}
