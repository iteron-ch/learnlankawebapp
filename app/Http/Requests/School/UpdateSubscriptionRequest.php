<?php

/**
 * This file is used for school validations.
 * @package    User, Student
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\School;
use App\Http\Requests\Request;


/**
 * This class is used for school validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class UpdateSubscriptionRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
     
     // $rules['billing_first_name'] = 'required|min:2|max:40|alpha';
     // $rules['billing_last_name'] = 'required|min:2|max:40|alpha';
     // $rules['billing_address'] = 'required|min:5|max:100';
     // $rules['billing_city'] = 'required';
     // $rules['billing_postal_code'] = 'required|min:4|max:7|regex:/' . alpha_numeric_valid;
    //  $rules['billing_county'] = 'required';
    //  $rules['billing_country'] = 'required';
      $rules['card_no'] = 'required';
      $rules['cvv_no'] = 'required';
      
      
      if (!empty(Request::all())) {
            if (Request::get('payment_method') == 'Invoiced') {

              //  unset($rules['billing_first_name']);
              //  unset($rules['billing_last_name']);
              //  unset($rules['billing_address']);
              //  unset($rules['billing_city']);
              //  unset($rules['billing_postal_code']);
              //  unset($rules['billing_county']);
              //  unset($rules['billing_country']);
                unset($rules['card_no']);
                unset($rules['cvv_no']);
            }
        }
        return $rules;
        
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        //$messages['billing_address.required'] = trans('front/front.address_error');
       // $messages['billing_city.required'] = trans('front/front.city_error');
        //$messages['billing_postal_code.required'] = trans('front/front.postal_code_error');
        //$messages['billing_postal_code.alpha_num'] = trans('front/front.postal_code_error');
       // $messages['billing_county.required'] = trans('front/front.county_error');
       // $messages['billing_country.required'] = trans('front/front.country_error');
        $messages['card_no.required'] = trans('front/front.card_no_error');
        $messages['cvv_no.required'] = trans('front/front.cvv_no_error');
        //$messages['billing_first_name.required'] = trans('front/front.billing_first_name_error');
        //$messages['billing_first_name.alpha'] = trans('front/front.billing_first_name_error');
        //$messages['billing_last_name.required'] = trans('front/front.billing_last_name_error');
       // $messages['billing_last_name.alphs'] = trans('front/front.billing_last_name_error');  
        // $messages['howfinds_id_other.required'] = trans('front/front.other_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
