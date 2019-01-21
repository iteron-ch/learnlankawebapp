<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FeesSaveRequest extends Request {

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
            'school_sign_up_fee' => 'required|max:8|regex:/' . amount_valid,
            'parent_sign_up_fee' => 'required|max:8|regex:/' . amount_valid,
            'per_student_fee' => 'required|max:8|regex:/' . amount_valid,
            'per_5_student_fee' => 'required|max:8|regex:/' . amount_valid,
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'school_sign_up_fee.required' => trans('admin/fee.school_signup_fee'),
            'school_sign_up_fee.numeric' => trans('admin/fee.school_signup_fee'),
            'parent_sign_up_fee.required' => trans('admin/fee.parent_signup_fee'),
            'parent_sign_up_fee.numeric' => trans('admin/fee.parent_signup_fee'),
            'per_student_fee.required' => trans('admin/fee.per_student_fee'),
            'per_student_fee.numeric' => trans('admin/fee.per_student_fee'),
            'per_5_student_fee.required' => trans('admin/fee.per_5_student_fee'),
            'per_5_student_fee.numeric' => trans('admin/fee.per_5_student_fee')
        ];
    }

}
