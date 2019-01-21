<?php

namespace App\Http\Requests\Voucher;

use App\Http\Requests\Request;

class VoucherRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        $this->sanitize();
        $rules = [
            'discount' => 'required|min:1|numeric',
            'discount_type' => 'required',
            'end_date' => 'required|min:2|max:50',
            'start_date' => 'required',
            'user_type' => 'required',
            'status' => 'required'
        ];
        if(!empty(Request::all()))
        {
            if(Request::get('discount_type') == 'Percent'){
                 $rules['discount'] = 'required|min:1|max:100|numeric';
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
            'voucher_code.required' => trans('admin/voucher.voucher_code_error'),
            'voucher_code.regex' => trans('admin/voucher.voucher_code_error'),
            'discount.required' => trans('admin/voucher.discount_error'),
            'discount.numeric' => trans('admin/voucher.discount_error'),
            'discount_type.required' => trans('admin/voucher.discount_type_error'),
            'start_date.required' => trans('admin/voucher.start_date_error'),
            'end_date.required' => trans('admin/voucher.end_date_error'),
            'user_type.required' => trans('admin/voucher.user_type_error'),
            'status.required' => trans('admin/voucher.status_error')
        ];
    }

}
