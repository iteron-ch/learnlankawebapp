<?php

/**
 * This file is used for tutor validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Tutor;

use App\Http\Requests\Request;
use App\Http\Requests\Tutor\TutorRequest;

/**
 * This class is used for tutor validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class FrontTutorCreateRequest extends TutorRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = decryptParam($this->userId);

        $rules['first_name'] = 'required|min:2|max:40|regex:/' . only_alpha_valid;
        $rules['last_name'] = 'required|min:2|max:40|regex:/' . only_alpha_valid;
        if (!empty($id)) {
            $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid . '|unique:users,username,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
            $rules['email'] = 'required|email|min:6|max:75|unique:users,email,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
        } else {
            $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid . '|unique:users,username,NULL,id,deleted_at,0000-00-00 00:00:00';
            $rules['email'] = 'required|email|min:6|max:75|unique:users,email,NULL,id,deleted_at,0000-00-00 00:00:00';
            $rules['password'] = 'required|min:6|max:20';
            $rules['confirm_password'] = 'required|min:6|max:20|same:password';
        }
        $rules['date_of_birth'] = 'required';
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
        /*$rules['card_no'] = 'required';
        $rules['cvv_no'] = 'required';
        if (Request::get('payment_type') == 'Invoiced') {
            unset($rules['card_no']);
            unset($rules['cvv_no']);
        }*/

        $rules['billing_first_name'] = 'required|min:2|max:40|regex:/' . only_alpha_valid;
        $rules['billing_last_name'] = 'required|min:2|max:40|regex:/' . only_alpha_valid;

        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages['first_name.required'] = trans('admin/teacher.first_name_error');
        $messages['first_name.regex'] = trans('front/front.first_name_error');
        $messages['last_name.required'] = trans('admin/teacher.last_name_error');
        $messages['last_name.regex'] = trans('front/front.last_name_error');
        $messages['username.required'] = trans('front/front.username_error');
        $messages['username.unique'] = trans('front/front.username_unique_error');
        $messages['username.regex'] = trans('front/front.username_error');
        $messages['email.required'] = trans('front/front.email_error');
        $messages['email.email'] = trans('front/front.email_error');
        $messages['email.unique'] = trans('front/front.email_unique_error');
        $messages['date_of_birth'] = trans('front/front.dob_error');
        $messages['password.required'] = trans('front/front.password_error');
        $messages['confirm_password.required'] = trans('front/front.confirm_password_error');
        $messages['telephone_no.required'] = trans('front/front.telephone_no_error');
        $messages['telephone_no.regex'] = trans('front/front.telephone_no_error');
        $messages['address.required'] = trans('front/front.address_error');
        $messages['city.required'] = trans('front/front.city_error');
        $messages['postal_code.required'] = trans('front/front.postal_code_error');
        $messages['postal_code.alpha_num'] = trans('front/front.postal_code_error');
        $messages['county.required'] = trans('front/front.county_error');
        $messages['country.required'] = trans('front/front.country_error');
        $messages['howfinds_id.required'] = trans('front/front.how_you_find_error');
        //$messages['card_no.required'] = trans('front/front.card_no_error');
        //$messages['cvv_no.required'] = trans('front/front.cvv_no_error');
        $messages['billing_first_name.required'] = trans('front/front.billing_first_name_error');
        $messages['billing_first_name.regex'] = trans('front/front.billing_first_name_error');
        $messages['billing_last_name.required'] = trans('front/front.billing_last_name_error');
        $messages['billing_last_name.regex'] = trans('front/front.billing_last_name_error');
        $messages['howfinds_id_other.required'] = trans('front/front.other_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
