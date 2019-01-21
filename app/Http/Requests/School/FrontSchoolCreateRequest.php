<?php

/**
 * This file is used for school validations.
 * @package    User, Student
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\School;

use App\Http\Requests\Request;
use App\Http\Requests\School\SchoolRequest;

/**
 * This class is used for school validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class FrontSchoolCreateRequest extends SchoolRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        //$rules = $this->commonRules();
        $this->sanitize();
        $id = decryptParam($this->userId);
        $rules['school_name'] = 'required|min:2|max:50|regex:/' . alpha_special_char;
        $rules['school_type'] = 'required';
        $rules['school_type_other'] = 'required';
        $rules['whoyous_other'] = 'required';

        if (!empty(Request::all())) {
            if (Request::get('school_type') != '-1') {
                unset($rules['school_type_other']);
            }
        }
        if (!empty(Request::all())) {
            if (Request::get('whoyous_id') != '-1') {
                unset($rules['whoyous_other']);
            }
        }

        if (!empty($id)) {
            $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid . '|unique:users,username,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
            $rules['email'] = 'required|email|min:6|max:75|unique:users,email,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
        } else {
            $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid . '|unique:users,username,NULL,id,deleted_at,0000-00-00 00:00:00';
            $rules['email'] = 'required|email|min:6|max:75|unique:users,email,NULL,id,deleted_at,0000-00-00 00:00:00';
            $rules['password'] = 'required|min:6|max:20';
            $rules['confirm_password'] = 'required|min:6|max:20|same:password';
        }
        $rules['whoyous_id'] = 'required';
        $rules['telephone_no'] = 'required|min:5|max:20|regex:/' . numeric_valid;
        $rules['address'] = 'required|min:5|max:100';

        $rules['city'] = 'required|min:2|max:40';
        $rules['postal_code'] = 'required|min:4|max:8|regex:/'.alpha_numeric_valid;

        $rules['county'] = 'required';
        $rules['country'] = 'required';
        $rules['howfinds_id'] = 'required';
        $rules['howfinds_other'] = 'required';
        if (!empty(Request::all())) {
            if (Request::get('howfinds_id') != '-1') {
                unset($rules['howfinds_other']);
            }
        }        
        $rules['billing_first_name'] = 'required';

       /* $rules['card_no'] = 'required';
        $rules['cvv_no'] = 'required';
        if (Request::get('payment_type') == 'Invoiced') {
            unset($rules['card_no']);
            unset($rules['cvv_no']);
        }*/
        $rules['billing_first_name'] = 'required|min:2|max:40|regex:/' . only_alpha_valid;
        $rules['billing_last_name'] = 'required|min:2|max:40|regex:/' . only_alpha_valid;
        $rules['dfe_number'] = 'required|max:30|regex:/' . def_number_valid;
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        // $messages = $this->commonMessages();
        $messages['email.unique'] = trans('front/front.email_unique_error');
        $messages['email.required'] = trans('front/front.email_error');
        $messages['email.email'] = trans('front/front.email_error');
        $messages['password.required'] = trans('front/front.password_error');
        $messages['confirm_password.required'] = trans('front/front.confirm_password_error');
        $messages['confirm_password.same'] = trans('front/front.confirm_password_same_error');
        $messages['school_name.required'] = trans('front/school.school_name_error');
        $messages['school_type.required'] = trans('front/school.school_type_error');
        $messages['username.required'] = trans('front/front.username_error');
        $messages['username.unique'] = trans('front/front.username_unique_error');
        $messages['username.regex'] = trans('front/front.username_error');
        $messages['whoyous_id.required'] = trans('front/front.whoyous_id_error');
        $messages['telephone_no.required'] = trans('front/front.telephone_no_error');
        $messages['telephone_no.regex'] = trans('front/front.telephone_no_error');
        $messages['address.required'] = trans('front/front.address_error');
        $messages['city.required'] = trans('front/front.city_error');
        $messages['postal_code.required'] = trans('front/front.postal_code_error');
        $messages['postal_code.alpha_num'] = trans('front/front.postal_code_error');
        $messages['county.required'] = trans('front/front.county_error');
        $messages['country.required'] = trans('front/front.country_error');
     //   $messages['card_no.required'] = trans('front/front.card_no_error');
      //  $messages['cvv_no.required'] = trans('front/front.cvv_no_error');
        $messages['billing_first_name.required'] = trans('admin/teacher.first_name_error');
        $messages['billing_first_name.alpha'] = trans('front/front.billing_first_name_error');
        $messages['billing_last_name.required'] = trans('admin/teacher.last_name_error');
        $messages['billing_last_name.alphs'] = trans('front/front.billing_last_name_error');
        $messages['school_type_other.required'] = trans('front/front.other_error');
        $messages['whoyous_id_other.required'] = trans('front/front.other_error');
        $messages['dfe_number.required'] = trans('front/front.dfe_number_error');
        $messages['dfe_number.regex'] = trans('front/front.dfe_number_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
