<?php

namespace App\Http\Requests\Rewards;

use App\Http\Requests\Rewards\RewardsRequest;
use App\Http\Requests\Request;

class RewardsCreateRequest extends RewardsRequest {

    public function rules() {
        $rules = $this->commonRules();
        $rules['student_type'] = 'required';
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
