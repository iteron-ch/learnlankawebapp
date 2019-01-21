<?php

/**
 * This file is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Questionset;

use App\Http\Requests\Questionset\QuestionsetRequest;

/**
 * This class is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class QuestionsetUpdateRequest extends QuestionsetRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = decryptParam($this->questionset);
        
        //$rules = $this->commonRules();
        //$rules['set_name'] = 'required|min:3|max:50|unique:questionsets,set_name,NULL,id,deleted_at,0000-00-00 00:00:00';       
        $rules['set_name'] = 'required|min:3|max:100|regex:/' . alpha_valid . '|unique:questionsets,set_name,' . $id . ',id,deleted_at,0000-00-00 00:00:00';
        $rules['ks_id'] = 'required';
        $rules['year_group'] = 'required';
        $rules['subject'] = 'required';
        $rules['set_group'] = 'required';

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
