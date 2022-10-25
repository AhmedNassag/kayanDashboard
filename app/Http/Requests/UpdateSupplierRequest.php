<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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
            "id" => "required",
            "name" => "required",
            "address" => "required",
            "phone" => "required|regex:/^01[0125][0-9]{8}$/|unique:suppliers,phone," . $this->id,
            "commerical_register" => "nullable|unique:suppliers,commerical_register," . $this->id,
            "tax_card" => "nullable|unique:suppliers,tax_card," . $this->id,
            "is_our_supplier" => ["required", "boolean", Rule::unique('suppliers')->where(fn ($query) => $query
                ->where('is_our_supplier', 1))->ignore($this->id)],
        ];
    }
}
