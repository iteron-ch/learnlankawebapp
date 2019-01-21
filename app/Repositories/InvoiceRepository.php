<?php

namespace App\Repositories;

use App\Models\Payment;
use Carbon\Carbon;
use DB;

class InvoiceRepository extends BaseRepository {

    /**

     * The SchoolClass instance.
     *
     * @var App\Models\Schoolclass
     */
    protected $payment;

    /**
     * The Classstudents instance.
     *
     * @var App\Models\Classstudents
     */

    /**
     * Create a new SchoolClassRepository instance.
     * @author     Icreon Tech  - dev2.
     * @param \App\Models\Schoolclass $schoolclass
     * @param \App\Models\Classstudents $classstudents
     * return void
     */
    public function __construct(Payment $payment) {
        $this->payment = $payment;
    }

    public function getInvoiceList($params = array()) {
        $query = $this->payment
                ->select(['users.username','users.user_type','users.email','users.telephone_no','users.school_name','payments.upgrade_type','payments.voucher_code','payments.discount_amount','payments.status','payments.original_amount','payments.no_of_students','payments.payment_type','payments.id AS payment_id', 'payments.invoice_number', 'payments.created_at', 'payments.no_of_students', 'payments.amount', 'users.subscription_start_date', 'users.subscription_expiry_date','payments.transaction_id'])
                ->join('users', 'users.id', '=', 'payments.user_id', 'left')
                ->where('payments.status', '=', SUCCESS)
                ->where('users.status', '!=', DELETED);
        if (isset($params['id']) && !empty($params['id'])) {
            $query->where('payments.id', '=', $params['id']);
        }
        return $query;
    }
    
    public function getInvoicePrintList($params = array()) {
        $query = $this->payment
                ->select(['users.email','users.address','users.city','users.postal_code','users.county','users.country','users.first_name','users.last_name','users.telephone_no','users.school_name','payments.upgrade_type','payments.voucher_code','payments.plan_students','payments.additional_students','payments.plan_amount','payments.additional_amount','payments.discount_amount','payments.status','payments.original_amount','payments.no_of_students','payments.payment_type','payments.id AS payment_id', 'payments.invoice_number', 'payments.updated_at','payments.transaction_id','payments.payment_date', 'payments.no_of_students', 'payments.amount', 'users.subscription_start_date', 'users.subscription_expiry_date','payments.transaction_id','payments.billing_first_name','payments.billing_last_name','users.user_type'])
                ->join('users', 'users.id', '=', 'payments.user_id', 'left')
                ->where('users.status', '!=', DELETED);
        if (isset($params['id']) && !empty($params['id'])) {
            $query->where('payments.status', '=', SUCCESS);
            $query->where('payments.id', '=', $params['id']);
        }
        if (isset($params['last_id']) && !empty($params['last_id'])) {
            $query->where('payments.status', '!=', FAIL);
            $query->where('payments.id', '=', $params['last_id']);
        }
        if (isset($params['type']) && !empty($params['type'])) {
            $query->where('users.id', '=', $params['user_id']);
        }
        
        return $query;
    }

}
