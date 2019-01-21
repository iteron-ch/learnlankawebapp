<?php

namespace App\Http\Requests;

class MessagesRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        $id = !empty(decryptParam($this->messages)) ? decryptParam($this->messages) : NULL;
        $rules['subject'] = 'required';
        $rules['message'] = 'required';
        $rules['recipients'] = 'required';
        return $rules;

    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'subject.required' => trans('admin/messages.subject_error'),
            'message.required' => trans('admin/messages.message_error'),
            'recipients.required' => trans('admin/messages.recipients_error'),
        ];
    }

}
