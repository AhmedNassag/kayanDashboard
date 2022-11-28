<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            "id" => "required",
            "user_id" => "required",
            "name" => "required",
            "phone" => "required|unique:users,phone," . $this->user_id . "|regex:/^01[0125][0-9]{8}$/",
            "email" => "required|email|unique:users,email," . $this->user_id,
            "store_name" => "required",
            "city_id" => "required",
            "area_id" => "required",
            "selling_method_id" => "required",
            "address" => "required",
            "location" => "required",
            "whatsup_phone" => "required|unique:clients,whatsup_phone," . $this->id . "|regex:/^01[0125][0-9]{8}$/",
            "responsible_name" => "required",
            "responsible_phone" => "required|regex:/^01[0125][0-9]{8}$/",
            "same_address_shipping" => "boolean|required",
        ];
        if (!$this->same_address_shipping) {
            $validators["shipping_city_id"] = "required";
            $validators["shipping_area_id"] = "required";
            $validators["shipping_address"] = "required";
            $validators["shipping_location"] = "required";
        }
        return $validators;
    }
}
