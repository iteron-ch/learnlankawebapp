<?php

namespace App\Http\Requests\HelpCentre;

use App\Http\Requests\HelpCentre\HelpCentreRequest;

class HelpCentreUpdateProfileRequest extends HelpCentreRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = decryptParam($this->helpcentre);
        $rules = $this->commonRules();
        $rules['title'] = 'required';
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages = $this->commonMessages();
        $messages['title.required'] = trans('admin/helpcentre.title_error');

        return $messages;
    }

    public function authorize() {
        return true;
    }

}
