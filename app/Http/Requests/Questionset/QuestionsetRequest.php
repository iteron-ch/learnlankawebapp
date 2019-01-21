<?php

/**
 * This file is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Questionset;

use App\Http\Requests\Request;

/**
 * This class is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class QuestionsetRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        
        $rules = [];
        
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function commonMessages() {
        return [];
    }
}
