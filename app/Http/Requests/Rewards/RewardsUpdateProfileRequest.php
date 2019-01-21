<?php namespace App\Http\Rewards\Rewards;

use App\Http\Requests\Rewards\RewardsRequest;

class RewardsUpdateProfileRequest extends RewardsRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
   public function rules() {
        
        //$rules['seleted_student'] = 'required';       
        
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages() {
        //$messages = $this->commonMessages();
        
        
        
    }

    public function authorize() {
        return true;
    }

}