<?php 
/**
 * This file is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
namespace App\Http\Requests\Student;

use App\Http\Requests\Student\StudentRequest;

/**
 * This class is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class StudentCreateRequest extends StudentRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = $this->commonRules();
        $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid.'|unique:users,username,NULL,id,deleted_at,0000-00-00 00:00:00';       
        $rules['email'] = 'email|min:6|max:75|unique:users,email,NULL,id,deleted_at,0000-00-00 00:00:00';
        $rules['password'] = 'required|min:6|max:20';
        $rules['confirm_password'] = 'required|min:6|max:20|same:password';
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
