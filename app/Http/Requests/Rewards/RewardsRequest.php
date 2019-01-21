<?php

namespace App\Http\Requests\Rewards;

use App\Http\Requests\Request;

class RewardsRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function commonRules() {
        $this->sanitize();
        $rules = [
            'marks' => 'required',
            'certificate' => 'required',
        ];
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function commonMessages() {

        return [
            'marks.regex' => trans('admin/rewards.marks_regex'),
            'marks.max' => trans('admin/rewards.max_marks'),
            'marks.required' => trans('admin/admin.marks_error'),
            'certi.required' => trans('admin/admin.select_error'),
        ];
    }

}
