<?php

/**
 * This file is used for tutor validations.
 * @package    User, Student
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Payment;

use App\Http\Requests\Request;

/**
 * This class is used for tutor validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class PaymentRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() { 
        $this->sanitize();
        $rules['payment_status'] = 'required';
        $rules['payment_note'] = 'required';
        
        return $rules;
    }

    public function messages() {
        return [
            'payment_status.required' => 'Please check payment status.',
            'payment_note.required' => 'Please enter note.',
        ];
    }
  public function authorize() {
        return true;
    }
}
