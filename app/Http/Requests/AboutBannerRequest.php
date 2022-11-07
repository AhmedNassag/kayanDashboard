<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutBannerRequest extends FormRequest
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
        $rules = [
            "header" => "required",
            "url" => "required",
            "button_label" => "required",
            "image" => "nullable|image",
        ];
        if ($this->name == "FIRST_BANNER" || $this->name == "THIRD_BANNER") {
            $rules["first_text"] = "required";
            $rules["second_text"] = "required";
        }
        if ($this->name == "FIRST_BANNER" || $this->name == "SECOND_BANNER") {
            $rules["color"] = "required";
        }
        if ($this->name == "LAST_BANNER") {
            $rules["sub_header"] = "required";
        }
        if ($this->name == "SECOND_BANNER") {
            $rules["text"] = "required";
        }
        return $rules;
    }
}
