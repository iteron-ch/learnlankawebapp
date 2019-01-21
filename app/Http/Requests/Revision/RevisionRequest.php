<?php

/**
 * This file is used for revision set validations.
 * @package    Task
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Requests\Revision;

use App\Http\Requests\Request;

/**
 * This class is used for test test validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class RevisionRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        $rules = [
            'classstudents_selected' => 'required',
            'key_stage' => 'required',
            'year_group' => 'required',
            'subject' => 'required',
            'strand' => 'required',
            'substrand[]' => 'required',
            'assign_date' => 'required',
            'completion_date' => 'required',
        ];
        if (!empty(Request::all())) {
            unset($rules['substrand[]']);
        }
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'classstudents_selected.required' => trans('admin/task.students_error'),
            'key_stage.required' => trans('admin/task.key_stage_error'),
            'year_group.required' => trans('admin/task.year_group_error'),
            'subject.required' => trans('admin/task.subject_error'),
            'strand.required' => trans('admin/task.strand_error'),
            'substrand[].required' => trans('admin/task.substrand_error'),
            'assign_date.required' => trans('admin/task.assign_date_error'),
            'completion_date.required' => trans('admin/task.completion_date_error'),
        ];
    }

}
