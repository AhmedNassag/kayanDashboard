<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NeedHelpRequest extends FormRequest
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
            "start_work_deadline" => "required",
            "end_work_deadline" => "required",
            "email" => "required"
        ];
    }
}
