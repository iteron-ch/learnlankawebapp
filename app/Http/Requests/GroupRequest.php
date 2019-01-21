<?php

namespace App\Http\Requests;

class GroupRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $this->sanitize();
        $id = !empty(decryptParam($this->group)) ? decryptParam($this->group) : NULL;
        return [
            //'group_name' => 'required|min:1|max:20|regex:/' . alpha_numeric_valid . '|unique:groups,group_name,' . $id . ',id,deleted_at,0000-00-00 00:00:00',
            'group_name' => 'required|min:1|max:20|regex:/' . alpha_numeric_valid,
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
            'group_name.required' => trans('admin/group.group_name_error'),
            'group_name.regex' => trans('admin/group.group_name_error'),
            'status.required' => trans('admin/group.status_error'),
        ];
    }

}
