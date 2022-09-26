<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DealRequest extends FormRequest
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
            "end_at" => "required|date|after_or_equal:now",
            "products_with_discounts" => "required|array|min:1",
            "products_with_discounts.*.product_id" => "required|exists:products,id",
            "products_with_discounts.*.discount" => "required|min:0|max:100"
        ];
    }
}
