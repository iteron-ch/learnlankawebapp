<?php

namespace App\Http\Requests;

class SchoolClassRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        $id = !empty(decryptParam($this->manageclass)) ? decryptParam($this->manageclass) : NULL;
        return [
            //'class_name' => 'required|min:1|max:20|regex:/' . alpha_numeric_valid . '|unique:schoolclasses,class_name,' . $id . ',id,deleted_at,0000-00-00 00:00:00',
            'class_name' => 'required|min:1|max:20|regex:/' . alpha_numeric_valid,
            'year' => 'required',
            'status' => 'required',
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        return [
            'class_name.required' => trans('admin/schoolclass.class_name_error'),
            'year.required' => trans('admin/schoolclass.year_error'),
            'status.required' => trans('admin/schoolclass.status_error'),
        ];
    }

}
