<?php

namespace App\Repositories;

use App\Models\Paymentmethod;

class PaymentmethodRepository extends BaseRepository {

    /**
     * The Paymentmethod instance.
     *
     * @var App\Models\Paymentmethod
     */
    protected $model;

    /**
     * Create a new Paymentmethod instance.
     *
     * @param  App\Models\Paymentmethod $paymentmethod
     * @return void
     */
    public function __construct(Paymentmethod $paymentmethod) {
        $this->model = $paymentmethod;
    }

    /**
     * Save Fee.
     *
     * @param  App\Models\Cmspage
     * @param  Array  $inputs
     * @return void
     */
    private function save($paymentmethod, $inputs) {
        $paymentmethod->paypal_email = $inputs['paypal_email'];
        $paymentmethod->transaction_key = $inputs['transaction_key'];
        $paymentmethod->transaction_user_id = $inputs['transaction_user_id'];
        $paymentmethod->transaction_password = $inputs['transaction_password'];
        $paymentmethod->paypal_method = $inputs['paypal_method'];
        $paymentmethod->created_by = session()->get('user')['id'];
        $paymentmethod->save();
    }

    /**
     * Create Fee.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     * @return App\Models\Paymentmethod 
     */
    public function store($inputs) {
        $paymentmethod = new $this->model;
        $this->save($paymentmethod, $inputs);
        return $paymentmethod;
    }

    public function getPaymentDetails($status) {
        return $this->model->select(['paymentmethods.id', 'users.username', 'paymentmethods.transaction_key', 'paymentmethods.transaction_user_id', 'paymentmethods.transaction_password', 'paymentmethods.paypal_email', 'paymentmethods.created_at', 'paymentmethods.paypal_method'])
                        ->join('users', 'paymentmethods.created_by', '=', 'users.id')->where('paymentmethods.status', '=', $status);
    }
  /**
     * Update Fee.
     *
     * @param  array  $inputs
     * @param  App\Models\Fee $paymentmethod
     * @return void
     */
    public function update($inputs, $id) {
        $paymentmethod = $this->getById($id);
        $paymentmethod::where('id', $id)->update(array('status' => INACTIVE)); // Making previous ststus 0
        $paymentmethod = new $this->model;
        $this->save($paymentmethod, $inputs);
    }

    /**
     * Get post collection.
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id) {
        $paymentmethod = $this->model->findOrFail($id);
        return compact('paymentmethod');
    }

}
