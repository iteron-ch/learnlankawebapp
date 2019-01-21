<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Voucher;

class VoucherRepository extends BaseRepository {

    /**
     * The Voucher instance.
     * @author Icreon Tech - dev5.
     * @var App\Models\Voucher
     */
    protected $voucherSet;

    /**
     * Create a new VoucherRepository instance.
     * @author Icreon Tech - dev5.
     * @param  App\Models\Voucher 
     * @return void
     */
    public function __construct(Voucher $voucherSet) {
        $this->voucherSet = $voucherSet;
    }

    /**
     * Save the Voucher.
     * @author Icreon Tech - dev5.
     * @param  App\Models\Voucher 
     * @param  Array  $inputs
     * @return void
     */
    public function save($inputs, $voucherSet) {
        $voucherSet->voucher_code = $inputs['voucher_code'];
        $voucherSet->discount = $inputs['discount'];
        $voucherSet->discount_type = $inputs['discount_type'];
        $voucherSet->start_date = inputDateFormat($inputs['start_date']);
        $voucherSet->end_date = inputDateFormat($inputs['end_date']);
        $voucherSet->user_type = $inputs['user_type'];
        $voucherSet->status = $inputs['status'];
        if (isset($inputs['created_by'])) {
            $voucherSet->created_by = $inputs['created_by'];
        }
        $voucherSet->updated_by = $inputs['updated_by'];
        $voucherSet->save();
    }

    /**
     * Create a new Voucher.
     * @author Icreon Tech - dev5.
     * @param  array  $inputs
     * @return App\Models\Voucher 
     */
    public function store($inputs) {
        $voucherSet = new $this->voucherSet;
        $this->save($inputs, $voucherSet);
    }

    public function showVoucher($id) {
        $voucherSet = $this->voucherSet
                        ->where('id', '=', $id)
                        ->select(['voucher_code', 'discount', 'discount_type', 'start_date AS start_date', 'end_date', 'user_type', 'status', 'created_at', 'updated_at'])
                        ->get()->first();
        return $voucherSet;
    }

    /**
     * Update a existing Voucher.
     * @author Icreon Tech - dev5.
     * @param  array  $inputs
     * @param  App\Models\Voucher 
     * @return void
     */
    public function update($inputs, $id) {
        $voucherSet = $this->voucherSet->where('id', '=', $id)->first();
        $this->save($inputs, $voucherSet);
    }

    /**
     * Update a Voucher status.
     * @author Icreon Tech - dev5.
     * @param  array  $inputs
     * @param  App\Models\Voucher 
     * @return void
     */
    public function destroyVoucher($inputs, $id) {
        $voucherSet = $this->voucherSet->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $voucherSet->updated_by = $inputs['updated_by'];
        $voucherSet->deleted_at = $dateTime;
        $voucherSet->status = DELETED; // deleted
        $voucherSet->save();
    }

    /**
     * Fetch all voucher records from database. 
     * @author Icreon Tech - dev5.
     * @param type $params
     * @return type
     */
    public function getVoucherList() {
        return $this->voucherSet
                        ->where('status', '!=', DELETED)
                        ->select(['id', 'voucher_code', 'discount', 'discount_type', 'start_date', 'end_date', 'user_type', 'status', 'created_at', 'created_by', 'deleted_at', 'updated_at', 'updated_by']);
    }

    /**
     * Fetch voucher record detail. 
     * @author Icreon Tech - dev5.
     * @param type $params
     * @return type
     */
    public function getVoucherDetails($patams = array()) {

        $dateTime = Carbon::now()->toDateTimeString();
        $voucherSet = $this->voucherSet
                        ->where('voucher_code', '=', trim($patams['voucher_code']))
                        ->select(['voucher_code', 'discount', 'discount_type', 'start_date AS start_date', 'end_date', 'user_type', 'status', 'created_at', 'updated_at'])
                        ->where('start_date', '<=', $dateTime)
                        ->where('end_date', '>=', $dateTime)
                        ->where('user_type', '>=', $patams['user_type'])
                        ->get()->first();
        if(!empty($voucherSet))
            return $voucherSet->toArray();
        else
            return false;
    }

}
