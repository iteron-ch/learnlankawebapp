<?php

namespace App\Http\Requests\Enquiry;

use App\Http\Requests\Request;

class EnquiryRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        $rules = [
            'user_type' => 'required',
            'title' => 'required|min:1|max:30|regex:/' . alpha_valid,
            'email' => 'required|email|min:6|max:75',
            'first_name' => 'required|min:1|max:40|regex:/' . only_alpha_valid,
            'last_name' => 'required|min:1|max:40|regex:/' . only_alpha_valid,
            'school' => 'min:2|max:100',
            'cities' => 'min:2|max:100',
            'county' => 'min:2|max:100',
            'contact_no' => 'required|min:5|max:20|regex:/' . numeric_space_valid,
            'postal_code' => 'max:8|regex:/' . alpha_numeric_valid,
            'how_hear' => 'required',
            'how_hear_other' => 'required',
        ];
        
        
        if(!empty(Request::all()))
        {
            if(Request::get('how_hear') != '-1'){
                 unset($rules['how_hear_other']);
            }
            
        }
        if (Request::get('school') == '') {
            unset($rules['school']);
        }
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'user_type.required' => trans('front/enquiry.user_type_error'),
            'title.required' => trans('front/enquiry.title_error'),
            'email.required' => trans('front/enquiry.email_error'),
            'first_name.required' => trans('admin/teacher.first_name_error'),
            'first_name.regex' => trans('admin/teacher.first_name_error'),
            'last_name.required' => trans('admin/teacher.last_name_error'),
            'last_name.regex' => trans('admin/teacher.last_name_error'),
            'school.required' => trans('front/enquiry.school_error'),
            'cities.required' => trans('front/enquiry.cities_error'),
            'contact_no.required' => trans('front/enquiry.contact_no_error'),
            'postal_code.required' => trans('front/enquiry.postal_code_error'),
            'how_hear.required' => trans('front/enquiry.how_hear_error'),
            'how_hear_other.required' => trans('front/enquiry.how_hear_other_error'),
        ];
    }

}
