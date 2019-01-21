<?php namespace App\Http\Requests;

class UserPasswordUpdateRequest extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
            $this->sanitize();
            return [
                    'old_password' => 'required|min:6|max:20',
                    'password' => 'required|min:6|max:20',
                    'confirm_password' => 'required|min:6|max:20|same:password',
            ];
	}
        
        /**
	 * Get the validation message that apply to the request.
	 *
	 * @return array
	 */
        public function messages()
        {
                return [
                    'old_password.required' => trans('admin/admin.password_error'),
                    'password.required' => trans('admin/admin.password_error'),
                    'confirm_password.required' => trans('admin/admin.confirm_password_error'),
                    'confirm_password.same' => trans('admin/admin.confirm_password_same_error'),
                ];
        }
        

}
