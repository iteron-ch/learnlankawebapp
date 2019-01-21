<?php

/**
 * This is used for question builder amin save/edit for users data.
 * @package    User
 * @author     Icreon Tech  - dev5.
 */

namespace App\Repositories;

use Image;
use Carbon\Carbon;
use App\Models\QuestionValidator;
use DB;

class QuestionValidatorRepository extends BaseRepository {

    /**
     * The QuestionAdmin instance.
     *
     * @var App\Models\QuestionAdmin
     */
    protected $questionValiSet;

    /**
     * Create a new QuestionAdminRepository instance.
     *
     * @param  App\Models\QuestionAdmin 
     * @return void
     */
    public function __construct(QuestionValidator $questionValiSet) {
        $this->questionValiSet = $questionValiSet;
    }

    /**
     * Save the QuestionAdmin.
     * @author     Icreon Tech  - dev5.
     * @param  App\Models\QuestionAdmin 
     * @param  Array  $inputs
     * @return void
     */
    public function save($inputs, $questionValiSet) {
        //asd($inputs);
        $questionValiSet->first_name = $inputs['first_name'];
        $questionValiSet->last_name = $inputs['last_name'];
        $questionValiSet->username = $inputs['username'];
        $questionValiSet->email = $inputs['email'];
        $questionValiSet->user_type = $inputs['user_type'];
        $questionValiSet->confirmed = '1';
        if (isset($inputs['image'])) {
            $questionValiSet->image = $inputs['image'];
        }


        if (isset($inputs['id'])) {
            $questionValiSet->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
            $questionValiSet->status = $inputs['status'];
        } else {
            $questionValiSet->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $questionValiSet->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
        }

        //$questionValiSet->save();
        $lastId = $questionValiSet->save() ? $questionValiSet->id : FALSE;
        return $lastId;
    }

    /**
     * Create a Voucher.
     * @author Icreon Tech  - dev5.
     * @param  array  $inputs
     * @return App\Models\Voucher 
     */
    public function store($inputs) {
        $questionValiSet = new $this->questionValiSet;
        $questionValiSet->password = bcrypt($inputs['password']);
        $lastInsertedId = $this->save($inputs, $questionValiSet);
         if ($lastInsertedId) {
            $insertCountarray = array();
            $insertCountarray['user_id'] = $lastInsertedId;
            $insertCountarray['question_validated'] = 0;
            DB::table('validator_question_count')->insert($insertCountarray);
        }
    }
    /**
     * Insert user entery on user count
     * @author     Icreon Tech - dev2.
     * @param type array $input
     * @param type int $user_id
     */
    public function saveUserCount($inputs, $user_id) {
       
    }
    /**
     * Update a Voucher.
     * @author     Icreon Tech  - dev5.
     * @param  array  $inputs
     * @param  App\Models\Voucher 
     * @return void
     */
    public function update($inputs, $id, $userDeleteImage = FALSE) {
        $questionValiSet = $this->questionValiSet->where('id', '=', $id)->first();
        //delete old image
        if ($userDeleteImage && !empty($user->image)) {
            $this->userDeleteImage($user->image);
        }
        if (!empty($inputs['password'])) {
            $questionValiSet->password = bcrypt($inputs['password']);
        }
        $this->save($inputs, $questionValiSet);
    }

    function userImageUpload($image) {
        $fileName = str_random(10) . '_' . time() . '.' . $image->getClientOriginalExtension(); // renameing image
        $pathLarge = public_path('uploads/user/large/' . $fileName);
        $pathMedium = public_path('uploads/user/medium/' . $fileName);
        $pathSmall = public_path('uploads/user/small/' . $fileName);
        Image::make($image->getRealPath())->resize(UIMG_L_W, UIMG_L_H)->save($pathLarge);
        Image::make($image->getRealPath())->resize(UIMG_M_W, UIMG_M_H)->save($pathMedium);
        Image::make($image->getRealPath())->resize(UIMG_S_W, UIMG_S_H)->save($pathSmall);
        return $fileName;
    }

    public function showAdmin($id) {
        $user = $this->user
                        ->join('countries', 'users.country', '=', 'countries.country_code')
                        ->join('counties', 'users.county', '=', 'counties.id')
                        ->join('howfinds', 'users.howfinds_id', '=', 'howfinds.id')
                        ->where('users.user_type', '=', TUTOR)
                        ->where('users.id', '=', $id)
                        ->where('users.status', '!=', DELETED)
                        ->select(['users.id', 'users.username', 'users.first_name',
                            'users.last_name', 'users.email', 'users.created_at', 'users.date_of_birth',
                            'users.updated_at', 'users.school_name', 'users.status',
                            'users.city', 'users.postal_code', 'users.address', 'users.gender',
                            'users.image', 'users.telephone_no', 'howfinds.name AS how_you_find',
                            'countries.printable_name AS country', 'counties.name AS county'
                        ])
                        ->get()->first();
        return $user;
    }

    public function showQuestionAdmin($id) {
        return $this->questionValiSet
                        ->where('id', '=', $id)
                        ->select(['id', 'first_name', 'last_name', 'image', 'username', 'email','status','created_at'])
                        ->get()->first();
    }

    /**
     * Update a Voucher status.
     *
     * @param  array  $inputs
     * @param  App\Models\Voucher 
     * @return void
     */
    public function destroyAdmin($inputs, $id) {
        $questionValiSet = $this->questionValiSet->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $questionValiSet->updated_by = session()->get('user')['user_type'];
        $questionValiSet->deleted_at = $dateTime;
        $questionValiSet->status = DELETED; // deleted
        $questionValiSet->save();
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    public function getQuestionValidator() { 
        return $this->questionValiSet
                        //->join('strands', 'topicassignments.strands_id', '=', 'strands.parent_id')
                        ->join('validator_question_count', 'validator_question_count.user_id', '=', 'users.id','left')
                        ->where('user_type', '=', QUESTIONVALIDATOR)
                        ->where('status', '!=', DELETED)
                        ->select(['users.id', 'status','first_name', 'last_name', 'email', 'username', 'created_at','last_login' ,'updated_at','question_validated']);

    }
}
