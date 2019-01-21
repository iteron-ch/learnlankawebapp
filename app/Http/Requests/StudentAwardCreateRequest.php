<?php

namespace App\Http\Requests;

class StudentAwardCreateRequest extends Request {

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
            'content' => 'required'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'title.required' => trans('admin/studentaward.title_error_msg'),
            'content.required' => trans('admin/studentaward.content_error_msg')
        ];
    }

}
