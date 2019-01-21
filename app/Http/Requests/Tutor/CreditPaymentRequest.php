<?php

/**
 * This file is used for tutor validations.
 * @package    User, Student
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Tutor;

use App\Http\Requests\Request;

/**
 * This class is used for tutor validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class CreditPaymentRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        // $rules = array();
        $rules['card_no'] = 'required';
        $rules['cvv_no'] = 'required';
        if (Request::get('payment_type') == 'Invoiced') {
            unset($rules['card_no']);
            unset($rules['cvv_no']);
        }
        return $rules;
    }

    public function messages() {
        $messages['card_no.required'] = trans('front/front.card_no_error');
        $messages['cvv_no.required'] = trans('front/front.cvv_no_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
