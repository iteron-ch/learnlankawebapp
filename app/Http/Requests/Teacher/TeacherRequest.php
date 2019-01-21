<?php

/**
 * This file is used for teacher validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Teacher;

use App\Http\Requests\Request;

/**
 * This class is used for teacher validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class TeacherRequest extends Request {

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
            'telephone_no' => 'required|min:5|max:20|regex:/' . numeric_space_valid,
            'address' => 'required|min:5|max:100',
            'city' => 'required|min:2|max:40',
            'postal_code' => 'required|min:4|max:7|regex:/'.alpha_numeric_valid,
            'county' => 'required',
            'country' => 'required',
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
            'username.unique' => trans('admin/admin.username_unique_error'),
            'username.regex' => trans('admin/admin.username_error'),
            'email.required' => trans('admin/admin.email_error'),
            'email.unique' => trans('admin/admin.email_unique_error'),
            'email.email' => trans('admin/admin.email_error'),
            'password.required' => trans('admin/admin.password_error'),
            'confirm_password.required' => trans('admin/admin.confirm_password_error'),
            'confirm_password.same' => trans('admin/admin.confirm_password_same_error'),
            'telephone_no.required' => trans('admin/admin.telephone_no_error'),
            'telephone_no.regex' => trans('admin/admin.telephone_no_error'),
            'address.required' => trans('admin/admin.address_error'),
            'city.required' => trans('admin/admin.city_error'),
            'postal_code.required' => trans('admin/admin.postal_code_error'),
            'postal_code.alpha_num' => trans('admin/admin.postal_code_error'),
            'county.required' => trans('admin/admin.county_error'),
            'country.required' => trans('admin/admin.country_error'),
            'image.image' => trans('admin/admin.imagevalid_error'),
            'image.max' => trans('admin/admin.imagemaxsize_error'),
        ];
    }
    
    

}
