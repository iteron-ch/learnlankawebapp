<?php namespace App\Http\Requests\QuestionAdmin;

use App\Http\Requests\QuestionAdmin\QuestionAdminRequest;

class QuestionAdminUpdateRequest extends QuestionAdminRequest {

    public function rules() {
        $id = decryptParam($this->questionadmin);
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
        return $messages;
    }
    

    public function authorize() {
        return true;
    }

}
