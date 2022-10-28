<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'shipp_active' => 'boolean',
            'sucursal_id' => 'required_if:shipp_active,==,1',
            'subcategorie_id' => 'required|exists:subcategories,id',
            'photos' => 'required|array',
            'photos.*' => 'required|file|mimes:jpeg,jpg,gif,png',
            'tags_titles' => 'array',
            'tags_titles.*' => 'string'
        ];
    }
}
