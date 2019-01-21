<?php

namespace App\Repositories;

use App\Models\Fee;

class FeesRepository extends BaseRepository {

    /**
     * The Fees instance.
     * @author     Icreon Tech - dev5.
     * @var App\Models\Fee
     */
    protected $model;

    /**
     * Create a new Fee instance.
     * @author     Icreon Tech - dev5.
     * @param  App\Models\Fee $Fee
     * @return void
     */
    public function __construct(Fee $fee) {
        $this->model = $fee;
    }

    /**
     * Save Fee record to database.
     * @author     Icreon Tech - dev5.
     * @param  App\Models\Fee
     * @param  Array  $inputs
     * @return void
     */
    private function save($fee, $inputs) {
        $fee->school_sign_up_fee = $inputs['school_sign_up_fee'];
        $fee->parent_sign_up_fee = $inputs['parent_sign_up_fee'];
        $fee->per_student_fee = $inputs['per_student_fee'];
        $fee->per_5_student_fee = $inputs['per_5_student_fee'];
        $fee->status = ACTIVE;
        $fee->created_by = session()->get('user')['id'];
        $fee->updated_by = session()->get('user')['id'];
        $fee->save();
    }

    /**
     * Create New Fee record.
     * @author     Icreon Tech - dev5.
     * @param  array  $inputs
     * @return App\Models\Fee 
     */
    public function store($inputs) {
        $fees = new $this->model;
        $this->save($fees, $inputs);
        return $fees;
    }

    /**
     * Update Fee.
     * Update a existing Fee record.
     * @param  App\Models\Fee $fees
     * @author     Icreon Tech - dev5.
     * @return void
     */
    public function update($inputs, $id) {
        $fees = $this->getById($id);
        $fees::where('id', $id)->update(array('status' => INACTIVE)); // Making previous ststus 0
        $fees = new $this->model;
        $this->save($fees, $inputs);
    }

    /**
     * Get post collection.
     * @param  int  $id
     * @author     Icreon Tech - dev5.
     * @return array
     */
    public function edit($id) {
        $fees = $this->model->findOrFail($id);
        return compact('fees');
    }

    /**
     * List of all Fee records from database
     * @author     Icreon Tech - dev5.
     * @param type $params 
     */
    public function getFee($status = ACTIVE) {

        return $this->model->select(['fees.id', 'users.username', 'fees.school_sign_up_fee', 'fees.parent_sign_up_fee', 'fees.per_student_fee', 'fees.per_5_student_fee', 'fees.updated_at', 'fees.updated_by'])
                        ->join('users', 'fees.updated_by', '=', 'users.id')->where('fees.status', '=', $status);
    }

}
