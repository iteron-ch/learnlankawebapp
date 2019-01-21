<?php

/**
 * This file is used for tutor validations.
 * @package    QuestionAdmin
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Requests\QuestionValidator;

use App\Http\Requests\Request;

/**
 * This class is used for QuestionAdmin validations.
 * @package    QuestionAdmin
 * @author     Icreon Tech  - dev5.
 */
class QuestionValidatorRequest extends Request {

    /**
     * Get the validation rules that apply to the QuestionAdmin record.
     *
     * @return array
     */
    public function commonRules() {
        $this->sanitize();
        return [
            'first_name' => 'required|min:2|max:40|regex:/' . only_alpha_valid,
            'last_name' => 'required|min:2|max:40|regex:/' . only_alpha_valid,
            'image' => 'image|max:' . img_maxsize,
            'confirm_password' => 'required|min:6|max:20|same:password'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function commonMessages() {
        return [
            'first_name.required' => trans('admin/teacher.first_name_error'),
            'first_name.regex' => trans('admin/teacher.first_name_error'),
            'last_name.required' => trans('admin/teacher.last_name_error'),
            'last_name.regex' => trans('admin/teacher.last_name_error'),
            'username.required' => trans('admin/admin.username_error'),
            'email.required' => trans('admin/admin.email_error'),
            'username.unique' => trans('admin/admin.username_unique_error'),
            'email.unique' => trans('admin/admin.email_unique_error'),
            'password.required' => trans('admin/admin.password_error'),
            'confirm_password.required' => trans('admin/admin.confirm_password_error'),
            'confirm_password.same' => trans('admin/admin.confirm_password_same_error'),
            'image.image' => trans('admin/admin.imagevalid_error'),
            'image.max' => trans('admin/admin.imagemaxsize_error'),
        ];
    }

}
