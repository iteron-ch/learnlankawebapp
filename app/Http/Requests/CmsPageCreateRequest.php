<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CmsPageCreateRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        return [
            'title' => 'required',
            'sub_title' => 'required',
            'description' => 'required'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'title.required' => trans('admin/cmspage.title_error_msg'),
            'sub_title.required' => trans('admin/cmspage.sub_title_error_msg'),
            'description.required' => trans('admin/cmspage.description_error_msg')
        ];
    }

}
