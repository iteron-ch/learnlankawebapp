<?php namespace App\Http\Requests\Enquiry;

use App\Http\Requests\Enquiry\EnquiryRequest;

class EnquiryCreateRequest extends EnquiryRequest {

    public function rules() {
        $rules = $this->commonRules();
        $rules['user_type'] = 'required';
        $rules['title'] = 'required|min:1|max:30|regex:/' . alpha_valid;
        $rules['email'] = 'required|email|min:6|max:75';
        
        return $rules;
    }
    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages = $this->commonMessages();
//        $messages['title.required'] = trans('admin/helpcentre.require_error');
       
        $messages['title.required'] = trans('admin/enquiry.title_error');
        return $messages;
    }

     public function authorize() {
        return true;
    }

}
