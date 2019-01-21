<?php

namespace App\Http\Requests;

class RewardsRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        $rules = [
            'percent_min' => 'required|numeric|regex:/' . amount_valid,
            'percent_max' => 'required|numeric|regex:/' . amount_valid,
            'subject' => 'required',
            'question_set' => 'required',
            'strand' => 'required',
            'studentawards_id' => 'required',
            'student_source' => 'required'
        ];
        if (!empty(Request::all())) { 
            $rules['percent_min'] = 'required|max:99.99|numeric|regex:/' . amount_valid;
            $rules['percent_max'] = 'required|max:100|numeric|regex:/' . amount_valid;
            if(!empty($this->get('percent_min')) && !empty($this->get('percent_max')) && $this->get('percent_min') >= $this->get('percent_max')){
               $rules['percent_range'] = 'required';
            }
            if (ucfirst($this->segment(2)) == TEST) {
                unset($rules['strand']);
                unset($rules['substrand']);
            } else {
                unset($rules['question_set']);
            }
            if(session()->get('user')['user_type'] == ADMIN || session()->get('user')['user_type'] == TUTOR){
                unset($rules['student_source']);
            }
            
            if (Request::get('student_source') && empty($this->get('students'))) {
                $rules['students'] = 'required';
            }
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
            'percent_min.regex' => trans('admin/admin.marks_error'),
            'percent_max.regex' => trans('admin/admin.marks_error'),
            'certi.required' => trans('admin/admin.select_error'),
            'question_set.required' => trans('admin/admin.select_error'),
            'subject.required' => trans('admin/admin.select_error'),
            'percent_min.required' => trans('admin/admin.marks_error'),
            'percent_min.max' => trans('admin/admin.percent_min_notgreater_error'),
            'percent_max.max' => trans('admin/admin.percent_max_notgreater_error'),
            'percent_max.required' => trans('admin/admin.marks_error'),
            'studentawards_id.required' => trans('admin/admin.select_certificate_error'),
            'student_source.required' => trans('admin/admin.select_student_source_error'),
            'students.required' => trans('admin/admin.atleast_students_error'),
            'percent_range.required' => trans('admin/admin.percent_range_error'),
        ];
    }

}
