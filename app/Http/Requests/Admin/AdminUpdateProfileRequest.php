<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\AdminRequest;

class AdminUpdateProfileRequest extends AdminRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = session()->get('user')['id'];
        $rules = $this->commonRules();
        $rules['username'] = 'required|min:2|max:30|regex:/' . alpha_valid.'|unique:users,username,' . $id.',id,deleted_at,0000-00-00 00:00:00';       
        $rules['email'] = 'required|email|min:6|max:75|unique:users,email,' . $id.',id,deleted_at,0000-00-00 00:00:00';
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