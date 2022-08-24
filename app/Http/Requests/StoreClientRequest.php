<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            "phone" => "required|unique:users|regex:/^01[0125][0-9]{8}$/",
            "email" => "required|email|unique:users",
            "password" => "required",
            "store_name" => "required",
            "country" => "required",
            "city" => "required",
            "address" => "required",
            "location" => "required",
            "area" => "required",
            "whatsup_phone" => "required|unique:clients|regex:/^01[0125][0-9]{8}$/",
            "responsible_name" => "required",
            "responsible_phone" => "required|regex:/^01[0125][0-9]{8}$/",
            "same_address_shipping" => "boolean|required",
        ];
        if (!$this->same_address_shipping) {
            $validators["shipping_country"] = "required";
            $validators["shipping_city"] = "required";
            $validators["shipping_address"] = "required";
            $validators["shipping_location"] = "required";
            $validators["shipping_area"] = "required";
        }
        return $validators;
    }
}
