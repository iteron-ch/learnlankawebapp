<?php

namespace App\Http\Requests\Notification;

use App\Http\Requests\Request;

class NotificationRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        $this->sanitize();
        $rules = [
            'user_type' => 'required',
            'title' => 'required|min:2|max:100|regex:/' . alpha_valid . '|unique:notifications,title,NULL,id,deleted_at,0000-00-00 00:00:00',
            'description' => 'required|min:2|max:250',
        ];
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function commonMessages() {
        return [
            'title.required' => trans('admin/admin.title_error'),
            'description.required' => trans('admin/admin.description_error'),
            'user_type.required' => trans('admin/admin.select_error'),
        ];
    }

}
