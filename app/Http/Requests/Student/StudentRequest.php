<?php

/**
 * This file is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Student;

use App\Http\Requests\Request;

/**
 * This class is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class StudentRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        $sess_user_type = session()->get('school_student_param')['sess_user_type'];
        $rules = [
            'teacher_id' => ($sess_user_type != TUTOR) ? 'required' : '',
            'first_name' => 'required|min:2|max:40|regex:/' . only_alpha_valid,
            'last_name' => 'required|min:2|max:40|regex:/' . only_alpha_valid,
            'telephone_no' => 'min:5|max:20|regex:/' . numeric_space_valid,
            'address' => 'required|min:5|max:100',
            'city' => 'required|min:2|max:40',
            'key_stage' => 'required',
            'year_group' => 'required',
            'postal_code' => 'required|min:4|max:7|regex:/' . alpha_numeric_valid,
            'county' => 'required',
            'country' => 'required',
            'image' => 'image|max:' . img_maxsize,
            'date_of_birth' => 'required',
        ];
        if ($sess_user_type != TUTOR) {
            $rules['ethnicity'] = 'required';
            $rules['sats_exempt'] = 'required';
            $rules['UPN'] = 'min:13|max:13|regex:/' . alpha_special_char;
            $rules['ks1_maths_baseline_value'] = 'regex:/' . amount_valid;
            $rules['ks1_english_baseline_value'] = 'regex:/' . amount_valid;
            $rules['ks2_maths_baseline_value'] = 'regex:/' . amount_valid;
            $rules['ks2_english_baseline_value'] = 'regex:/' . amount_valid;
            $rules['maths_target_value'] = 'regex:/' . amount_valid;
            $rules['english_target_value'] = 'regex:/' . amount_valid;



            if (!empty(Request::all())) {
                if (Request::get('sen_provision') == '0') {
                    unset($rules['sen_provision_desc']);
                }
                if (Request::get('ks1_average_point_score') == '1') {
                    $rules['ks1_average_point_score_value'] = 'min:1|max:1000|numeric';
                } else if (Request::get('ks1_average_point_score') == '2') {
                    $rules['ks1_average_point_score_value'] = 'min:1|max:100|numeric';
                }

                if (Request::get('ks1_maths_baseline') == '1') {
                    $rules['ks1_maths_baseline_value'] = 'min:1|max:1000|numeric';
                } else if (Request::get('ks1_maths_baseline') == '2') {
                    $rules['ks1_maths_baseline_value'] = 'min:1|max:100|numeric';
                }

                if (Request::get('ks1_english_baseline') == '1') {
                    $rules['ks1_english_baseline_value'] = 'min:1|max:1000|numeric';
                } else if (Request::get('ks1_english_baseline') == '2') {
                    $rules['ks1_english_baseline_value'] = 'min:1|max:100|numeric';
                }

                if (Request::get('ks2_maths_baseline') == '1') {
                    $rules['ks2_maths_baseline_value'] = 'min:1|max:1000|numeric';
                } else if (Request::get('ks2_maths_baseline') == '2') {
                    $rules['ks2_maths_baseline_value'] = 'min:1|max:100|numeric';
                }

                if (Request::get('ks2_english_baseline') == '1') {
                    $rules['ks2_english_baseline_value'] = 'min:1|max:1000|numeric';
                } else if (Request::get('ks2_english_baseline') == '2') {
                    $rules['ks2_english_baseline_value'] = 'min:1|max:100|numeric';
                }

                if (Request::get('maths_target') == '1') {
                    $rules['maths_target_value'] = 'min:1|max:1000|numeric';
                } else if (Request::get('maths_target') == '2') {
                    $rules['maths_target_value'] = 'min:1|max:100|numeric';
                }

                if (Request::get('english_target') == '1') {
                    $rules['english_target_value'] = 'min:1|max:1000|numeric';
                } else if (Request::get('english_target') == '2') {
                    $rules['english_target_value'] = 'min:1|max:100|numeric';
                }
            }
        }
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function commonMessages() {
        return [
            'teacher_id.required' => trans('admin/student.teacher_error'),
            'first_name.required' => trans('admin/teacher.first_name_error'),
            'first_name.regex' => trans('admin/teacher.first_name_error'),
            'last_name.required' => trans('admin/teacher.last_name_error'),
            'last_name.regex' => trans('admin/teacher.last_name_error'),
            'email.required' => trans('admin/admin.email_error'),
            'email.unique' => trans('admin/admin.email_unique_error'),
            'email.email' => trans('admin/admin.email_error'),
            'username.regex' => trans('admin/admin.username_error'),
            'username.required' => trans('admin/admin.username_error'),
            'username.unique' => trans('admin/admin.username_unique_error'),
            'password.required' => trans('admin/admin.password_error'),
            'confirm_password.required' => trans('admin/admin.confirm_password_error'),
            'confirm_password.same' => trans('admin/admin.confirm_password_same_error'),
            //'telephone_no.required' => trans('admin/admin.telephone_no_error'),
            'telephone_no.regex' => trans('admin/admin.telephone_no_error'),
            'address.required' => trans('admin/admin.address_error'),
            'city.required' => trans('admin/admin.city_error'),
            'postal_code.required' => trans('admin/admin.postal_code_error'),
            'postal_code.alpha_num' => trans('admin/admin.postal_code_error'),
            'county.required' => trans('admin/admin.county_error'),
            'country.required' => trans('admin/admin.country_error'),
            'key_stage.required' => trans('admin/admin.select_error'),
            'year_group.required' => trans('admin/admin.select_error'),
            //'classlevels_id.required' => trans('admin/teacher.level_error'),
            //'schoolclasses_id.required' => trans('admin/teacher.class_error'),
            'image.image' => trans('admin/admin.imagevalid_error'),
            'image.max' => trans('admin/admin.imagemaxsize_error'),
        ];
    }

}
