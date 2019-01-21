<?php

namespace App\Http\Requests\HelpCentre;

use App\Http\Requests\Request;

class HelpCentreRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        $this->sanitize();
        $rules = [];
       /* $rules = [
            'strands_id' => 'required',
            'sub_strands_id' => 'required',
        ];
*/
        /*if (!empty(Request::all())) {
            if (Request::get('media_type') === '1' || Request::get('media_type') === '5') {
                $rules['media_link'] = 'required';
            }
            if (Request::get('media_type') === '2' || Request::get('media_type') === '3' || Request::get('media_type') === '4') {
                $rules['doc'] = 'required';
            }
            if (Request::get('category') != '9' || Request::get('category') != '10' ) {
                $rules['strands_id'] = 'required';
            }
        }*/
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function commonMessages() {
        return [
            'strands_id.required' => trans('admin/admin.select_error'),
            'sub_strands_id.required' => trans('admin/admin.select_error'),
            'doc.required' => trans('admin/helpcentre.doc_error'),
            'media_link.required' => trans('admin/helpcentre.link_error'),
            'doc.mimes' => trans('admin/helpcentre.doc_error'),
            'doc.unique' => 'not unique',
        ];
    }

}
