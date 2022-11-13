<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutInformationRequest extends FormRequest
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
            "header" => "required",
            "sub_header" => "nullable",
            "text" => "nullable",
            "first_feature" => "nullable",
            "second_feature" => "nullable",
            "third_feature" => "nullable",
            "image" => "nullable|image|mimes:png,jpg,webp,gif,jpeg"
        ];
    }
}
