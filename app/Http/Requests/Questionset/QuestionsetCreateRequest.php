<?php 
/**
 * This file is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
namespace App\Http\Requests\Questionset;

use App\Http\Requests\Questionset\SQuestionsetRequest;

/**
 * This class is used for student validations.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class QuestionsetCreateRequest extends QuestionsetRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        //$rules = $this->commonRules();
        $rules['set_name'] = 'required|min:3|max:100|unique:questionsets,set_name,NULL,id,deleted_at,0000-00-00 00:00:00';       
        $rules['ks_id'] = 'required';
        $rules['year_group'] = 'required';
        $rules['subject'] = 'required';
        $rules['set_group'] = 'required';
        
        return $rules;
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        $messages = $this->commonMessages();
        return $messages;
    }

    public function authorize() {
        return true;
    }

}
