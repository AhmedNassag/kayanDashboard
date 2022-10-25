<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvertisePackageRequest extends FormRequest
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
        if ($this->method() == 'PUT' || $this->method() == 'PATCH')
            return
            [
                // 'ar.name' => ['required', Rule::unique('advertising_package_translations', 'name')->ignore($advertisingPackage->id, 'package_id')],
                // 'en.name' => ['required', Rule::unique('advertising_package_translations', 'name')->ignore($advertisingPackage->id, 'package_id')],
                'name'                => 'required|unique:advertising_packages,name',
                'period'              => 'required|integer',
                'visiter_num'         => 'required|integer',
                'price'               => 'required|integer',
                'pageView_id'         => 'required|array',
                'pageView_id.*'       => 'required|integer',
                'pageViewMobile_id'   => 'required|array',
                'pageViewMobile_id.*' => 'required|integer'
            ];
        else
            return
            [
                // 'ar.name' => 'required|unique:advertising_package_translations,name',
                // 'en.name' => 'required|unique:advertising_package_translations,name',
                'name'                => 'required|unique:advertising_packages,name',
                'period'              => 'required|integer',
                'visiter_num'         => 'required|integer',
                'price'               => 'required|integer',
                'pageView_id'         => 'required|array',
                'pageView_id.*'       => 'required|integer',
                'pageViewMobile_id'   => 'required|array',
                'pageViewMobile_id.*' => 'required|integer'
            ];
    }
}
