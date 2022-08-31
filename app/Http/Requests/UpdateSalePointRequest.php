<?php

namespace App\Http\Requests;

use App\Commons\Consts\RelatedWith;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSalePointRequest extends FormRequest
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
        $validator = [
            "id" => "required",
            "name" => "required|unique:sale_points,name," . $this->id,
            "counter" => "required|integer|min:0",
            "price" => "required|numeric|min:0",
            "related_with" => "required|in:" . RelatedWith::PRODUCT . "," . RelatedWith::CATEGORY . ","
                . RelatedWith::CLIENT_GROUP,
        ];
        if ($this->related_with == RelatedWith::PRODUCT) {
            $validator["product_id"] = "required|numeric";
        } else if ($this->related_with == RelatedWith::CATEGORY) {
            $validator["sub_category_id"] = "required|numeric";
        } else {
            $validator["clients_groups_ids"] = "required|array|min:1";
            $validator["clients_groups_ids.*"] = "required|numeric";
            $validator["clients_groups_ids.*"] = "required|numeric";
        }
        return $validator;
    }
}
