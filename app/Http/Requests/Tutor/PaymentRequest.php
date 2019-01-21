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
class PaymentRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
         $rules['credit-card-number'] = 'required';
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'credit-card-number.required' => trans('admin/teacher.first_name_error'),
           
        ];
    }

}
