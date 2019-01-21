<?php

/**
 * This is used for question builder amin save/edit for users data.
 * @package    User
 * @author     Icreon Tech  - dev5.
 */

namespace App\Repositories;

use Image;
use Carbon\Carbon;
use App\Models\QuestionAdmin;
use DB;

class QuestionAdminRepository extends BaseRepository {

    /**
     * The QuestionAdmin instance.
     *
     * @var App\Models\QuestionAdmin
     */
    protected $questionAdminSet;

    /**
     * Create a new QuestionAdminRepository instance.
     *
     * @param  App\Models\QuestionAdmin 
     * @return void
     */
    public function __construct(QuestionAdmin $questionAdminSet) {
        $this->questionAdminSet = $questionAdminSet;
    }

    /**
     * Save the QuestionAdmin.
     * @author     Icreon Tech  - dev5.
     * @param  App\Models\QuestionAdmin 
     * @param  Array  $inputs
     * @return void
     */
    public function save($inputs, $questionAdminSet) {
        //dd($inputs);
        $questionAdminSet->first_name = $inputs['first_name'];
        $questionAdminSet->last_name = $inputs['last_name'];
        $questionAdminSet->username = $inputs['username'];
        $questionAdminSet->email = $inputs['email'];
        $questionAdminSet->user_type = $inputs['user_type'];
        $questionAdminSet->confirmed = '1';
        if (isset($inputs['image'])) {
            $questionAdminSet->image = $inputs['image'];
        }


        if (isset($inputs['id'])) {
            $questionAdminSet->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
            $questionAdminSet->status = $inputs['status'];
        } else {
            $questionAdminSet->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $questionAdminSet->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
        }

        // $questionAdminSet->save();
        return $lastId = $questionAdminSet->save() ? $questionAdminSet->id : FALSE;
    }

    /**
     * Create a Voucher.
     * @author Icreon Tech  - dev5.
     * @param  array  $inputs
     * @return App\Models\Voucher 
     */
    public function store($inputs) {
        $questionAdminSet = new $this->questionAdminSet;
        $questionAdminSet->password = bcrypt($inputs['password']);
        $lastInsertedId = $this->save($inputs, $questionAdminSet);
        if ($lastInsertedId) {
            $insertCountarray = array();
            $insertCountarray['user_id'] = $lastInsertedId;
            $insertCountarray['question_count'] = 0;
            DB::table('question_admin_question_counts')->insert($insertCountarray);
        }
    }

    /**
     * Update a Voucher.
     * @author     Icreon Tech  - dev5.
     * @param  array  $inputs
     * @param  App\Models\Voucher 
     * @return void
     */
    public function update($inputs, $id, $userDeleteImage = FALSE) {
        $questionAdminSet = $this->questionAdminSet->where('id', '=', $id)->first();
        //delete old image
        if ($userDeleteImage && !empty($user->image)) {
            $this->userDeleteImage($user->image);
        }
        if (!empty($inputs['password'])) {
            $questionAdminSet->password = bcrypt($inputs['password']);
        }
        $this->save($inputs, $questionAdminSet);
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
        return $this->questionAdminSet
                        ->where('id', '=', $id)
                        ->select(['id', 'first_name', 'last_name', 'image', 'username', 'email','status', 'created_at'])
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
        $questionAdminSet = $this->questionAdminSet->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $questionAdminSet->updated_by = session()->get('user')['user_type'];
        $questionAdminSet->deleted_at = $dateTime;
        $questionAdminSet->status = DELETED; // deleted
        $questionAdminSet->save();
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    public function getQuestionAdmin() {
        return $this->questionAdminSet
                        //->join('strands', 'topicassignments.strands_id', '=', 'strands.parent_id')
                        //->join('users', 'topicassignments.student_id', '=', 'users.school_id')
                        ->join('question_admin_question_counts', 'question_admin_question_counts.user_id', '=', 'users.id','left')
                        ->where('user_type', '=', QUESTIONADMIN)
                        ->where('status', '!=', DELETED)
                        ->select(['users.id', 'first_name', 'status','last_name', 'email', 'username', 'created_at', 'updated_at','question_count','last_login']);
    }

}
