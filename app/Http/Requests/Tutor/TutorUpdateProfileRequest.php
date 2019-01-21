<?php

/**
 * This file is used for tutor validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Tutor;

use App\Http\Requests\Tutor\TutorRequest;

/**
 * This class is used for tutor validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class TutorUpdateProfileRequest extends TutorRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = session()->get('user')['id'];
        $rules = $this->commonRules();
        $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid . '|unique:users,username,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
        $rules['email'] = 'required|email|min:6|max:75|unique:users,email,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages = $this->commonMessages();
        $messages['username.unique'] = trans('admin/admin.username_unique_error');
        $messages['email.unique'] = trans('admin/admin.email_unique_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
