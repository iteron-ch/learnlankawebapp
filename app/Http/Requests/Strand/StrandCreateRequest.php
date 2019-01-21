<?php

/**
 * This file is used for strand validations.
 * @package    Strand
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Strand;

use App\Http\Requests\Strand\StrandRequest;

/**
 * This class is used for strand validations.
 * @package    Strand
 * @author     Icreon Tech  - dev1.
 */
class StrandCreateRequest extends StrandRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
