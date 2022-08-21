<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
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
            "name" => "required|unique:offers",
            "start_date" => "required|date_format:Y-m-d|after_or_equal:" . date('Y-m-d'),
            "end_date" => "required|date_format:Y-m-d|after:start_date",
            "ratio" => "required|boolean"
        ];
        $validators["discount"] = $this->ratio ? "required|numeric|min:0|max:100"
            : "required|numeric|min:0";
        return $validators;
    }
}
