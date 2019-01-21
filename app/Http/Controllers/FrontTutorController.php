<?php

/**
 * This controller is used for Tutor.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Requests\Tutor\FrontTutorCreateRequest;
use App\Http\Requests\Tutor\CreditPaymentRequest;
use App\Http\Requests\Tutor\PaymentRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\Howfind;
use App\Models\County;
use DB;
use Datatables;
use App\Repositories\EmailRepository;
use App\Repositories\MessageRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\FeesRepository;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;
use Braintree_ClientToken;
use Braintree_Transaction;

/**
 * This controller is used for tutor.
 * @author     Icreon Tech - dev1.
 */
class FrontTutorController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;
    protected $feesRepository;
    protected $currentDateTime;
    protected $invoiceRepo;

    /**
     * Create a new TutorController instance.
     * @param  App\Repositories\UserRepository $userRepo
     * @return void
     */
    public function __construct(UserRepository $userRepo, PaymentRepository $paymentRepo, FeesRepository $feesRepository, InvoiceRepository $invoiceRepo) {
        $this->userRepo = $userRepo;
        $this->paymentRepo = $paymentRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->feesRepository = $feesRepository;
        $this->currentDateTime = Carbon::now()->toDateTimeString();
    }

    /**
     * Show the form for creating a new tutor/parent.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function create(Request $request) {

        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['month'] = monthArray();
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['year'] = creditCardExpiryYear(date('Y'));

        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
        $perStudentFee = $feeRecord[0]['per_student_fee'];
        $parentSignUpFee = $feeRecord[0]['parent_sign_up_fee'];
        $data['per_student_fee'] = $perStudentFee;
        $data['parent_sign_up_fee'] = $parentSignUpFee;

        $data['status'] = statusArray();
        if (!empty($request->get('error')))
            $data['error_type'] = $request->get('error');
        else
            $data['error_type'] = '';
        $data['user'] = array();
        $data['user']['expiry_month'] = date('m');
        /* payment error case */
        if (!empty($request->get('userId'))) {
            $id = decryptParam($request->get('userId'));
            $user = $this->userRepo->getSubscribedUser($id)->toArray();
            $data['user'] = $user;
        }
        $data['page_heading'] = trans('front/tutor.manage_tutor');
        $data['page_title'] = trans('front/tutor.add_tutor');
        $data['trait'] = array('trait_1' => trans('front/tutor.tutor'), 'trait_1_link' => route('tutor.index'), 'trait_2' => trans('front/tutor.add_tutor'));
        $data['JsValidator'] = 'App\Http\Requests\Tutor\FrontTutorCreateRequest';
        return view('auth.register', $data);
    }

    /**
     * Insert a new the tutor/parent
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Tutor\TutorCreateRequest $request
     * @return Response
     */
    public function store(FrontTutorCreateRequest $request, EmailRepository $emailRepo) {
        // asd($request->all());
        $inputData = $this->userRepo->getTutorformData($request->all());
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
        // asd($feeRecord);
        /* make payment */
        $original_amount = $feeRecord[0]['parent_sign_up_fee'] + $feeRecord[0]['per_student_fee'] * $inputData['basic_info']['no_of_student'];
        $voucher_code = $request->get('voucher_code');
        $discount_amount = $request->get('total_discount_amount');
        $paymentData = $inputData['payment_info'];
        if (!empty($discount_amount))
            $paymentData['amount'] = $original_amount - $discount_amount;
        else
            $paymentData['amount'] = $original_amount;

        $inputData['basic_info']['payment_type'] = "";
        $paymentResponse['status'] = 'Pending';
        /* if ($inputData['basic_info']['payment_type'] == "Invoiced") {
          $paymentResponse['status'] = "success";
          } else {
          $paymentResponse = $this->paymentRepo->makePayment($paymentData);
          } */
        /* add user basic info and billing detail */
        $basicInfoData = $inputData['basic_info'];

        $basicInfoData['user_type'] = TUTOR;
        $basicInfoData['subscription_status'] = UNSUBSCRIBED;
        /* if (!empty($basicInfoData['userId'])) { // record update case in case of transaction fail and user changes the basic information 
          $userId = decryptParam($basicInfoData['userId']);
          $basicInfoData['updated_by'] = $userId;
          $basicInfoData['id'] = $userId;
          $this->userRepo->frontUserUpdate($basicInfoData, $userId);
          } else { */
        $basicInfoData['status'] = DELETED;
        $basicInfoData['deleted_at'] = $this->currentDateTime;
        $userId = $this->userRepo->frontStore($basicInfoData);
        /* $emailTemplateId = '11';
          $emailParam = array(
          'addressData' => array(
          'to_email' => $basicInfoData['email'],
          'to_name' => $basicInfoData['username'],
          ),
          'userData' => array(
          'username' => $basicInfoData['username'],
          'first_name' => $basicInfoData['first_name'],
          'last_name' => $basicInfoData['last_name'],
          'password' => $basicInfoData['password'],
          )
          ); */

        // }
        /* save transaction */

        $totalNoOfStudent = TUTOR_NO_OF_STUDENTS + $basicInfoData['no_of_student'];
        $paymentData['userId'] = $userId;
        $paymentData['no_of_student'] = $totalNoOfStudent;
        if ($basicInfoData['user_type'] == TUTOR)
            $paymentData['payment_type'] = 'Invoiced'; //$inputData['basic_info']['payment_type']
        else
            $paymentData['payment_type'] = 'Creditcard';
        $paymentData['original_amount'] = $original_amount;
        $paymentData['discount_amount'] = $discount_amount;
        $paymentData['voucher_code'] = $voucher_code;
        $paymentData['upgrade_type'] = 2;
        $paymentData['status'] = 'Pending';

        $paymentData['plan_students'] = TUTOR_NO_OF_STUDENTS;
        $paymentData['additional_students'] = $basicInfoData['no_of_student'];
        $paymentData['plan_amount'] = $feeRecord[0]['parent_sign_up_fee'];
        $paymentData['additional_amount'] = $feeRecord[0]['per_student_fee'] * $inputData['basic_info']['no_of_student'];

        $paymentId = $this->paymentRepo->saveTransaction($paymentData);

        $this->userRepo->userSubscriptionUpdate(array(
            'userId' => $userId,
            'status' => DELETED,
            //  'no_of_student' => $totalNoOfStudent,
            'subscription_start_date' => '',
            'subscription_expiry_date' => ''
        ));

        //$emailRepo->sendEmail($emailParam, $emailTemplateId);
        return redirect(route('fronttutor.payment', ['userId' => encryptParam($userId), 'paymentId' => encryptParam($paymentId)]));
        /*
          if ($paymentResponse['status'] == 'success') {
          if ($inputData['basic_info']['payment_type'] == 'Creditcard') {
          $this->userRepo->userSubscriptionUpdate(array(
          'userId' => $userId,
          'status' => ACTIVE,
          'no_of_student' => $totalNoOfStudent,
          'subscription_start_date' => Carbon::now()->toDateTimeString(),
          'subscription_expiry_date' => Carbon::now()->addMonths(12)->toDateTimeString()
          ));
          }
          return redirect(route('frontschool.signupconfirm', $inputData['basic_info']['payment_type']));
          } else {
          $tempParamData = array();
          $tempParamData['user_id'] = $userId;
          $tempParamData['no_of_student'] = $basicInfoData['no_of_student'];
          $this->userRepo->deleteUserTemporaryInformation($tempParamData);
          return redirect(route('fronttutor.create', ['error' => 'payment', 'userId' => encryptParam($userId)]))->with('error', trans('front/front.payment_fail'));
          }
         */
    }

    public function payment(Request $request) {
        //asd($request->all());
        $params[] = array();
        $params['id'] = decryptParam($request->get('paymentId'));
        $params['user_id'] = decryptParam($request->get('userId'));
        $paymentArray = $this->paymentRepo->getPaymentDetails($params)->toArray();
        $userParam['id'] = $params['user_id'];
        $userArray = $this->userRepo->getFrontUser($userParam)->get()->first()->toArray();

        //  if (count($paymentArray)==0) {
        //     return redirect('error404');
        // }
        $data['paymentArray'] = $paymentArray;
        $clientToken = Braintree_ClientToken::generate();
        $data['clientToken'] = $clientToken;
        $data['id'] = $request->get('paymentId');
        $data['user_id'] = $request->get('userId');
        $data['month'] = monthArray();
        $data['year'] = creditCardExpiryYear(date('Y'));
        $data['page_heading'] = trans('front/tutor.manage_tutor');
        $data['page_title'] = trans('front/tutor.add_tutor');
        $data['trait'] = array('trait_1' => trans('front/tutor.tutor'), 'trait_1_link' => route('tutor.index'), 'trait_2' => trans('front/tutor.add_tutor'));
        $data['JsValidator'] = 'App\Http\Requests\Tutor\CreditPaymentRequest';
        if ($userArray['user_type'] == SCHOOL)
            return view('front.school.payment', $data);
        else if ($userArray['user_type'] == TUTOR)
            return view('front.tutor.payment', $data);
        die;
    }

    public function makePayment(Request $request, EmailRepository $emailRepo, MessageRepository $messageRepo) {
        $inputs = $request->all();
        $paymentMethodNonce = $request->input('payment_method_nonce');
        $userId = $request->input('user_id');
        $paymentId = $request->input('payment_id');
        $params = array();
        $params['id'] = decryptParam($paymentId);
        $params['user_id'] = decryptParam($userId);
        $paymentArray = $this->paymentRepo->getPaymentDetails($params)->toArray();
        $user = User::findOrFail($params['user_id'])->toArray();

        // if (empty($paymentArray)) {
        //     return redirect('error404');
        // }
        // asd($paymentArray);
        try {
            $result = Braintree_Transaction::sale(array(
                        'amount' => $paymentArray['amount'],
                        'paymentMethodNonce' => $paymentMethodNonce,
                        'customer' => [
                            'firstName' => isset($paymentArray['billing_first_name']) ? $paymentArray['billing_first_name'] : '',
                            'lastName' => isset($paymentArray['billing_last_name']) ? $paymentArray['billing_last_name'] : '',
                            'email' => isset($user['email']) ? $user['email'] : ''
                        ],
                        'options' => array(
                            'submitForSettlement' => True
                        )
            ));

            if ($result->success == 1) {
                $transactionId = $result->transaction->id;
                if (!empty($transactionId)) {

                    $dateParam['start_date'] = $this->currentDateTime;
                    $dateParam['user_type'] = $user['user_type'];

                    $this->userRepo->userSubscriptionUpdate(array(
                        'userId' => $params['user_id'],
                        'status' => ACTIVE,
                        'subscription_status' => '1',
                        'no_of_student' => $paymentArray['no_of_students'],
                        'subscription_start_date' => $this->currentDateTime,
                        'subscription_expiry_date' => getSubscriptionExpiryDate($dateParam),
                        'deleted_at' => NULL_DATETIME
                    ));

                    $this->paymentRepo->userPaymentUpdate(array(
                        'id' => $params['id'],
                        'user_id' => $params['user_id'],
                        'payment_date' => $this->currentDateTime,
                        'status' => SUCCESS,
                        'payment_type' => 'Creditcard',
                        'transaction_id' => $transactionId
                    ));
                }
                $data['payment_type'] = 'Creditcard';
                $param['id'] = decryptParam($inputs['payment_id']);
                $invoiceDetail = $this->invoiceRepo->getInvoicePrintList($param)->get()->toArray();
                $invoiceDetails = $invoiceDetail[0];

                /*Welcome message to inbox*/
                $messageRepo->storeMessage(array(
                    'subject' => WELCOME_MESSAGE_SUBJECT,
                    'created_by' => SUPERADMIN_ID,
                    'message' => WELCOME_MESSAGE_MESSAGE,
                    'recipients' => [$params['user_id']],
                ));
                /*End*/
                /* Email for new registration */
                if ($user['user_type'] == SCHOOL) {
                    $emailTemplateId = '14';
                    $emailParam = array(
                        'addressData' => array(
                            'to_email' => $user['email'],
                            'to_name' => $user['username'],
                        ),
                        'userData' => array(
                            'first_name' => $paymentArray['billing_first_name'],
                            'last_name' => $paymentArray['billing_first_name'],
                            'username' => $user['username'],
                            'school_name' => $user['school_name'],
                        //'password' => $basicInfoData['password'],
                        //'school_name' => $basicInfoData['school_name']
                        )
                    );
                    $emailRepo->sendEmail($emailParam, $emailTemplateId);
                }
                if ($user['user_type'] == TUTOR) {
                    $emailTemplateId = '11';
                    $emailParam = array(
                        'addressData' => array(
                            'to_email' => $user['email'],
                            'to_name' => $user['username'],
                        ),
                        'userData' => array(
                            'username' => $user['username'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                        )
                    );
                    $emailRepo->sendEmail($emailParam, $emailTemplateId);
                }


                /* End Email for new registration */
                
                /* Email for payment confirmation */
                $emailParam = array(
                    'addressData' => array(
                        'to_email' => $invoiceDetails['email'],
                        'to_name' => $invoiceDetails['school_name'],
                    ),
                    'userData' => array(
                        'school_name' => ($invoiceDetails['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                        'address' => $invoiceDetails['address'],
                        'city' => $invoiceDetails['city'],
                        'email' => $invoiceDetails['email'],
                        'transaction_id' => (isset($invoiceDetails['transaction_id']) ? 'SC-' . date("Y") . '-' . $invoiceDetails['transaction_id'] : "N/A"),
                        'payment_date' => outputDateFormat($invoiceDetails['payment_date']),
                        'plan_students' => $invoiceDetails['plan_students'],
                        'additional_students' => $invoiceDetails['additional_students'],
                        'plan_amount' => $invoiceDetails['plan_amount'],
                        'additional_amount' => $invoiceDetails['additional_amount'],
                        'discount' => $invoiceDetails['discount_amount'],
                        'total' => $invoiceDetails['amount'],
                        'payment_type' => $invoiceDetails['payment_type'],
                        'invoice_school_name'=> $invoiceDetails['school_name']
                    )
                );
                $emailRepo->sendEmail($emailParam, 38);
                
                
                /* End Email for payment confirmation */
                //return view('front.tutor.thanks', $data);
                return redirect(route('fronttutor.signupconfirm', ['payment_type' => 'Creditcard']));
            } else {
                if ($user['user_type'] == SCHOOL)
                    return redirect(route('frontschool.payment', ['userId' => $userId, 'paymentId' => $paymentId]))->with('error', trans('front/front.payment_fail'));
                else if ($user['user_type'] == TUTOR)
                    return redirect(route('fronttutor.payment', ['userId' => $userId, 'paymentId' => $paymentId]))->with('error', trans('front/front.payment_fail'));

                die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return redirect(route('fronttutor.payment', ['userId' => $userId, 'paymentId' => $paymentId]))->with('error', trans('front/front.payment_fail'));
            die;
        }
        //return view('front.tutor.thanks', $data);
        //die;
    }

    /**
     * Confirmation of school sugnup
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function confirm($type) {
        $data['payment_type'] = $type;

        return view('front.tutor.thanks', $data);
    }

}
