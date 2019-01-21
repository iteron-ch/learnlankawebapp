<?php

namespace App\Http\Requests\Notification;

use App\Http\Requests\Notification\NotificationRequest;
use App\Http\Requests\Request;

class NotificationCreateRequest extends NotificationRequest {

    public function rules() {
        $rules = $this->commonRules();
      

        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages = $this->commonMessages();
        //$messages['user_type.required'] = trans('admin/admin.title_error');
        
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
