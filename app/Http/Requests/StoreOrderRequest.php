<?php

namespace App\Http\Requests;

use App\Facades\ProductRepository;
use App\Repositories\Cache\ProductCache;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class StoreOrderRequest extends FormRequest
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
            'address_id' => 'required|integer|exists:addresses,id',
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|gt:0|less_or_equal_than_field:product_id,stock,' . ProductRepository::class,
            'shipp_type' => 'required|string|in:' . implode(',', array_keys(Config::get('shipping.types')))
        ];
    }
}
