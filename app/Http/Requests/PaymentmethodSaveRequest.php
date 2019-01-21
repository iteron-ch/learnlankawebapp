<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PaymentmethodSaveRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        return [
            'paypal_method' => 'required',
            'paypal_email' => 'required|email',
            'transaction_key' => 'required',
            'transaction_user_id' => 'required',
            'transaction_password' => 'required'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'paypal_method.required' => trans('admin/paymentmethod.paypal_method_error'),
            'paypal_email.required' => trans('admin/paymentmethod.paypal_email_error'),
            'transaction_key.required' => trans('admin/paymentmethod.paypal_transaction_error'),
            'transaction_user_id.required' => trans('admin/paymentmethod.user_id_error'),
            'transaction_password' => trans('admin/paymentmethod.password')
        ];
    }

}
