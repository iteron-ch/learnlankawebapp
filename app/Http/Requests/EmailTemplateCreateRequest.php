<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmailTemplateCreateRequest extends Request {

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
            'subject' => 'required',
            'message' => 'required'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'title.required' => trans('admin/emailtemplate.title_error_msg'),
            'subject.required' => trans('admin/emailtemplate.subject_error_msg'),
            'message.required' => trans('admin/emailtemplate.message_error_msg')
        ];
    }

}
