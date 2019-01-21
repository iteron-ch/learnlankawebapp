<?php namespace App\Http\Requests\Group;

use App\Http\Requests\Group\GroupRequest;

class GroupUpdateProfileRequest extends GroupRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   public function rules() {
        $rules['group_name'] = 'required|min:1|max:30|';
        $rules['student'] = 'required';       
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        
        $messages['group_name.unique'] ='No';
        $messages['student.required'] = trans('admin/voucher.discount_error');
//        $messages['password.required'] = trans('admin/admin.password_error');
//        $messages['confirm_password.required'] = trans('admin/admin.confirm_password_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}