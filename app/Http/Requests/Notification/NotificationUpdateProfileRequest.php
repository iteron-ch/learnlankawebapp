<?php namespace App\Http\Requests\Notification;

use App\Http\Requests\Notification\NotificationRequest;

class NotificationUpdateProfileRequest extends NotificationRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
   public function rules() {
        $rules['title'] = 'required|min:1|max:30|';
        $rules['user_type'] = 'required';
        $rules['status'] = 'required';
        //$rules['seleted_student'] = 'required';       
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        //$messages = $this->commonMessages();
        $messages['title.required'] = trans('admin/admin.password_error');
        
        return $messages;
    }

    public function authorize() {
        return true;
    }

}