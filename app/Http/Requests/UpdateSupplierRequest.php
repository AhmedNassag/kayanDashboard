<?php

namespace App\Http\Requests;

use App\Commons\Consts\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

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
        $validators = [
            "name" => "required",
            "address" => "required",
            "phone" => "required|unique:suppliers,phone," . $this->id,
            "commerical_register" => "nullable|unique:suppliers,commerical_register," . $this->id,
            "tax_card" => "nullable|unique:suppliers,tax_card," . $this->id,
            "responsible_phone" => "nullable",
            "payment_type" => "required|in:" . PaymentType::BANK_TRANSFER . "," . PaymentType::CASH . ","
                . PaymentType::WALLET
        ];
        if ($this->payment_type == PaymentType::BANK_TRANSFER) {
            $validators["account_number"] = "required";
        } else if ($this->payment_type == PaymentType::WALLET) {
            $validators["payment_phone"] = "required|regex:/^01[0125][0-9]{8}$/";
        } else {
            $validators["payment_responsible_name"] = "required";
            $validators["payment_responsible_phone"] = "required|regex:/^01[0125][0-9]{8}$/";
            $validators["payment_responsible_card_number"] = "required";
        }
        return $validators;
    }
}
