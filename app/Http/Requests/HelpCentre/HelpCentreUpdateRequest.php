<?php

/**
 * This file is used for HelpCentre validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\HelpCentre;

use App\Http\Requests\HelpCentre\HelpCentreRequest;

/**
 * This class is used for HelpCentre validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class HelpCentreUpdateRequest extends HelpCentreRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = $this->commonRules();
        $rules['visible_to'] = 'required';
        $rules['title'] = 'required|min:2|max:30|regex:/' . alpha_valid . '|unique:helpcentres,title,NULL,id,deleted_at,0000-00-00 00:00:00';
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
