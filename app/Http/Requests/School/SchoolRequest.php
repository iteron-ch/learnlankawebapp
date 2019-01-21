<?php

/**
 * This file is used for school validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\School;

use App\Http\Requests\Request;

/**
 * This class is used for school validations.
 * @package    User, school
 * @author     Icreon Tech  - dev1.
 */
class SchoolRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        $this->sanitize();
        $rules = [
            'school_name' => 'required|min:2|max:50|regex:/' . alpha_special_char,
            'school_type' => 'required',
            'school_type_other' => 'required|min:2|max:50',
            'whoyous_id' => 'required',
            'whoyous_other' => 'required',
            'telephone_no' => 'required|min:5|max:20|regex:/' . numeric_space_valid,
            'address' => 'required|min:5|max:100',
            'city' => 'required|min:2|max:40',
            'postal_code' => 'required|min:4|max:7|regex:/' . alpha_numeric_valid,
            'county' => 'required',
            'country' => 'required',
            'howfinds_id' => 'required',
            'howfinds_other' => 'required',
           // 'ks1_average_point_score_value' => 'regex:/' . amount_valid,
            //'ks1_maths_baseline_value' => 'regex:/' . amount_valid,
            //'ks1_english_baseline_value' => 'regex:/' . amount_valid,
           // 'ks2_maths_baseline_value' => 'regex:/' . amount_valid,
           // 'ks2_english_baseline_value' => 'regex:/' . amount_valid,
            'image' => 'image|max:' . img_maxsize,
            'dfe_number' => 'required|max:30|regex:/' . def_number_valid
        ];
        if (!empty(Request::all())) {
            if (Request::get('howfinds_id') != '-1') {
                unset($rules['howfinds_other']);
            }
            if (Request::get('school_type') != '-1') {
                unset($rules['school_type_other']);
            }
            if (Request::get('whoyous_id') != '-1') {
                unset($rules['whoyous_other']);
            }
           /* if (Request::get('ks1_maths_baseline') == '1') {
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
            }*/
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
            'school_name.required' => trans('admin/school.school_name_error'),
            'school_name.regex' => trans('admin/school.school_name_error'),
            'school_type.required' => trans('admin/school.school_type_error'),
            'school_type_other.required' => trans('admin/admin.other_error'),
            'username.required' => trans('admin/admin.username_error'),
            'username.regex' => trans('admin/admin.username_error'),
            'username.unique' => trans('admin/admin.username_unique_error'),
            'email.unique' => trans('admin/admin.email_unique_error'),
            'email.required' => trans('admin/admin.email_error'),
            'email.email' => trans('admin/admin.email_error'),
            'password.required' => trans('admin/admin.password_error'),
            'confirm_password.required' => trans('admin/admin.confirm_password_error'),
            'confirm_password.same' => trans('admin/admin.confirm_password_same_error'),
            'whoyous_id.required' => trans('admin/admin.whoyous_id_error'),
            'whoyous_other.required' => trans('admin/admin.other_error'),
            'telephone_no.required' => trans('admin/admin.telephone_no_error'),
            'telephone_no.regex' => trans('admin/admin.telephone_no_error'),
            'address.required' => trans('admin/admin.address_error'),
            'city.required' => trans('admin/admin.city_error'),
            'postal_code.required' => trans('admin/admin.postal_code_error'),
            'postal_code.alpha_num' => trans('admin/admin.postal_code_error'),
            'howfinds_other.required' => trans('admin/tutor.howfinds_other'),
            'howfinds_id.required' => trans('admin/admin.how_you_find_error'),
            'county.required' => trans('admin/admin.county_error'),
            'country.required' => trans('admin/admin.country_error'),
            'image.image' => trans('admin/admin.imagevalid_error'),
            'image.max' => trans('admin/admin.imagemaxsize_error'),
            'dfe_number.required' => trans('admin/admin.dfe_number_error'),
            'dfe_number.regex' => trans('admin/admin.dfe_number_error')
        ];
    }

}
