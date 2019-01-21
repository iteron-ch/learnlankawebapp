<?php

/**
 * This file is used for taskassignment validations.
 * @package    Taskassignment
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Requests\Taskassignment;

use App\Http\Requests\Request;

/**
 * This file is used for taskassignment validations.
 * @package    Taskassignment
 * @author     Icreon Tech  - dev2.
 */
class TaskassignmentRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        return [
            //'students' => 'required',
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'subject.required' => trans('admin/topic.subject_error'),
            'strands_id.required' => trans('admin/topic.strands_id_error'),
            'substrands_id.required' => trans('admin/topic.substrands_id_error'),
            
        ];
    }

}
