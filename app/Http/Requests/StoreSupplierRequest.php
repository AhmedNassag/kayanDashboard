<?php

namespace App\Http\Requests;

use App\Commons\Consts\SupplierType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
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
            "name" => "required",
            "address" => "required",
            "phone" => "required|unique:suppliers|regex:/^01[0125][0-9]{8}$/",
            "type" => "required|in:" . SupplierType::ALL,
            "commerical_register" => "nullable|unique:suppliers",
            "tax_card" => "nullable|unique:suppliers",
            "responsible_phone" => "nullable|regex:/^01[0125][0-9]{8}$/",
            "is_our_supplier" => ["required","boolean",Rule::unique('suppliers')->where(fn ($query) => $query
            ->where('is_our_supplier', 1))],
        ];
    }
}
