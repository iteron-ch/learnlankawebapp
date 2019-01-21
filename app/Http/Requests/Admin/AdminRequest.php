<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AdminRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        $this->sanitize();
        return [
            'first_name' => 'required|min:2|max:40|regex:/' . only_alpha_valid,
            'last_name' => 'required|min:2|max:40|regex:/' . only_alpha_valid,
            'image' => 'image|max:' . img_maxsize
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
            'username.regex' => trans('admin/admin.username_error'),
            'email.unique' => trans('admin/admin.email_unique_error'),
            'email.email' => trans('admin/admin.email_error'),
            'image.image' => trans('admin/admin.imagevalid_error'),
            'image.max' => trans('admin/admin.imagemaxsize_error'),
        ];
    }

}
