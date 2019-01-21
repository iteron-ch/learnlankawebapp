<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Enquiry;
use App\Models\User;

class EnquiryRepository extends BaseRepository {

    /**
     * The Enquiry instance.
     * @author Icreon Tech - dev5.
     * @var App\Models\Enquiry
     */
    protected $enquirySet;
    protected $school;

    /**
     * Create a new EnquiryRepository instance.
     * @author Icreon Tech - dev5.
     * @param  App\Models\Enquiry 
     * @return void
     */
    public function __construct(Enquiry $enquirySet, User $school) {
        $this->enquirySet = $enquirySet;
        $this->school = $school;
    }

    /**
     * Save the Enquiry.
     * @author Icreon Tech - dev5.
     * @param  App\Models\Enquiry 
     * @param  Array  $inputs
     * @return void
     */
    public function save($inputs, $enquirySet) {
        $enquirySet->title = $inputs['title'];
        $enquirySet->user_type = $inputs['user_type'];
        $enquirySet->city = isset($inputs['cities']) ? $inputs['cities'] : '';
        $enquirySet->first_name = $inputs['first_name'];
        $enquirySet->last_name = $inputs['last_name'];
        $enquirySet->email = $inputs['email'];
        //$enquirySet->how_hear_other = isset($inputs['how_hear_other']) && $inputs['how_hear'] == OTHER_VALUE ? $inputs['how_hear_other'] : '';
        $enquirySet->how_hear_other = isset($inputs['how_hear_other']) && $inputs['how_hear'] == OTHER_VALUE ? $inputs['how_hear_other'] : '';
        $enquirySet->postal_code = isset($inputs['postal_code']) ? $inputs['postal_code'] : '';
        $enquirySet->county = isset($inputs['county']) ? $inputs['county'] : '0';
        $enquirySet->school = isset($inputs['school']) ? $inputs['school'] : '0';
        $enquirySet->contact_no = isset($inputs['contact_no']) ? $inputs['contact_no'] : '';
        $enquirySet->how_hear = $inputs['how_hear'];
        $enquirySet->job_role = $inputs['job_role'];
        if (isset($inputs['howfinds_other'])) {
            $enquirySet->howfinds_other = $inputs['howfinds_other'];
        }
        $enquirySet->save();
        return $lastId = $enquirySet->save() ? $enquirySet->id : FALSE;
    }

    /**
     * Create a new Enquiry.
     * @author Icreon Tech - dev5.
     * @param  array  $inputs
     * @return App\Models\Enquiry 
     */
    public function store($inputs) {
        //asd($inputs);
        $enquirySet = new $this->enquirySet;
        return $this->save($inputs, $enquirySet);
    }

    public function showEnquiry($id) {
        $enquirySet = $this->enquirySet
                ->where('enquiries.id', '=', $id)
                ->select(['enquiries.id', 'title', 'enquiries.user_type', 'enquiries.first_name', 'enquiries.last_name', 'enquiries.email', 'enquiries.city',
                    'enquiries.school', 'enquiries.postal_code', 'enquiries.contact_no', 'enquiries.created_at', 'enquiries.county', 'how_hear', 'enquiries.how_hear_other','job_role'])
                ->get();
        return $enquirySet;
    }

    /**
     * Fetch all Enquiry records from database. 
     * @author Icreon Tech - dev5.
     * @param type $params
     * @return type
     */
    public function getEnquiryList() {
        return $this->enquirySet
                        ->where('status', '!=', DELETED)
                        ->select(['id', 'user_type', 'title', 'first_name', 'last_name', 'email', 'contact_no','how_hear','job_role', 'updated_at','how_hear_other','created_at']);
    }

}
