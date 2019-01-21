<?php

namespace App\Http\Requests\HelpCentre;

use App\Http\Requests\HelpCentre\HelpCentreRequest;
use App\Http\Requests\Request;

class HelpCentreCreateRequest extends HelpCentreRequest {

    public function rules() {
        $rules['title'] = 'required';
        $rules['category_selected'] = 'required';
        $rules['visible_to_selected'] = 'required';
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages['visible_to.required'] = trans('admin/helpcentre.visible_to_error');
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
