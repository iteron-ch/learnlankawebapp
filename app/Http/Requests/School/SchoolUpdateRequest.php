<?php

/**
 * This file is used for school validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\School;

use App\Http\Requests\School\SchoolRequest;

/**
 * This class is used for school validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class SchoolUpdateRequest extends SchoolRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = decryptParam($this->school);
        $rules = $this->commonRules();
        $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid . '|unique:users,username,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
        $rules['email'] = 'required|email|min:6|max:75|unique:users,email,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
        $rules['password'] = 'min:6|max:20';
        $rules['confirm_password'] = 'min:6|max:20|same:password';
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages = $this->commonMessages();
        $messages['confirm_password.same'] = trans('admin/admin.confirm_password_same_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
