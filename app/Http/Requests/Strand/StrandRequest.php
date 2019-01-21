<?php

/**
 * This file is used for strand validations.
 * @package    Strand
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Requests\Strand;

use App\Http\Requests\Request;

/**
 * This class is used for strand validations.
 * @package    Strand
 * @author     Icreon Tech  - dev1.
 */
class StrandRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() { 
        $this->sanitize();
        $rules = [
            'subject' => 'required',
            'strand' => 'required|min:2|max:200',
            'alias_sub_strand' => 'required|min:2|max:200' ,
            'reference_code' => 'required|min:1|max:6' ,
            'appendices' => 'min:2|max:1000',
        ];
        if(empty(Request::all()) || Request::get('is_substrand'))
        {
            $rules = $rules + array('parent_id' => 'required');
        }
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function commonMessages() {
        return [
            'parent_id.required' => trans('admin/strand.strand_error'),
            'subject.required' => trans('admin/strand.subject_error'),
            'strand.required' => trans('admin/strand.strand_name_error'),
            'alias_sub_strand.required' => trans('admin/strand.alias_strand_name_error'),
            'strand.min' => trans('admin/strand.name_error_min'),
            'strand.max' => trans('admin/strand.name_error_max'),
            'alias_sub_strand.min' => trans('admin/strand.display_name_error_min'),
            'alias_sub_strand.max' => trans('admin/strand.display_name_error_max'),
            'reference_code.required' => trans('admin/strand.reference_code_error'),
            'reference_code.max' => trans('admin/strand.reference_code_error_max'),
        ];
    }

}
