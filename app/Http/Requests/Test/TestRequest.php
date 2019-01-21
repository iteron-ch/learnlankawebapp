<?php

/**
 * This file is used for test set validations.
 * @package    Task
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Requests\Test;

use App\Http\Requests\Request;

/**
 * This class is used for test test validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class TestRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        return [
            'assign_date' => 'required',
            'completion_date' => 'required',
            'classstudents_selected' => 'required'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'assign_date.required' => trans('admin/task.assign_date_error'),
            'completion_date.required' => trans('admin/task.completion_date_error'),
            'classstudents_selected.required' => trans('admin/task.students_error')
        ];
    }

}
