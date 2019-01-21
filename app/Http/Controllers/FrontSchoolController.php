<?php

/**
 * This controller is used for School.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\FeesRepository;
use App\Repositories\InvoiceRepository;
use App\Http\Requests\School\FrontSchoolCreateRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\Schooltype;
use App\Models\Whoyou;
use App\Models\County;
use Carbon\Carbon;
use App\Repositories\EmailRepository;
use App\Models\Howfind;
use Braintree_ClientToken;
use Braintree_Transaction;

/**
 * This controller is used for school.
 * @author     Icreon Tech - dev1.
 */
class FrontSchoolController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * The PaymentRepository instance.
     * @var App\Repositories\PaymentRepository
     */
    protected $paymentRepo;
    protected $feesRepository;
    protected $invoiceRepo;
    protected $currentDateTime;

    /**
     * Create a new SchoolController instance.
     * @param  App\Repositories\UserRepository $userRepo
     * @param  App\Repositories\PaymentRepository $paymentRepo
     * @return void
     */
    public function __construct(UserRepository $userRepo, PaymentRepository $paymentRepo, FeesRepository $feesRepository, InvoiceRepository $invoiceRepo) {
        $this->userRepo = $userRepo;
        $this->paymentRepo = $paymentRepo;
        $this->feesRepository = $feesRepository;
        $this->invoiceRepo = $invoiceRepo;
        $this->currentDateTime = Carbon::now()->toDateTimeString();
        //$this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Show the form for creating a new school.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function create(Request $request) {
        $data['school_type'] = ['' => trans('admin/admin.select_option')] + Schooltype::getSchoolType() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['who_you'] = ['' => trans('admin/admin.select_option')] + Whoyou::getWhoAreYou() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['month'] = monthArray();
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['year'] = creditCardExpiryYear(date('Y'));

        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();

        $perFiveStudentFee = $feeRecord[0]['per_5_student_fee'];
        $signUpFee = $feeRecord[0]['school_sign_up_fee'];
        $data['per_five_student_fee'] = $perFiveStudentFee;
        $data['sign_up_fee'] = $signUpFee;

        $data['status'] = statusArray();
        $data['page_title'] = trans('front/school.signup');
        if (!empty($request->get('error')))
            $data['error_type'] = $request->get('error');
        else
            $data['error_type'] = '';

        $data['user'] = array();
        /* payment error case */
        if (!empty($request->get('userId'))) {
            $id = decryptParam($request->get('userId'));
            $user = $this->userRepo->getSubscribedUser($id)->toArray();
            $data['user'] = $user;
        }

        $data['JsValidator'] = 'App\Http\Requests\School\FrontSchoolCreateRequest';
        return view('front.school.create', $data);
    }

    /**
     * Insert a new the school
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\School\FrontSchoolCreateRequest $request
     * @return Response
     */
    public function Store(FrontSchoolCreateRequest $request, EmailRepository $emailRepo) {
        $inputData = $this->userRepo->getSchoolformData($request->all());
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
        /* make payment */
        $original_amount = $feeRecord[0]['school_sign_up_fee'] + ($feeRecord[0]['per_5_student_fee'] / 5) * $inputData['basic_info']['no_of_student'];
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
          }
          /* add user basic info and billing detail */
        $basicInfoData = $inputData['basic_info'];
        $payment_info = $inputData['basic_info'];

        $basicInfoData['user_type'] = SCHOOL;
        $basicInfoData['subscription_status'] = $paymentResponse['status'] == 'success' ? SUBSCRIBED : UNSUBSCRIBED;


        /* if (!empty($basicInfoData['userId'])) { // record update case in case of transaction fail and user changes the basic information 
          $userId = decryptParam($basicInfoData['userId']);
          $basicInfoData['updated_by'] = $userId;
          $basicInfoData['id'] = $userId;
          $this->userRepo->frontUserUpdate($basicInfoData, $userId);
          } else { */
        $basicInfoData['status'] = DELETED;
        $basicInfoData['deleted_at'] = $this->currentDateTime;
        $userId = $this->userRepo->frontStore($basicInfoData);
        /* $emailTemplateId = '14';
          $emailParam = array(
          'addressData' => array(
          'to_email' => $basicInfoData['email'],
          'to_name' => $basicInfoData['username'],
          ),
          'userData' => array(
          'first_name' => $inputData['payment_info']['billing_first_name'],
          'last_name' => $inputData['payment_info']['billing_last_name'],
          'username' => $basicInfoData['username'],
          'school_name' => $basicInfoData['school_name'],
          //'password' => $basicInfoData['password'],
          //'school_name' => $basicInfoData['school_name']
          )
          ); */

        // }
        /* save transaction */

        $totalNoOfStudent = NO_OF_STUDENTS + $basicInfoData['no_of_student'];
        $paymentData['plan_students'] = NO_OF_STUDENTS;
        $paymentData['additional_students'] = $basicInfoData['no_of_student'];
        $paymentData['userId'] = $userId;
        $paymentData['no_of_student'] = $totalNoOfStudent;
        $paymentData['payment_type'] = $inputData['basic_info']['payment_type'];
        $paymentData['original_amount'] = $original_amount;
        $paymentData['discount_amount'] = $discount_amount;
        $paymentData['voucher_code'] = $voucher_code;
        $paymentData['plan_amount'] = $feeRecord[0]['school_sign_up_fee'];
        $paymentData['additional_amount'] = ($feeRecord[0]['per_5_student_fee'] / 5) * $inputData['basic_info']['no_of_student'];
        $paymentData['upgrade_type'] = 2;
        $paymentData['status'] = 'Pending';
        $paymentId = $this->paymentRepo->saveTransaction($paymentData, $paymentResponse);

        $this->userRepo->userSubscriptionUpdate(array(
            'userId' => $userId,
            'status' => DELETED,
            //'no_of_student' => $totalNoOfStudent,
            'subscription_start_date' => '',
            'subscription_expiry_date' => ''
        ));

        //$emailRepo->sendEmail($emailParam, $emailTemplateId);
        return redirect(route('frontschool.payment', ['userId' => encryptParam($userId), 'paymentId' => encryptParam($paymentId)]));

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
          return redirect(route('frontschool.create', ['error' => 'payment', 'userId' => encryptParam($userId)]))->with('error', trans('front/front.payment_fail'));
          } */
    }

    /**
     * Invoice Payment 
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function confirm($type, $userId, $invoice_id, EmailRepository $emailRepo) {

        $params['user_id'] = decryptParam($userId);
        $invoice_id = decryptParam($invoice_id);

        if (isset($type) && $type == 'Invoiced') {
            $userParam['id'] = $params['user_id'];
            $userArray = $this->userRepo->getFrontUser($userParam)->get()->first()->toArray();
            // asd($userArray);
            if (!empty($userArray)) {
                $this->userRepo->userSubscriptionUpdate(array(
                    'userId' => $params['user_id'],
                    'status' => INACTIVE,
                    'deleted_at' => NULL_DATETIME
                ));
            }
        }

        $params['type'] = SCHOOL;
        $data['payment_type'] = $type;

        $emailTemplateId2 = '41'; //Invoice Payment Reminder


       // asd($invoiceDetails);
        $updateParam['transaction_id'] = $invoice_id;
        $updateParam['payment_type'] = 'Invoiced';
        $this->paymentRepo->updateTransactionNo($updateParam);
        $invoiceDetail = $this->invoiceRepo->getInvoicePrintList($params)->get()->toArray();
        $invoiceDetails = $invoiceDetail[0];
        
        
        
        /* Email for new registration */
        if ($userArray['user_type'] == SCHOOL) {
            $emailTemplateId = '14';
            $emailParam = array(
                'addressData' => array(
                    'to_email' => $userArray['email'],
                    'to_name' => $userArray['username'],
                ),
                'userData' => array(
                    'first_name' => $invoiceDetails['billing_first_name'],
                    'last_name' => $invoiceDetails['billing_first_name'],
                    'username' => $userArray['username'],
                    'school_name' => $invoiceDetails['school_name'],
                //'password' => $basicInfoData['password'],
                //'school_name' => $basicInfoData['school_name']
                )
            );
            $emailRepo->sendEmail($emailParam, $emailTemplateId);
        }        
        
        
        $emailParam = array(
            'addressData' => array(
                'to_email' => $invoiceDetails['email'],
                'to_name' => (isset($invoiceDetails['school_name']) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name']),
            ),
            'userData' => array(
                'username' => (isset($invoiceDetails['school_name']) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name']),
                'name' => (isset($invoiceDetails['school_name']) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name']),
            )
        );
        $emailParam2 = array(
            'addressData' => array(
                'to_email' => $invoiceDetails['email'],
                'to_name' => $invoiceDetails['school_name'],
                'cc_email' => env('ADMIN_CC_EMAIL'),
                'cc_name' => env('ADMIN_CC_NAME'),
            ),
            'userData' => array(
                'school_name' => ($invoiceDetails['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                'address' => $invoiceDetails['address'],
                'city' => $invoiceDetails['city'],
                'email' => $invoiceDetails['email'],
                'transaction_id' => (isset($invoiceDetails['transaction_id']) ? 'SC-' . date("Y") . '-' . $invoiceDetails['transaction_id'] : "N/A"),
                'payment_date' => (($invoiceDetails['payment_date'] != NULL_DATETIME) ? outputDateFormat($invoiceDetails['payment_date']) : outputDateFormat($this->currentDateTime)),
                'plan_students' => $invoiceDetails['plan_students'],
                'additional_students' => $invoiceDetails['additional_students'],
                'plan_amount' => $invoiceDetails['plan_amount'],
                'additional_amount' => $invoiceDetails['additional_amount'],
                'discount' => $invoiceDetails['discount_amount'],
                'total' => $invoiceDetails['amount'],
                'payment_type' => $invoiceDetails['payment_type'],
                'heading' => $invoiceDetails['payment_type']
            )
        );
        
        $emailRepo->sendEmail($emailParam2, 38);
        $emailRepo->sendEmail($emailParam, $emailTemplateId2);
        return view('front.tutor.thanks', $data);
    }

}
