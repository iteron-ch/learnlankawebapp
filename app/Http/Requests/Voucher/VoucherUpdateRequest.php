<?php 

namespace App\Http\Requests\Voucher;

use App\Http\Requests\Voucher\VoucherRequest;

class VoucherUpdateRequest extends VoucherRequest {

    public function rules() {
        $id = decryptParam($this->voucher);
        $rules = $this->commonRules();
        $rules['voucher_code'] = 'required|min:5|max:20|regex:/' . alpha_numeric_no_space_valid.'|unique:vouchers,voucher_code,' . $id . ',id,deleted_at,0000-00-00 00:00:00';  
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages = $this->commonMessages();
        return $messages;
    }
    

    public function authorize() {
        return true;
    }

}
