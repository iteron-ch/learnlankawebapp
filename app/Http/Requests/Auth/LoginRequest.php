<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class LoginRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'log' => 'required', 'password' => 'required',
        ];
    }

    public function messages() {
        return [
            'log.required' => trans('admin/auth/login.log_require_error'),
            'password.required' => trans('admin/auth/login.password_require_error'),
        ];
    }

}
