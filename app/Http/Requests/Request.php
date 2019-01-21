<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest {

    public function authorize() {
        // Honeypot 
        return $this->input('address') == '';
    }

    public function sanitize() {

        $input = array_map(function ($value) {
            if (is_string($value)) {
                return trim($value);
            } else {
                return $value;
            }
        }, $this->all());
        $this->replace($input);
    }

}
