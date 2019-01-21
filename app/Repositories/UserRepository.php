<?php

/**
 * This is used for user save/edit for users data.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Repositories;

use App\Repositories\FeesRepository;
use App\Models\User;
use App\Models\Usercount;
use App\Models\BillingAddress;
use App\Models\Payment;
use App\Models\ClassStudents;
use Carbon\Carbon;
use Image;
use DB;

/**
 * This is used for user save/edit for users data.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class UserRepository extends BaseRepository {

    /**
     * The Student instance.
     *
     * @var App\Models\Student
     */
    protected $user;
    protected $group;
    protected $payment;
    protected $classStudents;
    protected $feesRepository;
    protected $currentDateTime;

    /**
     * Create a new UserRepository instance.
     *
     * @param  App\Models\User $user
     * @param  App\Models\Student $student
     * @return void
     */
    public function __construct(User $user, Usercount $usercount, FeesRepository $feesRepository, Payment $payment,ClassStudents $classStudents) {
        $this->user = $user;
        $this->feesRepository = $feesRepository;
        $this->usercount = $usercount;
        $this->payment = $payment;
        $this->classStudents = $classStudents;
        $this->currentDateTime = Carbon::now()->toDateTimeString();
    }

    /**
     * Save the User.
     *  
     * @param  App\Models\User $user
     * @param  Array  $inputs
     * @return void
     */
    private function save($user, $inputs) {
        
        $user->year_group = isset($inputs['year_group']) ? $inputs['year_group'] : '';
        $user->key_stage = isset($inputs['key_stage']) ? $inputs['key_stage'] : '';
        $user->username = isset($inputs['username']) ? $inputs['username'] : '';
        $user->email = $inputs['email'];
        $user->image = isset($inputs['image']) ? $inputs['image'] : $user->image;
        $user->school_name = isset($inputs['school_name']) ? $inputs['school_name'] : '';
        $user->school_type = isset($inputs['school_type']) ? $inputs['school_type'] : '';
        $user->school_type_other = isset($inputs['school_type_other']) && $inputs['school_type'] == OTHER_VALUE ? $inputs['school_type_other'] : '';
        $user->telephone_no = isset($inputs['telephone_no']) ? $inputs['telephone_no'] : '';
        $user->whoyous_id = isset($inputs['whoyous_id']) ? $inputs['whoyous_id'] : '0';
        $user->whoyous_other = isset($inputs['whoyous_other']) && $inputs['whoyous_id'] == OTHER_VALUE ? $inputs['whoyous_other'] : '';
        $user->address = isset($inputs['address']) ? $inputs['address'] : '';
        $user->city = isset($inputs['city']) ? $inputs['city'] : '';
        $user->postal_code = isset($inputs['postal_code']) ? $inputs['postal_code'] : '';
        $user->county = isset($inputs['county']) ? $inputs['county'] : '';
        $user->country = isset($inputs['country']) ? $inputs['country'] : '';
        $user->user_type = isset($inputs['user_type']) ? $inputs['user_type'] : '';
        $user->status = isset($inputs['status']) ? $inputs['status'] : ACTIVE;
        $user->subscription_status = isset($inputs['subscription_status']) ? $inputs['subscription_status'] : UNSUBSCRIBED;
        $user->gender = isset($inputs['gender']) ? $inputs['gender'] : '';
        $user->first_name = isset($inputs['first_name']) ? $inputs['first_name'] : '';
        $user->last_name = isset($inputs['last_name']) ? $inputs['last_name'] : '';
        $user->howfinds_id = isset($inputs['howfinds_id']) ? $inputs['howfinds_id'] : '0';
        $user->howfinds_other = isset($inputs['howfinds_other']) && $inputs['howfinds_id'] == OTHER_VALUE ? $inputs['howfinds_other'] : '';
        $user->date_of_birth = isset($inputs['date_of_birth']) ? inputDateFormat($inputs['date_of_birth']) : '';
        $user->classlevels_id = isset($inputs['classlevels_id']) ? $inputs['classlevels_id'] : '0';
        $user->tutor_id = isset($inputs['sess_tutor_id']) ? $inputs['sess_tutor_id'] : '0';
        $user->school_id = isset($inputs['school']) ? $inputs['school'] : '0';
        $user->teacher_id = isset($inputs['teacher_id']) ? $inputs['teacher_id'] : '0';
        $user->ethnicity = isset($inputs['ethnicity']) ? $inputs['ethnicity'] : '0';
        $user->sen_provision = isset($inputs['sen_provision']) ? $inputs['sen_provision'] : '0';
        $user->sen_provision_desc = isset($inputs['sen_provision_desc']) ? $inputs['sen_provision_desc'] : '0';
        $user->sats_exempt = isset($inputs['sats_exempt']) ? $inputs['sats_exempt'] : '0';
        $user->ks1_average_point_score = isset($inputs['ks1_average_point_score']) ? $inputs['ks1_average_point_score'] : '0';
        $user->ks1_average_point_score_value = isset($inputs['teacher_id']) ? $inputs['ks1_average_point_score_value'] : '0';
        $user->ks1_maths_baseline = isset($inputs['ks1_maths_baseline']) ? $inputs['ks1_maths_baseline'] : '0';
        $user->ks1_maths_baseline_value = isset($inputs['ks1_maths_baseline_value']) ? $inputs['ks1_maths_baseline_value'] : '0';
        $user->ks1_english_baseline = isset($inputs['ks1_english_baseline']) ? $inputs['ks1_english_baseline'] : '0';
        $user->ks1_english_baseline_value = isset($inputs['ks1_english_baseline_value']) ? $inputs['ks1_english_baseline_value'] : '0';
        $user->ks2_maths_baseline = isset($inputs['ks2_maths_baseline']) ? $inputs['ks2_maths_baseline'] : '0';
        $user->ks2_maths_baseline_value = isset($inputs['ks2_maths_baseline_value']) ? $inputs['ks2_maths_baseline_value'] : '0';
        $user->ks2_english_baseline = isset($inputs['ks2_english_baseline']) ? $inputs['ks2_english_baseline'] : '0';
        $user->ks2_english_baseline_value = isset($inputs['ks2_english_baseline_value']) ? $inputs['ks2_english_baseline_value'] : '0';
        $user->maths_target = isset($inputs['maths_target']) ? $inputs['maths_target'] : '0';
        $user->maths_target_value = isset($inputs['maths_target_value']) ? $inputs['maths_target_value'] : '0';
        $user->english_target = isset($inputs['english_target']) ? $inputs['english_target'] : '0';
        $user->english_target_value = isset($inputs['english_target_value']) ? $inputs['english_target_value'] : '0';
        $user->UPN = isset($inputs['UPN']) ? $inputs['UPN'] : '0';
        $user->date_of_entry = isset($inputs['date_of_entry']) ? inputDateFormat($inputs['date_of_entry']) : '';
        $user->fsm_eligibility = isset($inputs['fsm_eligibility']) ? $inputs['fsm_eligibility'] : '0';
        $user->eal = isset($inputs['eal']) ? $inputs['eal'] : '0';
        $user->term_of_birth = isset($inputs['term_of_birth']) ? $inputs['term_of_birth'] : '0';
        //$user->teacher_id = isset($inputs['teacher_id']) ? $inputs['teacher_id'] : '0';

        $user->howfinds_id = isset($inputs['howfinds_id']) ? $inputs['howfinds_id'] : '0';
        $user->howfinds_other = isset($inputs['howfinds_other']) ? $inputs['howfinds_other'] : '0';
        $user->is_parent = isset($inputs['is_parent']) ? $inputs['is_parent'] : '0';
        $user->do_not_receive_email = isset($inputs['do_not_receive_email']) ? $inputs['do_not_receive_email'] : '0';
        $user->is_traffic_light = isset($inputs['is_traffic_light']) ? $inputs['is_traffic_light'] : '0';
        $user->dfe_number = isset($inputs['dfe_number']) ? $inputs['dfe_number'] : '';
        //asd($user);
        $user->confirmed = '1';
        if (isset($inputs['id'])) {
            $user->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
        } else {
            $user->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $user->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $user->deleted_at = isset($inputs['deleted_at']) ? $inputs['deleted_at'] : '0';
            
        }
        
        
        $lastId = $user->save() ? $user->id : FALSE;
        if ($lastId && $inputs['user_type'] == STUDENT) {
            //assign class and group to student
            $created_at = Carbon::now()->toDateTimeString();
            $groupClassData = array(
                'is_edit' => isset($inputs['id']) ? TRUE : FALSE,
                'student_id' => $lastId,
                'class_id' => isset($inputs['schoolclasses_id']) ? $inputs['schoolclasses_id'] : '',
                'groups' => isset($inputs['groups']) && !empty($inputs['groups']) ? $inputs['groups'] : '',
                'school_id' => $user->school_id,
                'created_by' => $user->updated_by,
                'created_at' => $created_at
            );
            $this->assignGroupAndClassToStudent($groupClassData);
        }
        /* if ($lastId && ($inputs['user_type'] == SCHOOL || $inputs['user_type'] == TUTOR)) {
          $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
          $fees = $feeRecord[0];
          $created_at = Carbon::now()->toDateTimeString();
          $userStudents = array(
          'users_id' => $lastId,
          'no_of_student' => NO_OF_STUDENTS,
          'subscription_start_date' => $created_at,
          'subscription_expiry_date' => Carbon::now()->addMonths(12)->toDateTimeString(),
          );
          $payments = array(
          'user_id' => $lastId,
          'payment_type' => INVOICED,
          'no_of_students' => NO_OF_STUDENTS,
          'status' => SUCCESS,
          'billing_first_name' => isset($inputs['first_name']) ? $inputs['first_name'] : '',
          'billing_last_name' => isset($inputs['last_name']) ? $inputs['last_name'] : '',
          'billing_address' => isset($inputs['address']) ? $inputs['address'] : '',
          'billing_city' => isset($inputs['city']) ? $inputs['city'] : '',
          'billing_county' => isset($inputs['county']) ? $inputs['county'] : '',
          'billing_country' => isset($inputs['country']) ? $inputs['country'] : '',
          'billing_postal_code' => isset($inputs['postal_code']) ? $inputs['postal_code'] : '',
          'amount' => isset($fees['school_sign_up_fee']) ? $fees['school_sign_up_fee'] : '',
          'original_amount' => isset($fees['school_sign_up_fee']) ? $fees['school_sign_up_fee'] : '',
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString(),
          );
          //DB::table('userstudents')->insert($userStudents);
          //DB::table('payments')->insert($payments);
          } */
        return $lastId;
    }

    public function updatePayment($inputs, $lastId) {
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();

        if ($inputs['user_type'] == TUTOR)
            $purchaseNoOfStudent = TUTOR_NO_OF_STUDENTS;
        if ($inputs['user_type'] == SCHOOL)
            $purchaseNoOfStudent = NO_OF_STUDENTS;

        $fees = $feeRecord[0];
        $created_at = Carbon::now()->toDateTimeString();
        $userStudents = array(
            'users_id' => $lastId,
            'no_of_student' => $purchaseNoOfStudent,
            'user_subscription_status' => INACTIVE,
            'payment_status' => INVOICED,
            'upgrade_type' => 2,
                // 'subscription_start_date' => $created_at,
                // 'subscription_expiry_date' => Carbon::now()->addMonths(12)->toDateTimeString(),
        );
        $payments = array(
            'user_id' => $lastId,
            'payment_type' => INVOICED,
            'no_of_students' => $purchaseNoOfStudent,
            'status' => PENDING,
            'billing_first_name' => isset($inputs['first_name']) ? $inputs['first_name'] : '',
            'billing_last_name' => isset($inputs['last_name']) ? $inputs['last_name'] : '',
            'billing_address' => isset($inputs['address']) ? $inputs['address'] : '',
            'billing_city' => isset($inputs['city']) ? $inputs['city'] : '',
            'billing_county' => isset($inputs['county']) ? $inputs['county'] : '',
            'billing_country' => isset($inputs['country']) ? $inputs['country'] : '',
            'billing_postal_code' => isset($inputs['postal_code']) ? $inputs['postal_code'] : '',
            //'amount' => isset($fees['school_sign_up_fee']) ? $fees['school_sign_up_fee'] : '',
            //'original_amount' => isset($fees['school_sign_up_fee']) ? $fees['school_sign_up_fee'] : '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
            'upgrade_type' => 2
        );
        if ($inputs['user_type'] == SCHOOL) {
            $payments['amount'] = isset($fees['school_sign_up_fee']) ? $fees['school_sign_up_fee'] : '0';
            $payments['original_amount'] = isset($fees['school_sign_up_fee']) ? $fees['school_sign_up_fee'] : '0';
        } else if ($inputs['user_type'] == TUTOR) {
            $payments['amount'] = isset($fees['parent_sign_up_fee']) ? $fees['parent_sign_up_fee'] : '0';
            $payments['original_amount'] = isset($fees['parent_sign_up_fee']) ? $fees['parent_sign_up_fee'] : '0';
        }


        //DB::table('user_purchased_students_count')->insert($userStudents);
        DB::table('payments')->insert($payments);
    }

    /**
     * Create a user.
     *
     * @param  array  $inputs
     * @return App\Models\User 
     */
    public function store($inputs) {
        $user = new $this->user;
        $user->password = bcrypt($inputs['password']);
        $lastInsertedId = $this->save($user, $inputs);
        if ($lastInsertedId) {
            //save user count
            $this->saveUserCount($inputs, $lastInsertedId);
        }
        return $lastInsertedId;
    }

    /**
     * Update a user.
     *
     * @param  array  $inputs
     * @param  App\Models\User $user
     * @return void
     */
    public function update($inputs, $id, $userDeleteImage = FALSE) {
        $user = $this->user->where('id', '=', $id)->first();
        //delete old image
        if ($userDeleteImage && !empty($user->image)) {
            $this->userDeleteImage($user->image);
        }
        if (!empty($inputs['password'])) {
            $user->password = bcrypt($inputs['password']);
        }
        return $this->save($user, $inputs);
    }

    /**
     * Get school detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function showSchool($id) {
        $user = $this->user
                        ->join('countries', 'users.country', '=', 'countries.country_code')
                        ->join('counties', 'users.county', '=', 'counties.id','left')
                        ->join('schooltypes', 'users.school_type', '=', 'schooltypes.id', 'left')
                        ->join('whoyous', 'users.whoyous_id', '=', 'whoyous.id', 'left')
                        ->join('howfinds', 'users.howfinds_id', '=', 'howfinds.id', 'left')
                        ->where('users.user_type', '=', SCHOOL)
                        ->where('users.id', '=', $id)
                        ->where('users.status', '!=', DELETED)
                        ->select(['users.id', 'users.username', 'users.email',
                            'users.created_at', 'users.updated_at', 'users.school_name',
                            'users.status', 'users.city', 'users.howfinds_id', 'howfinds.name AS howfinds_name', 'users.howfinds_other', 'users.postal_code',
                            'users.address', 'users.image', 'users.telephone_no',
                            'countries.printable_name AS country', 'users.ks1_maths_baseline', 'users.ks1_maths_baseline_value', 'users.ks2_maths_baseline', 'users.ks2_maths_baseline_value', 'users.ks1_english_baseline',
                            'users.ks1_english_baseline_value', 'users.ks2_english_baseline', 'users.ks2_english_baseline_value', 'counties.name AS county',
                            'schooltypes.school_type', 'users.school_type_other', 'whoyous.name AS who_are_you'
                            , 'users.whoyous_other', 'do_not_receive_email', 'is_traffic_light', 'dfe_number'
                        ])
                        ->get()->first();
        return $user;
    }

    /**
     * Get teacher detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function showTeacher($id) {
        $user = $this->user
                        ->join('countries', 'users.country', '=', 'countries.country_code')
                        ->join('counties', 'users.county', '=', 'counties.id','left')
                        ->where('users.user_type', '=', TEACHER)
                        ->join('users as school', 'school.id', '=', 'users.school_id')
                        ->where('users.id', '=', $id)
                        ->where('users.status', '!=', DELETED)
                        ->select(['users.id', 'users.username', 'users.first_name',
                            'users.last_name', 'users.email', 'users.created_at',
                            'users.updated_at', 'users.school_name', 'users.status',
                            'users.city', 'users.postal_code', 'users.address',
                            'users.gender', 'users.image', 'users.telephone_no',
                            'countries.printable_name AS country', 'counties.name AS county', 'school.school_name as school_name'
                        ])
                        ->get()->first();
        return $user;
    }

    /**
     * Get parent/tutor detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function showTutor($id) {
        $user = $this->user
                        ->join('countries', 'users.country', '=', 'countries.country_code')
                        ->join('counties', 'users.county', '=', 'counties.id','left')
                        ->join('howfinds', 'users.howfinds_id', '=', 'howfinds.id', 'left')
                        ->where('users.user_type', '=', TUTOR)
                        ->where('users.id', '=', $id)
                        ->where('users.status', '!=', DELETED)
                        ->select(['users.id', 'users.username', 'users.first_name',
                            'users.last_name', 'users.email', 'users.created_at', 'users.date_of_birth',
                            'users.updated_at', 'users.school_name', 'users.status',
                            'users.city', 'users.postal_code', 'users.address', 'users.gender',
                            'users.image', 'users.telephone_no', 'howfinds.name AS how_you_find',
                            'users.howfinds_other AS how_find_other',
                            'countries.printable_name AS country', 'counties.name AS county'
                        ])
                        ->get()->first();
        return $user;
    }

    /**
     * Get student detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function showStudent($id) {
        $user = $this->user
                        ->join('countries', 'users.country', '=', 'countries.country_code','left')
                        ->join('counties', 'users.county', '=', 'counties.id','left')
                        ->join('ethnicities', 'users.ethnicity', '=', 'ethnicities.id', 'left')
                        ->where('users.user_type', '=', STUDENT)
                        ->where('users.id', '=', $id)
                        ->where('users.status', '!=', DELETED)
                        ->select(['users.id', 'users.username', 'users.first_name',
                            'users.last_name', 'users.email', 'users.created_at', 'users.date_of_birth',
                            'users.date_of_birth', 'users.ethnicity', 'users.sen_provision', 'users.sen_provision_desc', 'users.sats_exempt', 'users.ks1_average_point_score',
                            'users.ks1_average_point_score_value', 'users.ks1_maths_baseline', 'users.ks1_maths_baseline_value', 'users.ks2_maths_baseline', 'users.ks2_maths_baseline_value', 'users.ks1_english_baseline',
                            'users.ks1_english_baseline_value', 'users.ks2_english_baseline', 'users.ks2_english_baseline_value', 'users.maths_target', 'users.maths_target_value', 'users.english_target',
                            'users.english_target_value', 'users.UPN', 'users.date_of_entry', 'users.fsm_eligibility', 'users.eal', 'users.term_of_birth',
                            'users.updated_at', 'users.school_name', 'users.status', 'ethnicities.id AS ethnicity_id', 'ethnicities.ethnicity_name',
                            'users.city', 'users.postal_code', 'users.address', 'users.gender',
                            'users.image', 'users.telephone_no', 'countries.printable_name AS country', 'counties.name AS county'
                        ])
                        ->get()->first();
        return $user;
    }

    /**
     * Update a user status.
     *
     * @param  array  $inputs
     * @param  App\Models\User $user
     * @return void
     */
    public function destroyUser($inputs, $id) {
        $user = $this->user->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $user->updated_by = $inputs['updated_by'];
        $user->deleted_at = $dateTime;
        $user->status = DELETED; // deleted
        $user->save();
    }

    /**
     * list of users 
     * @param type $params 
     */
    public function getUserList($params = array()) {
        $commonFieldsArray = array('users.id', 'username', 'first_name', 'last_name', 'email', 'users.created_at', 'users.updated_at', 'school_name', 'users.status', 'users.school_type', 'users.county', 'users.howfinds_id', 'users.howfinds_other', 'users.school_id', 'users.subscription_start_date', 'users.subscription_expiry_date', 'users.subscription_expiry_date AS renew_date');
        switch ($params['user_type']) {
            case SCHOOL:
                $extraFieldsArray = array('usercounts.school_teacher_counts as no_of_teachers', 'usercounts.school_student_counts as no_of_students');
                $fieldsArray = array_merge($commonFieldsArray, $extraFieldsArray);
                $query = $this->user->select($fieldsArray)
                        ->join('usercounts', 'users.id', '=', 'usercounts.school_id', 'left')
                        ->where('users.status', '!=', DELETED)
                        ->where("user_type", "=", $params['user_type']);
                if (isset($params['limit']) && !empty($params['limit']))
                    $query->limit($params['limit']);

                if (isset($params['school_id']) && !empty($params['school_id']))
                    $query->where('users.school_id', '=', $params['school_id']);
                return $query;
                break;
            case TEACHER:
                $extraFieldsArray = array('usercounts.teacher_student_counts as no_of_students');
                $fieldsArray = array_merge($commonFieldsArray, $extraFieldsArray);
                $query = $this->user->select($fieldsArray)
                        ->join('usercounts', 'users.id', '=', 'usercounts.teacher_id', 'left')
                        ->where('users.status', '!=', DELETED)
                        ->where("user_type", "=", $params['user_type']);

                if (isset($params['school_id']) && !empty($params['school_id']))
                    $query->where('users.school_id', '=', $params['school_id']);

                if (isset($params['limit']) && !empty($params['limit']))
                    $query->limit($params['limit']);
                return $query;
                break;

            case TUTOR:
                $extraFieldsArray = array('usercounts.tutor_student_counts as no_of_students');
                //$extraFieldsArray = array('usercounts.tutor_student_counts as no_of_students', 'payments.amount');
                $fieldsArray = array_merge($commonFieldsArray, $extraFieldsArray);
                $query = $this->user->select($fieldsArray)
                        ->join('usercounts', 'users.id', '=', 'usercounts.tutor_id', 'left')
                        // ->join('payments', 'users.id', '=', 'payments.user_id', 'left')
                        ->where('users.status', '!=', DELETED)
                        ->where("user_type", "=", $params['user_type']);

                if (isset($params['school_id']) && !empty($params['school_id']))
                    $query->where('users.school_id', '=', $params['school_id']);
                if (isset($params['limit']) && !empty($params['limit']))
                    $query->limit($params['limit']);
                return $query;
                break;
            case STUDENT:
                $extraFieldsArray = array('classlevels.level as class_level');
                $fieldsArray = array_merge($commonFieldsArray, $extraFieldsArray);
                $query = $this->user->select($fieldsArray)
                        ->join('classlevels', 'users.classlevels_id', '=', 'classlevels.id', 'left')
                        ->where('users.status', '!=', DELETED)
                        ->where("user_type", "=", $params['user_type']);
                if (isset($params['school_id']) && !empty($params['school_id']))
                    $query->where('users.school_id', '=', $params['school_id']);
                if (isset($params['teacher_id']) && !empty($params['teacher_id']))
                    $query->where('users.teacher_id', '=', $params['teacher_id']);
                if (isset($params['tutor_id']) && !empty($params['tutor_id']))
                    $query->where('users.tutor_id', '=', $params['tutor_id']);

                if (isset($params['limit']) && !empty($params['limit']))
                    $query->limit($params['limit']);
                return $query;
                break;
        }
    }

    /**
     * list of users 
     * @param type $params 
     */
    public function getPaymentUserList($params = array()) {
        $commonFieldsArray = array('usercounts.tutor_student_counts as no_of_students', 'users.howfinds_other', 'users.howfinds_id', 'howfinds.name as howfinds_name', 'users.id', 'username', 'first_name', 'last_name', 'email', 'users.created_at', 'users.updated_at', 'school_name', 'users.status', 'users.school_type', 'users.county', 'users.howfinds_id', 'users.howfinds_other', 'users.school_id', 'users.subscription_start_date', 'users.subscription_expiry_date');
        switch ($params['user_type']) {
            case TUTOR:
                $extraFieldsArray = array(DB::raw('sum(payments.amount) as total'), 'payments.amount');
                $fieldsArray = array_merge($commonFieldsArray, $extraFieldsArray);
                $query = $this->user->select($fieldsArray)
                        ->leftJoin('payments', function($join) {
                            $join->on('users.id', '=', 'payments.user_id');
                            $join->where('payments.status', '=', SUCCESS);
                            $join->where('payments.upgrade_type', '=', '1');
                        })
                        ->join('usercounts', 'users.id', '=', 'usercounts.tutor_id', 'left')
                        ->join('howfinds', 'users.howfinds_id', '=', 'howfinds.id', 'left')
                        ->where("user_type", "=", $params['user_type'])
                        ->where('users.status', '!=', DELETED)
                        ->groupBy('users.id');
                return $query;
                break;
        }
    }

    /**
     * list of Graph details 
     * @param type $params 
     */
    public function getGraphData($param = array()) {


        $query = DB::select('SELECT DATE_FORMAT(payments.created_at, "%b") AS month, SUM(payments.amount) AS total ,DATE_FORMAT(payments.created_at, "%m-%Y") AS md
            FROM payments 
            INNER JOIN users ON users.id=payments.user_id	
            WHERE payments.status="' . SUCCESS . '" AND payments.created_at <= NOW() AND payments.created_at >= DATE_ADD(NOW(),INTERVAL - 12 MONTH) AND users.status!="' . DELETED . '" and users.user_type="' . $param['userType'] . '"
            GROUP BY md   
            ORDER BY YEAR(payments.created_at) ASC, MONTH(payments.created_at) ASC ');

        return $query;
    }

    /**
     * list of Graph details 
     * @param type $params 
     */
    public function getGraphDataDashboard($param = array()) {
        $query = DB::select('SELECT DATE_FORMAT(created_at, "%b") AS month, COUNT(id) AS total ,DATE_FORMAT(created_at, "%m-%Y") AS md
            FROM users 
            WHERE STATUS !="' . DELETED . '" AND user_type="' . $param['userType'] . '"
            AND created_at <= NOW() AND created_at >= DATE_ADD(NOW(),INTERVAL - 12 MONTH)    
            GROUP BY md
            ORDER BY YEAR(created_at) ASC, MONTH(created_at) ASC');

        return $query;
    }

    /**
     * list of Graph details 
     * @param type $params 
     */
    public function getGraphDataYearly($param = array()) {
        $query = DB::select('SELECT YEAR(payments.created_at) AS YEAR ,SUM(payments.amount) AS total
                FROM payments 
                INNER JOIN users ON users.id=payments.user_id
                WHERE payments.status="' . SUCCESS . '" AND  payments.created_at <= NOW() AND payments.created_at >= DATE_ADD(NOW(),INTERVAL - 1 YEAR)
                AND users.status != "' . DELETED . '" AND  users.user_type="' . $param['userType'] . '"
                GROUP BY YEAR(created_at) ');
        return $query;
    }

    /**
     * list of users
     * @param type $params 
     */
    public function getListUser($params = array()) {
        $query = $this->user
                ->select(['users.id', 'username', 'first_name', 'last_name', 'email', 'users.created_at', 'users.updated_at', 'school_name', 'users.status', 'users.school_type', 'users.county', 'users.howfinds_id', 'users.howfinds_other', 'users.school_id', 'users.subscription_start_date', 'users.subscription_expiry_date',
                    'users.user_type', 'payments.amount', 'payments.upgrade_type', 'payments.no_of_students', 'payments.id AS payments_id', 'payments.voucher_code', 'payments.created_at as payment_created_at'])
                ->join('payments', 'users.id', '=', 'payments.user_id', 'left')
                ->where('payments.status', '=', 'Success')
                ->where('users.status', '!=', DELETED);
        if (isset($params['id']) && !empty($params['id'])) {
            $query->where('payments.id', '=', $params['id']);
        } else {
            $query->where("users.user_type", "=", $params['user_type']);
        }
        return $query;
    }

    /**
     * list of users
     * @param type $params 
     */
    public function getUser($params = array()) {
        $defaultSelect = ['users.id', 'username', 'first_name', 'last_name', 'email', 'school_name', 'user_type', 'remaining_no_of_student','ks2_maths_baseline_value','ks2_english_baseline_value'];
        $select = isset($params['select']) ? $params['select'] : $defaultSelect;
        $query = $this->user
                ->select($select);
        $query->where('users.status', '!=', DELETED);

        if (isset($params['id']) && !empty($params['id']))
            $query->where('users.id', '=', $params['id']);
        if (isset($params['ids']) && !empty($params['ids']))
            $query->whereIn('users.id', $params['ids']);
        if (isset($params['user_type']) && !empty($params['user_type']))
            $query->where('users.user_type', '=', $params['user_type']);
        if (isset($params['school_id']) && !empty($params['school_id']))
            $query->where('users.school_id', '=', $params['school_id']);
        if (isset($params['status']) && !empty($params['status']))
            $query->where('users.status', '=', $params['status']);
        if (isset($params['email']) && !empty($params['email']))
            $query->where('users.email', '=', $params['email']);
        if (isset($params['username']) && !empty($params['username']))
            $query->where('users.username', '=', $params['username']);
        return $query;
    }

    /**
     * list of users
     * @param type $params 
     */
    public function getFrontUser($params = array()) {
        $defaultSelect = ['users.id', 'username', 'first_name', 'last_name', 'email', 'school_name', 'user_type', 'remaining_no_of_student'];
        $select = isset($params['select']) ? $params['select'] : $defaultSelect;
        $query = $this->user
                ->select($select);
        if (isset($params['id']) && !empty($params['id']))
            $query->where('users.id', '=', $params['id']);
        if (isset($params['user_type']) && !empty($params['user_type']))
            $query->where('users.user_type', '=', $params['user_type']);
        if (isset($params['school_id']) && !empty($params['school_id']))
            $query->where('users.school_id', '=', $params['school_id']);
        if (isset($params['status']) && !empty($params['status']))
            $query->where('users.status', '=', $params['status']);
        if (isset($params['email']) && !empty($params['email']))
            $query->where('users.email', '=', $params['email']);
        if (isset($params['username']) && !empty($params['username']))
            $query->where('users.username', '=', $params['username']);
        return $query;
    }    
    
    
    /**
     * list of users
     * @param type $params 
     */
    public function getUser2($params = array()) {
        $query = $this->user
                ->select(['users.id', 'users.username', 'users.first_name', 'users.last_name', 'users.email', 'users.school_name', 'users.user_type','remaining_no_of_student'])
                ->join('payments', 'payments.user_id', '=', 'users.id', 'left')
                ->where('users.id', '=', $params['id']);
        $query->where('users.status', '!=', DELETED);
        return $query;
    }

    public function getDuplicateUser($params = array()) {
        $query = $this->user
                ->select(['users.id']);
        $query->where('users.status', '!=', DELETED);
        $query->where(function ($query) use ($params) {
            $query->where('users.username', '=', $params['username'])
                    ->orwhere('users.email', '=', $params['email']);
        });

        return $query;
    }

    public function getDuplicateUsername($params = array()) {
        $query = $this->user
                ->select(['users.id']);
        $query->where('users.status', '!=', DELETED);
        $query->where('users.username', '=', $params['username']);

        return $query;
    }

    /**
     * upload user image
     * @object type $image 
     */
    function userImageUpload($image) {
        $fileName = str_random(10) . '_' . time() . '.' . $image->getClientOriginalExtension(); // renameing image
        $pathOriginal = public_path('uploads/user/' . $fileName);
        $pathLarge = public_path('uploads/user/large/' . $fileName);
        $pathMedium = public_path('uploads/user/medium/' . $fileName);
        $pathSmall = public_path('uploads/user/small/' . $fileName);
        Image::make($image->getRealPath())->save($pathOriginal);
        Image::make($image->getRealPath())->resize(UIMG_L_W, UIMG_L_H)->save($pathLarge);
        Image::make($image->getRealPath())->resize(UIMG_M_W, UIMG_M_H)->save($pathMedium);
        Image::make($image->getRealPath())->resize(UIMG_S_W, UIMG_S_H)->save($pathSmall);
        return $fileName;
    }

    /**
     * delete user image
     * @string type $image 
     */
    function userDeleteImage($fileName) {
        $pathOriginal = public_path('uploads/user/' . $fileName);
        $pathLarge = public_path('uploads/user/large/' . $fileName);
        $pathMedium = public_path('uploads/user/medium/' . $fileName);
        $pathSmall = public_path('uploads/user/small/' . $fileName);
        if (file_exists($pathOriginal)) {
            unlink($pathOriginal);
        }
        if (file_exists($pathLarge)) {
            unlink($pathLarge);
        }
        if (file_exists($pathMedium)) {
            unlink($pathMedium);
        }
        if (file_exists($pathSmall)) {
            unlink($pathSmall);
        }
    }

    /**
     * update school profile
     * @string type $image 
     */
    function updateSchoolProfile($inputs, $id, $userDeleteImage = FALSE) {
        $user = $this->user->where('id', '=', $id)->first();
        if ($userDeleteImage && !empty($user->image)) {
            $this->userDeleteImage($user->image);
        }
        $user->username = $inputs['username'];
        $user->email = $inputs['email'];
        $user->image = isset($inputs['image']) ? $inputs['image'] : $user->image;
        $user->school_name = $inputs['school_name'];
        $user->school_type = isset($inputs['school_type']) ? $inputs['school_type'] : '';
        $user->school_type_other = isset($inputs['school_type_other']) && $inputs['school_type'] == OTHER_VALUE ? $inputs['school_type_other'] : '';
        $user->telephone_no = $inputs['telephone_no'];
        $user->whoyous_id = isset($inputs['whoyous_id']) ? $inputs['whoyous_id'] : '0';
        $user->whoyous_other = isset($inputs['whoyous_other']) && $inputs['whoyous_id'] == OTHER_VALUE ? $inputs['whoyous_other'] : '';
        $user->howfinds_id = isset($inputs['howfinds_id']) ? $inputs['howfinds_id'] : '0';
        $user->howfinds_other = isset($inputs['howfinds_other']) && $inputs['howfinds_id'] == OTHER_VALUE ? $inputs['howfinds_other'] : '';
        $user->address = $inputs['address'];
        $user->city = $inputs['city'];
        $user->postal_code = $inputs['postal_code'];
        $user->county = $inputs['county'];
        $user->ks1_maths_baseline = isset($inputs['ks1_maths_baseline']) ? $inputs['ks1_maths_baseline'] : '0';
        $user->ks1_maths_baseline_value = isset($inputs['ks1_maths_baseline_value']) ? $inputs['ks1_maths_baseline_value'] : '0';
        $user->ks1_english_baseline = isset($inputs['ks1_english_baseline']) ? $inputs['ks1_english_baseline'] : '0';
        $user->ks1_english_baseline_value = isset($inputs['ks1_english_baseline_value']) ? $inputs['ks1_english_baseline_value'] : '0';
        $user->ks2_maths_baseline = isset($inputs['ks2_maths_baseline']) ? $inputs['ks2_maths_baseline'] : '0';
        $user->ks2_maths_baseline_value = isset($inputs['ks2_maths_baseline_value']) ? $inputs['ks2_maths_baseline_value'] : '0';
        $user->ks2_english_baseline = isset($inputs['ks2_english_baseline']) ? $inputs['ks2_english_baseline'] : '0';
        $user->ks2_english_baseline_value = isset($inputs['ks2_english_baseline_value']) ? $inputs['ks2_english_baseline_value'] : '0';
        $user->country = $inputs['country'];
        $user->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '';
        $user->do_not_receive_email = isset($inputs['do_not_receive_email']) ? $inputs['do_not_receive_email'] : '0';
        $user->is_traffic_light = isset($inputs['is_traffic_light']) ? $inputs['is_traffic_light'] : '0';
        $user->dfe_number = isset($inputs['dfe_number']) ? $inputs['dfe_number'] : '';
        return $user->save();
    }

    /**
     * update school profile
     * @string type $image 
     */
    function updateTeacherProfile($inputs, $id, $userDeleteImage = FALSE) {
        $user = $this->user->where('id', '=', $id)->first();
        if ($userDeleteImage && !empty($user->image)) {
            $this->userDeleteImage($user->image);
        }
        $user->username = $inputs['username'];
        $user->email = $inputs['email'];
        $user->image = isset($inputs['image']) ? $inputs['image'] : $user->image;
        $user->telephone_no = $inputs['telephone_no'];
        $user->address = $inputs['address'];
        $user->city = $inputs['city'];
        $user->postal_code = $inputs['postal_code'];
        $user->county = $inputs['county'];
        $user->country = $inputs['country'];
        $user->gender = isset($inputs['gender']) ? $inputs['gender'] : '';
        $user->first_name = isset($inputs['first_name']) ? $inputs['first_name'] : '';
        $user->last_name = isset($inputs['last_name']) ? $inputs['last_name'] : '';
        $user->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '';
        return $user->save();
    }

    /**
     * update school profile
     * @string type $image 
     */
    function updateTutorProfile($inputs, $id, $userDeleteImage = FALSE) {
        $user = $this->user->where('id', '=', $id)->first();
        if ($userDeleteImage && !empty($user->image)) {
            $this->userDeleteImage($user->image);
        }
        $user->username = $inputs['username'];
        $user->email = $inputs['email'];
        $user->image = isset($inputs['image']) ? $inputs['image'] : $user->image;
        $user->telephone_no = $inputs['telephone_no'];
        $user->address = $inputs['address'];
        $user->city = $inputs['city'];
        $user->postal_code = $inputs['postal_code'];
        $user->county = $inputs['county'];
        $user->country = $inputs['country'];
        $user->gender = isset($inputs['gender']) ? $inputs['gender'] : '';
        $user->first_name = isset($inputs['first_name']) ? $inputs['first_name'] : '';
        $user->last_name = isset($inputs['last_name']) ? $inputs['last_name'] : '';
        $user->howfinds_id = isset($inputs['howfinds_id']) ? $inputs['howfinds_id'] : '0';
        $user->howfinds_other = isset($inputs['howfinds_other']) && $inputs['howfinds_id'] == OTHER_VALUE ? $inputs['howfinds_other'] : '';
        $user->date_of_birth = isset($inputs['date_of_birth']) ? inputDateFormat($inputs['date_of_birth']) : '';
        $user->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '';
        return $user->save();
    }

    /**
     * update school profile
     * @string type $image 
     */
    function updateAdmin($inputs, $id, $userDeleteImage = FALSE) {
        $user = $this->user->where('id', '=', $id)->first();
        if ($userDeleteImage && !empty($user->image)) {
            $this->userDeleteImage($user->image);
        }
        $user->username = $inputs['username'];
        $user->email = $inputs['email'];
        $user->image = isset($inputs['image']) ? $inputs['image'] : $user->image;
        $user->first_name = isset($inputs['first_name']) ? $inputs['first_name'] : '';
        $user->last_name = isset($inputs['last_name']) ? $inputs['last_name'] : '';
        $user->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '';
        $user->save();
    }

    /**
     * update user password
     * @string type $image 
     */
    function getUpdatePasswordRedirect($userType) {
        $redirect = '';
        switch ($userType):
            case ADMIN:
                $redirect = 'myprofile';
                break;
            case SCHOOL:
                $redirect = 'manageaccount';
                break;
            case TEACHER:
                $redirect = 'manageprofile';
                break;
            case TUTOR:
                $redirect = 'myaccount';
                break;
            case STUDENT:
                break;
        endswitch;
        return $redirect;
    }

    /**
     * update user password
     * @string type $image 
     */
    function updatePassword($user, $inputs) {
        $user->password = $inputs['password'];
        $user->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '';
        $user->save();
    }

    /**
     * Update a user.
     *
     * @param  array  $inputs
     * @param  App\Models\User $user
     * @return void
     */
    public function frontUserUpdate($inputs, $id) {
        $user = $this->user->where('id', '=', $id)->first();
        if (!empty($inputs['password'])) {
            $user->password = bcrypt($inputs['password']);
        }
        $this->save($user, $inputs);
    }

    public function userSubscriptionUpdate($input) {
        $model = $this->user->findOrFail($input['userId']);
        if (isset($input['status'])) {
            $model->status = $input['status'];
        }
        if (isset($input['subscription_status'])) {
            $model->subscription_status = $input['subscription_status'];
        }
        if (isset($input['subscription_start_date'])) {
            $model->subscription_start_date = $input['subscription_start_date'];
        }
        if (isset($input['subscription_expiry_date'])) {
            $model->subscription_expiry_date = $input['subscription_expiry_date'];
        }
        if (isset($input['deleted_at'])) {
            $model->deleted_at = $input['deleted_at'];
        }
        if (isset($input['no_of_student'])) {
            $model->remaining_no_of_student = $model->remaining_no_of_student + $input['no_of_student'];
            $model->total_number_of_student = $model->total_number_of_student + $input['no_of_student'];
        }
        $model->save();
    }

    /**
     * Insert a new the user
     * @author     Icreon Tech - dev2.
     * @param type array $inputs
     * @return void
     */
    public function frontStore($inputs) {
        $user = new $this->user;
        $user->password = bcrypt($inputs['password']);
        $lastInsertedId = $this->save($user, $inputs);
        //save user count
        $this->saveUserCount($inputs, $lastInsertedId);
        //save billing address
        $this->saveBillingAddress($inputs, $lastInsertedId);
        return $lastInsertedId;
    }

    /**
     * Insert user entery on user count
     * @author     Icreon Tech - dev2.
     * @param type array $input
     * @param type int $user_id
     */
    public function saveUserCount($inputs, $user_id) {
        $usercount = new $this->usercount;
        if ($inputs['user_type'] == SCHOOL) { /* insert into usercounts table for new school */
            $usercount->school_id = $user_id;
        } else if ($inputs['user_type'] == TEACHER) { /* insert into usercounts table for new teacher */
            $usercount->teacher_id = $user_id;
        } else if ($inputs['user_type'] == TUTOR) { /* insert into usercounts table for new parent/tutor */
            $usercount->tutor_id = $user_id;
        }
        $usercount->save();
    }

    /**
     * Insert billing address
     * @author     Icreon Tech - dev2.
     * @param type array $input
     * @param type int $user_id
     */
    public function saveBillingAddress($inputs, $user_id) {
        $billingAddress = new BillingAddress();
        $billingAddress->users_id = $user_id;
        $billingAddress->first_name = isset($inputs['billing_first_name']) ? $inputs['billing_first_name'] : '';
        $billingAddress->last_name = isset($inputs['billing_last_name']) ? $inputs['billing_last_name'] : '';
        $billingAddress->address = isset($inputs['billing_address']) ? $inputs['billing_address'] : '';
        $billingAddress->city = isset($inputs['billing_city']) ? $inputs['billing_city'] : '';
        $billingAddress->postal_code = isset($inputs['billing_postal_code']) ? $inputs['billing_postal_code'] : '';
        $billingAddress->county = isset($inputs['billing_county']) ? $inputs['billing_county'] : '';
        $billingAddress->country = isset($inputs['billing_country']) ? $inputs['billing_country'] : '';
        $billingAddress->save();
    }

    /**
     * return school signup from data (basic infp and card detail in array)
     * @author     Icreon Tech - dev2.
     * @param type $inputs
     * @return type array $inputs
     */
    public function getSchoolformData($inputs) {
        $billingFields = array('billing_first_name', 'billing_last_name', 'billing_address', 'billing_city', 'billing_postal_code', 'billing_county', 'billing_country');
        //$cardFields = array('card_no', 'card_type', 'expiry_month', 'expiry_year', 'cvv_no');
        foreach ($billingFields as $billingField) {
            $inputData['payment_info'][$billingField] = $inputs[$billingField];
        }
        /*
          foreach ($cardFields as $field) {
          $inputData['payment_info'][$field] = $inputs[$field];
          unset($inputs[$field]);
          } */
        $inputData['basic_info'] = $inputs;
        return $inputData;
    }

    /**
     * return tutor signup from data (basic infp and card detail in array)
     * @author     Icreon Tech - dev1.
     * @param type $inputs
     * @return type array $inputs
     */
    public function getTutorformData($inputs) {
        $billingFields = array('billing_first_name', 'billing_last_name', 'billing_address', 'billing_city', 'billing_postal_code', 'billing_county', 'billing_country');
        //$cardFields = array('card_no', 'card_type', 'expiry_month', 'expiry_year', 'cvv_no');
        foreach ($billingFields as $billingField) {
            $inputData['payment_info'][$billingField] = $inputs[$billingField];
        }
        /* foreach ($cardFields as $field) {
          $inputData['payment_info'][$field] = $inputs[$field];
          unset($inputs[$field]);
          } */
        $inputData['basic_info'] = $inputs;
        return $inputData;
    }

    /**
     * return a school's students
     * @author     Icreon Tech - dev2.
     * @param type $params
     * @return type array 
     */
    public function getSchoolStudents($params) {
        return $this->user->select(['id', 'first_name', 'last_name'])
                        ->where('status', '!=', DELETED)
                        ->where(['school_id' => $params['school_id'], 'user_type' => STUDENT])
                        ->orderBy('first_name')
                        ->get();
    }

    /**
     * return a school's student list
     * @author     Icreon Tech - dev2.
     * @param type $params
     * @return type array 
     */
    public function schoolStudentsList($params) {
        $students = $this->getSchoolStudents($params);
        $studentsData = array();
        if (count($students)) {
            foreach ($students as $student) {
                $studentsData[$student->id] = $student->first_name . ' ' . $student->last_name;
            }
        }
        return $studentsData;
    }

    /**
     * assign group and class to student
     * @author     Icreon Tech - dev2.
     * @param type $data
     */
    protected function assignGroupAndClassToStudent($data) {
        if ($data['is_edit']) {
            DB::table('groupstudents')->where('student_id', '=', $data['student_id'])->delete();
            DB::table('classstudents')->where('student_id', '=', $data['student_id'])->delete();
            unset($data['is_edit']);
        }
        //assign class to student
        if (!empty($data['class_id'])) {
            $classArr = $data;
            unset($classArr['groups']);
            $this->assignClassTostudent($classArr);
        }
        //assign student group
        if (!empty($data['groups']) && is_array($data['groups'])) {
            $groupArr = $data;
            unset($groupArr['class_id']);
            $this->assignGroupTostudent($groupArr);
        }
    }

    /**
     * assign group to student
     * @author     Icreon Tech - dev2.
     * @param type $data
     */
    protected function assignGroupTostudent($data) {
        foreach ($data['groups'] as $value) {
            $groupArr = array(
                'student_id' => $data['student_id'],
                'group_id' => $value,
                'created_at' => $data['created_at'],
                'school_id' => $data['school_id'],
                'created_by' => $data['created_by']
            );
            $dataParam[] = $groupArr;
        }
        DB::table('groupstudents')->insert($dataParam);
    }

    /**
     * assign class to student
     * @author     Icreon Tech - dev2.
     * @param type $data
     */
    protected function assignClassTostudent($data) {
        $dataParam = array(
            'student_id' => $data['student_id'],
            'class_id' => $data['class_id'],
            'created_at' => $data['created_at'],
            'school_id' => $data['school_id'],
            'created_by' => $data['created_by']
        );
        DB::table('classstudents')->insert($dataParam);
    }

    /**
     * get user
     * @param type $id, integer value 
     */
    public function getSubscribedUser($id) {
        $query = $this->user->select(['users.id', 'username', 'email', 'user_type', 'school_id', 'address', 'city', 'postal_code', 'county', 'country', 'image', 'telephone_no', 'school_name', 'first_name',
                    'last_name', 'gender', 'date_of_birth', 'howfinds_id', 'howfinds_other', 'payment.no_of_student', 'whoyous_id', 'school_type', 'school_type_other', 'whoyous_other'])
                ->join('payment_temp as payment', 'payment.user_id', '=', 'users.id', 'left');
        return $query->where("users.id", "=", $id)->get()->first();
    }

    /**
     * 
     * $params type array
     */
    public function deleteUserTemporaryInformation($params = array()) {
        DB::table('payment_temp')->where('users_id', '=', $params['user_id'])->delete();
        $dataParam = array(
            'users_id' => $params['user_id'],
            'no_of_student' => $params['no_of_student']
        );
        DB::table('payment_temp')->insert($dataParam);
    }

    /**
     * 
     * $params type array
     */
    public function updateUserStudentsCounts($params = array()) {
        /* $dataParam = array(
          'users_id' => $params['user_id'],
          'no_of_student' => $params['no_of_student'],
          'subscription_start_date' => $params['subscription_start_date'],
          'subscription_expiry_date' => $params['subscription_expiry_date'],
          );
          DB::table('userstudents')->insert($dataParam);
         */


        $userDataParam = array(
            'users_id' => $params['user_id'],
            'no_of_student' => $params['no_of_student'],
            'upgrade_type' => 2,
        );
        DB::table('user_purchased_students_count')->insert($userDataParam);
    }

    /**
     * this is used to get the total Revenue
     * @author     Icreon Tech - dev5.
     * @param type $data
     */
    public function showRevenueTotal($params = array()) {
        if ($params == '') {
            $query = $this->payment->select(DB::raw('sum(payments.amount) as total'))
                    ->where('payments.status', '=', SUCCESS)
                    ->get()
                    ->toArray();
        } else if ($params == SCHOOL) {
            $query = $this->payment->select(DB::raw('sum(payments.amount) as total'))
                    ->join('users', 'users.id', '=', 'payments.user_id', 'left')
                    ->where('users.user_type', '=', SCHOOL)
                    ->where('payments.status', '=', SUCCESS)
                    ->get()
                    ->toArray();
        } else {
            $query = $this->payment->select(DB::raw('sum(payments.amount) as total'))
                    ->join('users', 'users.id', '=', 'payments.user_id', 'left')
                    ->where('users.user_type', '=', TUTOR)
                    ->where('payments.status', '=', SUCCESS)
                    ->get()
                    ->toArray();
        }
        return $query;
    }

    /**
     * this is used to get the additional purchases
     * @author     Icreon Tech - dev5.
     * @param type $data
     */
    public function showAdditional_purchases($params = array()) {
        $query = $this->user->select(DB::raw('sum(payments.amount) as total'), 'users.id')
                ->join('payments', 'users.id', '=', 'payments.user_id', 'left')
                ->where('payments.status', '=', SUCCESS)
                ->where('payments.upgrade_type', '=', '1')
                ->groupBy('users.id');

//        SELECT users.id, SUM(payments.amount) FROM users
//        JOIN `payments` ON users.id = payments.user_id
//        WHERE payments.status = 'Success'
//        AND payments.upgrade_type = '1'
//        GROUP BY users.id
        return $query;
    }

    /**
     * this is used to get the total no of user for different user type
     * @author     Icreon Tech - dev1.
     * @param type $data
     */
    public function showUserTypeTotal($params = array()) {
        $query = $this->user->select(DB::raw('COUNT(user_type) AS total_count'), 'user_type');

        if (isset($params['user_type']) && !empty($params['user_type']))
            $query->where('user_type', '=', $params['user_type']);

        //if (isset($params['status']) && !empty($params['status']))
        $query->where('status', '!=', DELETED);

        if (isset($params['active_tutors']) && !empty($params['active_tutors'])) {
            $query->where('user_type', '=', $params['active_tutors']);
        }
        if (isset($params['school_id']) && !empty($params['school_id']))
            $query->where('school_id', '=', $params['school_id']);
        if (isset($params['teacher_id']) && !empty($params['teacher_id']))
            $query->where('teacher_id', '=', $params['teacher_id']);
        if (isset($params['tutor_id']) && !empty($params['tutor_id']))
            $query->where('tutor_id', '=', $params['tutor_id']);


        $query->groupBy('user_type');
        return $query->get()->toArray();
    }

    public function showLastRegistration($params = array()) {
        $data = DB::table('users')
                        ->select(DB::raw('count(id) as userCnt'))
                        ->whereRaw(DB::raw('created_at >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, "%Y/%m/01")'))
                        ->whereRaw(DB::raw('created_at < DATE_FORMAT(CURRENT_DATE, "%Y/%m/01")'))
                        ->where('status','!=',DELETED)    
                        ->where('user_type','=',$params['type'])    
                        ->get();
        return $data;
    }

    /**
     * this is used to get the total no of user for different user type
     * @author     Icreon Tech - dev5.
     * @param type $data
     */
    public function showMemberCount($params = array()) {
        if (isset($params['tutor']) && !empty($params['tutor'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as activeTutors'))
                            ->whereRaw(DB::raw('status = "Active"'))
                            ->whereRaw(DB::raw('user_type =  "4"'))->get();
        } else if (isset($params['school']) && !empty($params['school'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as activeSchools'))
                            ->whereRaw(DB::raw('status = "Active"'))
                            ->whereRaw(DB::raw('user_type =  "2"'))->get();
        } else if (isset($params['school_inactive']) && !empty($params['school_inactive'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as inactiveSchools'))
                            ->whereRaw(DB::raw('status = "Inactive"'))
                            ->whereRaw(DB::raw('user_type =  "2"'))->get();
        } else if (isset($params['tutor_inactive']) && !empty($params['tutor_inactive'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as inactiveTutors'))
                            ->whereRaw(DB::raw('status = "Inactive"'))
                            ->whereRaw(DB::raw('user_type =  "4"'))->get();
        }
        return $data;
    }

    /**
     * this is used to get the total no Registrations
     * @author     Icreon Tech - dev5.
     * @param type $data
     */
    public function showMonthlyRegistration($params = array()) {
        if (isset($params['tutor']) && !empty($params['tutor'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as lastMonthActiveTutors'))
                            ->whereRaw(DB::raw('user_type =  "4"'))
                          //  ->whereRaw(DB::raw('status = "Active"'))
                            ->where('status','!=',DELETED)
                            ->whereRaw(DB::raw('created_at >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, "%Y/%m/01")'))
                            ->whereRaw(DB::raw('created_at < DATE_FORMAT(CURRENT_DATE, "%Y/%m/01")'))->get();
        } else if (isset($params['school']) && !empty($params['school'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as lastMonthActiveSchools'))
                            ->whereRaw(DB::raw('user_type =  "2"'))
                            //->whereRaw(DB::raw('status = "Active"'))
                            ->where('status','!=',DELETED)
                            ->whereRaw(DB::raw('created_at >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, "%Y/%m/01")'))
                            ->whereRaw(DB::raw('created_at < DATE_FORMAT(CURRENT_DATE, "%Y/%m/01")'))->get();
        } else if (isset($params['school_inactive']) && !empty($params['school_inactive'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as lastMonthInactiveSchools'))
                            ->whereRaw(DB::raw('user_type =  "2"'))
                            ->whereRaw(DB::raw('status = "Inactive"'))
                            ->whereRaw(DB::raw('created_at >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, "%Y/%m/01")'))
                            ->whereRaw(DB::raw('created_at < DATE_FORMAT(CURRENT_DATE, "%Y/%m/01")'))->get();
        } else if (isset($params['tutor_inactive']) && !empty($params['tutor_inactive'])) {
            $data = DB::table('users')
                            ->select(DB::raw('count(id) as lastMonthInactiveTutors'))
                            ->whereRaw(DB::raw('user_type =  "4"'))
                            ->whereRaw(DB::raw('status = "Inactive"'))
                            ->whereRaw(DB::raw('created_at >= DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, "%Y/%m/01")'))
                            ->whereRaw(DB::raw('created_at < DATE_FORMAT(CURRENT_DATE, "%Y/%m/01")'))->get();
        }

        return $data;
    }

    /**
     * this is used to get the count of the registred users for different type of school
     * @author     Icreon Tech - dev1.
     * @param type $param
     */
    public function getTypeofSchoolCounts($param = array()) {
        $query = $this->user->select(DB::raw("IF((users.school_type IS NOT NULL OR users.school_type!='0' OR users.school_type!='-1'),COUNT(users.school_type),0) AS user_count, schooltypes.school_type"));
        $query->join('schooltypes as schooltypes', 'schooltypes.id', '=', 'users.school_type', 'right');
        $query->whereRaw(DB::raw("IF((users.school_type IS NOT NULL OR users.school_type!='0' OR users.school_type!='-1'),users.status!='Deleted',1=1)"));
        $query->orderBy('schooltypes.school_type');
        $query->groupBy(DB::raw("IF(schooltypes.id IS NOT NULL,schooltypes.id,1=1)"));
        return $this->showTypeofSchoolCounts($query->get()->toArray());
    }

    /**
     * this is used to get the count of the registred users for different type of school (Others case)
     * @author     Icreon Tech - dev1.
     * @param type $param
     */
    public function getOtherTypeofSchoolCounts($param = array()) {
        $query = $this->user->select(DB::raw("COUNT(users.school_type) AS user_count, 'Others' as school_type"));
        $query->where('school_type', '=', '-1');
        $result = $query->get()->toArray();
        if (count($result) > 0)
            return '{label: "' . $result[0]['school_type'] . '", data: ' . $result[0]['user_count'] . '}';
    }

    /**
     * this is used to get the count of the registred users for cunties
     * @author     Icreon Tech - dev1.
     * @param type $param
     */
    public function getCountiesUserCounts($param = array()) {
        $query = $this->user->select(DB::raw("IF((users.county IS NOT NULL OR users.county!='0' OR users.county!='-1'),COUNT(users.county),0) AS user_count, counties.name"));
        $query->join('counties as counties', 'counties.id', '=', 'users.county', 'right');
        $query->whereRaw(DB::raw("IF((users.county IS NOT NULL OR users.county!='0' OR users.county!='-1'),users.status!='Deleted',1=1)"));
        $query->where("user_type", "=", $param['user_type']);
        $query->orderBy('user_count', 'desc');
        $query->groupBy(DB::raw("IF(counties.id IS NOT NULL,counties.id,1=1)"));
        $query->limit('5');
        return $this->showCountiesUserCounts($query->get()->toArray());
    }

    /**
     * this is used to get the count of the registred users for cunties
     * @author     Icreon Tech - dev1.
     * @param type $param
     */
    public function getHowHearCounts($param = array()) {
        $query = $this->user->select(DB::raw("IF((users.howfinds_id IS NOT NULL OR users.howfinds_id!='0' OR users.howfinds_id!='-1'),COUNT(users.howfinds_id),0) AS user_count, howfinds.name"));
        $query->join('howfinds', 'howfinds.id', '=', 'users.howfinds_id', 'right');
        $query->whereRaw(DB::raw("IF((users.howfinds_id IS NOT NULL OR users.howfinds_id!='0' OR users.howfinds_id!='-1'),users.status!='Deleted',1=1)"));
        $query->where('users.user_type', '=', $param['user_type']);
        $query->orderBy('user_count', 'desc');
        $query->groupBy(DB::raw("IF(howfinds.id IS NOT NULL,howfinds.id,1=1)"));
        return $this->showHowHearCounts($query->get()->toArray());
    }

    public function getOtherHowHearCounts($param = array()) {
        $query = $this->user->select(DB::raw("COUNT(users.howfinds_id) AS user_count, 'Event/Others' as name"));
        $query->where('howfinds_id', '=', '-1');
        $query->where('users.user_type', '=', $param['user_type']);
        $result = $query->get()->toArray();
        if (count($result) > 0)
            return '{label: "' . $result[0]['name'] . '", data: ' . $result[0]['user_count'] . '}';
    }

    /**
     * this is used to get the count of the registred users for different type of school
     * @author     Icreon Tech - dev1.
     * @param type $data
     */
    public function showTypeofSchoolCounts($data) {
        $typeOfSchoolArray = array();
        if (count($data) > 0) {
            foreach ($data as $key => $val) {
                $typeOfSchoolArray[] = '{label: "' . $val['school_type'] . '", data: ' . $val['user_count'] . '}';
            }
        }
        return $typeOfSchoolArray;
    }

    /**
     * this is used to get the count of the registred users for different type of school
     * @author     Icreon Tech - dev1.
     * @param type $data
     */
    public function showCountiesUserCounts($data) {
        $countiesUsersArray = array();
        if (count($data) > 0) {
            foreach ($data as $key => $val) {
                $countiesUsersArray[] = '{label: "' . $val['name'] . '", data: ' . $val['user_count'] . '}';
            }
        }
        return $countiesUsersArray;
    }

    /**
     * this is used to get the count of the registred users for different type of school
     * @author Icreon Tech - dev1.
     * @param type $data
     */
    public function showHowHearCounts($data) {
        $typeOfSchoolArray = array();
        if (count($data) > 0) {
            foreach ($data as $key => $val) {
                $typeOfSchoolArray[] = '{label: "' . $val['name'] . '", data: ' . $val['user_count'] . '}';
            }
        }
        return $typeOfSchoolArray;
    }

    public function getStudentRewardsList($params = array()) {
        $query = $this->user->select(['student_rewards.id as rank', 'username', 'email'])
                ->join('student_rewards', 'student_rewards.users_id', '=', 'users.id', 'inner');
        if (isset($params['limit']) && !empty($params['limit']))
            $query->limit($params['limit']);
        return $query->get();
    }

    /**
     * Get parent/tutor detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function getPaymentDetails($params = array()) {
        $user = $this->payment
                        ->join('users', 'users.id', '=', 'payments.user_id')
                        ->where('payments.user_id', '=', $params['user_id'])
                        ->where('payments.status', '=', 'Pending')
                        ->select(['payments.payment_type', 'payments.no_of_students', 'payments.status', 'payments.amount', 'payments.created_at', 'payments.currency', 'users.first_name', 'users.last_name', 'users.school_name', 'users.user_type', 'payments.id', 'payments.upgrade_type'])
                        ->get()->toArray();
        return $user;
    }

    public function getUserSubscription($params = array()) {
        return DB::table('user_purchased_students_count')->select(DB::raw('sum(no_of_student) as total_students_subscription'))->where('users_id', '=', $params['user_id'])
                        ->where('payment_status', '=', SUCCESS)->where('user_subscription_status', '=', ACTIVE)->get();
    }

    public function storeLoginDetails($id) {
        $loginDetails = array(
            'last_login' => Carbon::now()->toDateTimeString(),
        );
        DB::table('users')
                ->where('id', $id)
                ->update($loginDetails);
    }

    public function getTeacherStudents($params = array()) {
        $user = $this->user->where('users.user_type', '=', STUDENT)
                        ->where('users.teacher_id', '=', $params['teacher_id'])
                        ->where('users.status', '=', ACTIVE)
                        ->select(['users.id'])
                        ->get()->toArray();
        return $user;
    }

    /**
     * This is used to delete an Event
     * @param Request $request
     * @return type
     * @author     Icreon Tech  - dev5.
     */
    public function saveEvent($inputs) {
        $events = array(
            'title' => $inputs['title'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'start_date' => $inputs['date'],
            'end_date' => $inputs['date'],
            'start_time' => $inputs['startTime'],
            'end_time' => $inputs['endTime'],
            'created_by' => session()->get('user')['id'],
        );
        DB::table('events')->insert($events);
    }

    /**
     * This is used to delete an Event
     * @param Request $request
     * @return type
     * @author     Icreon Tech  - dev5.
     */
    public function updateEvent($inputs) {
        DB::table('events')
                ->where('id', $inputs['id'])
                ->update(array(
                    'title' => $inputs['title'],
                    'start_time' => $inputs['startTime'],
                    'end_time' => $inputs['endTime'],
                    'updated_by' => $inputs['updated_by'],
        ));
    }

    /**
     * This is used to delete an Event
     * @param Request $request
     * @return type
     * @author     Icreon Tech  - dev5.
     */
    public function deleteEvent($id) {
        DB::table('events')->where('id', '=', $id)->delete();
    }

    /**
     * This is used to get the county details
     * @params array 
     * @return type array
     * @author     Icreon Tech  - dev1.
     */
    public function getCounty($params = array()) {
        return DB::table('counties')->select('id')->where('name', '=', $params['county'])->first();
    }

    /**
     * This is used to get ethnicity
     * @params array 
     * @return type array
     * @author     Icreon Tech  - dev1.
     */
    public function getEthnicity($params = array()) {
        return DB::table('ethnicities')->select('id', 'code')->where('code', '=', $params['ethnicity'])->first();
    }

    /**
     * insert the user .
     * @param  array  $inputs
     * @return App\Models\User 
     */
    public function importStudent($inputs) {
        $user = new $this->user;
        $user->password = bcrypt($inputs['password']);
        $lastInsertedId = $this->save($user, $inputs);
    }

    public function getClassList($schoolId) {
        $sqlClassList = "select * from schoolclasses where created_by = " . $schoolId . " and status!='Deleted'";
        $classList = DB::select($sqlClassList);
        return $classList;
    }

    public function getStudentList($schoolId) {
        
    }

    public function getClassName($params = array()) {
        return DB::table('schoolclasses')->select('id')->where('class_name', '=', trim($params['class_name']))->where('created_by', '=', $params['school_id'])->first();
    }

    public function getGroupName($params = array()) {
        return DB::table('groups')->select('id')->where('group_name', '=', trim($params['group_name']))->where('created_by', '=', $params['school_id'])->first();
    }

    public function showRevenue($id) {
        $group = $this->user
                        ->select(['id', 'class_name', 'year', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'])
                        ->where('id', '=', $id)
                        ->get()->first();
        return $group;
    }

    public function updateRemainingAccount($updateParam = array()) {
        //if ($updateParam['user_type'] == TUTOR || $updateParam['user_type'] == TUTOR || $updateParam['user_type'] == TEACHER)
            DB::update('update users set remaining_no_of_student=remaining_no_of_student-1 where id=' . $updateParam['user_id']);
    }
    public function getRemainingAccountStudent($params = array()) {
        return DB::table('users')->select('remaining_no_of_student')->where('id', '=', $params['school_id'])->first();
    }

    public function renewUserSubscription($input) {
        $model = $this->user->findOrFail($input['userId']);
        if (isset($input['status'])) {
            $model->status = $input['status'];
        }
        if (isset($input['subscription_status'])) {
            $model->subscription_status = $input['subscription_status'];
        }
        if (!empty($input['subscription_expiry_date'])) {

            $date = strtotime($input['subscription_expiry_date']);
            $new_date = strtotime('+ 1 year', $date);
            $endDate = date('Y-m-d', $new_date);

            $model->subscription_expiry_date = $endDate;
        } else {
            $date = strtotime($this->currentDateTime);
            $new_date = strtotime('+ 1 year', $date);
            $endDate = date('Y-m-d', $new_date);
            $model->subscription_start_date = $this->currentDateTime;
            $model->subscription_expiry_date = $endDate;
        }
        $model->save();
    }
    /**
     * list of users
     * @param type $params 
     */
    public function getUserForPayment($params = array()) {
        $defaultSelect = ['users.id', 'username', 'first_name', 'last_name', 'email', 'school_name', 'user_type', 'remaining_no_of_student'];
        $select = isset($params['select']) ? $params['select'] : $defaultSelect;
        $query = $this->user
                ->select($select);

        if (isset($params['id']) && !empty($params['id']))
            $query->where('users.id', '=', $params['id']);
        if (isset($params['user_type']) && !empty($params['user_type']))
            $query->where('users.user_type', '=', $params['user_type']);
        if (isset($params['school_id']) && !empty($params['school_id']))
            $query->where('users.school_id', '=', $params['school_id']);
        if (isset($params['status']) && !empty($params['status']))
            $query->where('users.status', '=', $params['status']);
        if (isset($params['email']) && !empty($params['email']))
            $query->where('users.email', '=', $params['email']);
        if (isset($params['username']) && !empty($params['username']))
            $query->where('users.username', '=', $params['username']);
        return $query;
    }
    
    public function getStudentAssigendClass($params){
        return $this->classStudents
                        ->join('schoolclasses', 'classstudents.class_id', '=', 'schoolclasses.id', 'inner')
                        ->select(['schoolclasses.id', 'schoolclasses.class_name'])
                        ->where("classstudents.student_id", "=", $params['student_id'])
                        ->where("schoolclasses.status", "!=", DELETED)
                        ->get()->first()->toArray();
    }
}
