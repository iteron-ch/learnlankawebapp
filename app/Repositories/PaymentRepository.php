<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Paypalpayment;
use PayPal\Exception\PayPalConnectionException;
use DB;

class PaymentRepository extends BaseRepository {

    /**
     * The Payment instance.
     * @var App\Models\Payment
     */
    protected $payment;
    protected $user;

    /**
     * Paypalpayment instance to authenticate the call.
     * @param object $_apiContext
     */
    private $_apiContext;
    protected $currentDateTime;

    /**
     * Create a new PaymentRepository instance.
     *
     * @param  App\Models\Payment $payment
     * @return void
     */
    public function __construct(User $user, Payment $payment) {
        $this->user = $user;
        $this->payment = $payment;
        $this->_apiContext = Paypalpayment::ApiContext(config('paypal_payment.Account.ClientId'), config('paypal_payment.Account.ClientSecret'));
        $this->currentDateTime = Carbon::now()->toDateTimeString();
    }

    /**
     * make payment 
     * @author     Icreon Tech - dev2.
     * @param type $inputs array
     * @return type array $inputs
     */
    public function makePayment($inputs) {
        // ### Address
        // Base Address object used as shipping or billing
        // address in a payment. [Optional]
        $addr = Paypalpayment::address();
        $addr->setLine1($inputs['billing_address']);
        $addr->setCity($inputs['billing_city']);
        //$addr->setState("NY");
        $addr->setPostalCode($inputs['billing_postal_code']);
        $addr->setCountryCode(CURRENCY_CODE);
        //$addr->setPhone("716-298-1822");
        // ### CreditCard
        $card = Paypalpayment::creditCard();
        $card->setType($inputs['card_type'])
                ->setNumber($inputs['card_no'])
                ->setExpireMonth($inputs['expiry_month'])
                ->setExpireYear($inputs['expiry_year'])
                ->setCvv2($inputs['cvv_no'])
                ->setFirstName($inputs['billing_first_name'])
                ->setLastName($inputs['billing_last_name']);
        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // Use a Payer ID (A unique identifier of the payer generated
        // and provided by the facilitator. This is required when
        // creating or using a tokenized funding instrument)
        // and the `CreditCardDetails`
        $fi = Paypalpayment::fundingInstrument();
        $fi->setCreditCard($card);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // Use the List of `FundingInstrument` and the Payment Method
        // as 'credit_card'
        $payer = Paypalpayment::payer();
        $payer->setPaymentMethod("credit_card")
                ->setFundingInstruments(array($fi));

        $item1 = Paypalpayment::item();
        $item1->setName(PURCHASE_SUBSCRIPTION)
                ->setDescription(NEW_SIGNUP)
                ->setCurrency(CURRENCY_CODE)
                ->setQuantity(1)
                ->setPrice($inputs['amount']);


        $itemList = Paypalpayment::itemList();
        $itemList->setItems(array($item1));

        //Payment Amount
        $amount = Paypalpayment::amount();
        $amount->setCurrency(CURRENCY_CODE)
                ->setTotal($inputs['amount']);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types

        $transaction = Paypalpayment::transaction();
        $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription(NEW_SIGNUP)
                ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent as 'sale'

        $payment = Paypalpayment::payment();

        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setTransactions(array($transaction));

        try {
            $payment->create($this->_apiContext);
            return array('status' => 'success', 'response' => $payment);
        } catch (PayPalConnectionException $ex) {
            return array('status' => 'failed', 'response' => $ex->getData());
        }
    }

    /**
     * 
     * @param type $transactionsDetail
     */
    public function saveTransaction($paymentData) {

        $created_at = $this->currentDateTime;
        $payment = new $this->payment();
        $payment->user_id = $paymentData['userId'];

        $payment->plan_students = isset($paymentData['plan_students']) ? $paymentData['plan_students'] : '0';
        $payment->additional_students = isset($paymentData['additional_students']) ? $paymentData['additional_students'] : '0';
        $payment->plan_amount = isset($paymentData['plan_amount']) ? $paymentData['plan_amount'] : '0';
        $payment->additional_amount = isset($paymentData['additional_amount']) ? $paymentData['additional_amount'] : '0';


        if ($paymentData['status'] == 'success') {
            if ($paymentData['payment_type'] == "Creditcard") {

                $payment->status = SUCCESS;
                $payment->original_amount = $paymentData['original_amount'];
                $payment->amount = $paymentData['amount'];
                $payment->currency = CURRENCY_CODE;
                $payment->payment_type = 'Creditcard';
                $payment->no_of_students = $paymentData['no_of_student'];
                $payment->upgrade_type = $paymentData['upgrade_type'];
                $payment->transaction_id = $paymentData['transaction_id'];
                $payment->payment_date = isset($paymentData['payment_date']) ? $paymentData['payment_date'] : NULL_DATETIME;
            } else {
                $payment->status = "Pending";
                $payment->amount = $paymentData['amount'];
                $payment->original_amount = $paymentData['original_amount'];
                $payment->currency = CURRENCY_CODE;
                $payment->no_of_students = $paymentData['no_of_student'];
                $payment->upgrade_type = $paymentData['upgrade_type'];
                $payment->payment_type = 'Invoiced';
            }
        } else {
            $payment->status = "Pending";
            $payment->amount = $paymentData['amount'];
            $payment->currency = CURRENCY_CODE;
            $payment->no_of_students = $paymentData['no_of_student'];
            $payment->payment_type = 'Invoiced';
            $payment->voucher_code = isset($paymentData['voucher_code']) ? $paymentData['voucher_code'] : '';
            $payment->discount_amount = isset($paymentData['discount_amount']) ? $paymentData['discount_amount'] : '';
            $payment->billing_first_name = isset($paymentData['billing_first_name']) ? $paymentData['billing_first_name'] : '';
            $payment->billing_last_name = isset($paymentData['billing_last_name']) ? $paymentData['billing_last_name'] : '';
            $payment->billing_address = isset($paymentData['billing_address']) ? $paymentData['billing_address'] : '';
            $payment->billing_city = isset($paymentData['billing_city']) ? $paymentData['billing_city'] : '';
            $payment->billing_postal_code = isset($paymentData['billing_postal_code']) ? $paymentData['billing_postal_code'] : '';
            $payment->billing_county = isset($paymentData['billing_county']) ? $paymentData['billing_county'] : '';
            $payment->billing_country = isset($paymentData['billing_country']) ? $paymentData['billing_country'] : '';
            $payment->original_amount = isset($paymentData['original_amount']) ? $paymentData['original_amount'] : '0';
            $payment->upgrade_type = isset($paymentData['upgrade_type']) ? $paymentData['upgrade_type'] : '';

            /* $response = $paymentResponse['response'];
              $error = json_decode($response);
              $payment->status = FAIL;
              if ($error == true)
              $payment->payment_fail_reason = $error->name;
             */
        }
        $lastId = $payment->save() ? $payment->id : FALSE;
        if ($paymentData['payment_type'] == "Invoiced") {
            DB::table('payments')
                    ->where('id', $lastId)
                    ->update(array('transaction_id' => $lastId));
        }
        return $lastId;
    }

    /**
     * list of users 
     * @param type $params 
     */
    public function getUserPaymentList($params = array()) {
        $commonFieldsArray = array('users.id', 'username', 'first_name', 'last_name', 'email', 'users.created_at', 'school_name', 'users.status', 'users.school_id', 'users.user_type');
        $extraFieldsArray = array('payments.no_of_students as no_of_students', 'payments.payment_type as payment_type', 'payments.status as payment_status', 'payments.updated_at as updated_at');
        $fieldsArray = array_merge($commonFieldsArray, $extraFieldsArray);
        $query = $this->user->select($fieldsArray)
                ->join('payments', 'payments.user_id', '=', 'users.id', 'inner')
                ->where('users.status', '!=', DELETED);
        if (isset($params['status']) && !empty($params['status']))
            $query->where('payments.status', '=', $params['status']);
        //   if (isset($params['limit']) && !empty($params['limit']))
        //       $query->limit($params['limit']);
        return $query;
    }

    /**
     * Update a payment status.
     * @param type $inputs
     * @param type $id
     * @return type
     */
    public function update($inputs, $id) {
        $payment = $this->payment->where('id', '=', $id)->first();
        
        if($payment->payment_type == "Invoiced" && empty($payment->transaction_id))
            $payment->transaction_id = $id;
        
        $payment->status = isset($inputs['payment_status']) ? $inputs['payment_status'] : 'Pending';
        $payment->payment_note = isset($inputs['payment_note']) ? $inputs['payment_note'] : '';
        $payment->payment_date = $this->currentDateTime;
        $payment->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
        $payment->upgrade_type = isset($inputs['upgrade_type']) ? $inputs['upgrade_type'] : '1';
        return $payment->save() ? $payment->toArray() : FALSE;
    }

    function updateUserSubscription($userId) {
        $result = DB::select(DB::raw("select * from  user_purchased_students_count WHERE users_id = '" . $userId . "' and payment_status='Success' and user_subscription_status='Active'"));
        if (count($result) > 0) {
            $resultData = $result[0];
            $subscription_start_date = $resultData->subscription_start_date;
            $subscription_expiry_date = $resultData->subscription_expiry_date;
        } else {
            $dateParam['start_date'] = $this->currentDateTime;
            $dateParam['user_type'] = $user['user_type'];
            $subscription_start_date = $this->currentDateTime;
            $subscription_expiry_date = getSubscriptionExpiryDate($dateParam);
        }
        DB::table('user_purchased_students_count')
                ->where('users_id', $userId)
                ->where('subscription_start_date', '=', NULL_DATETIME)
                ->update(array('payment_status' => SUCCESS, 'subscription_start_date' => $subscription_start_date, 'subscription_expiry_date' => $subscription_expiry_date, 'user_subscription_status' => ACTIVE));

        DB::table('users')
                ->where('id', $userId)
                ->update(array('subscription_start_date' => $subscription_start_date, 'subscription_expiry_date' => $subscription_expiry_date));
    }

    function saveUserStudentsCounts($params) {
        $userDataParam = array(
            'users_id' => $params['user_id'],
            'no_of_student' => $params['no_of_student'],
            'upgrade_type' => $params['upgrade_type'],
        );
        DB::table('user_purchased_students_count')->insert($userDataParam);

        if ($params['payment_status'] == SUCCESS) {
            $this->updateUserSubscription($params['user_id']);
        }
    }

    function updateUserTotalStudentsCounts($params) {
        $userDataParam = array(
            'no_of_student' => 'no_of_student' + $params['no_of_student'],
        );
        DB::statement(DB::raw("update userstudents set no_of_student = no_of_student+" . $params['no_of_student'] . " WHERE users_id = '" . $params['user_id'] . "'"));
    }

    function getPaymentDetails($params) {
        return $payment = $this->payment->where('id', '=', $params['id'])->where('user_id', '=', $params['user_id'])->first();
    }

    function userPaymentUpdate($params) {
        DB::statement(DB::raw("update payments set status ='" . $params['status'] . "',payment_type ='" . $params['payment_type'] . "',transaction_id ='" . $params['transaction_id'] . "',payment_date ='" . $params['payment_date'] . "' WHERE user_id = '" . $params['user_id'] . "' AND id = '" . $params['id'] . "'"));
    }

    function updateTransactionNo($paymentData) {
        if ($paymentData['payment_type'] == "Invoiced") {
            DB::table('payments')
                    ->where('id', $paymentData['transaction_id'])
                    ->update(array('transaction_id' => $paymentData['transaction_id']));
        }
    }

}
