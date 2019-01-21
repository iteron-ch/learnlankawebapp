<?php

/**
 * This file is used for test set validations.
 * @package    Task
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Requests\Task;

use App\Http\Requests\Request;

/**
 * This class is used for test test validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class TasktestRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        return [
            'key_stage' => 'required',
            'year_group' => 'required',
            'subject' => 'required',
            'question_set' => 'required',
            'assign_date' => 'required',
            'completion_date' => 'required',
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'key_stage.required' => trans('admin/task.key_stage_error'),
            'year_group.required' => trans('admin/task.year_group_error'),
            'subject.required' => trans('admin/task.subject_error'),
            'question_set.required' => trans('admin/task.question_set_error'),
            'assign_date.required' => trans('admin/task.assign_date_error'),
            'completion_date.required' => trans('admin/task.completion_date_error'),
            
        ];
    }

}
