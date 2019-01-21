<?php

/**
 * This controller is used for School.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\EmailRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\FeesRepository;
use App\Repositories\InvoiceRepository;
use App\Http\Requests\School\SchoolCreateRequest;
use App\Http\Requests\School\UpdateSubscriptionRequest;
use App\Http\Requests\School\SchoolUpdateRequest;
use App\Http\Requests\School\SchoolUpdateProfileRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\City;
use App\Models\User;
use App\Models\Howfind;
use App\Models\Schooltype;
use App\Models\Whoyou;
use App\Models\County;
use Datatables;
use Braintree_ClientToken;
use Braintree_Transaction;
use Carbon\Carbon;

/**
 * This controller is used for school.
 * @author     Icreon Tech - dev1.
 */
class SchoolController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;
    protected $paymentRepo;
    protected $feesRepository;
    protected $city;
    protected $invoiceRepo;
    protected $currentDateTime;

    /**
     * Create a new SchoolController instance.
     * @param  App\Repositories\UserRepository $user
     * @return void
     */
    public function __construct(UserRepository $userRepo, PaymentRepository $paymentRepo, FeesRepository $feesRepository, City $city, EmailRepository $emailRepo, InvoiceRepository $invoiceRepo) {
        $this->userRepo = $userRepo;
        $this->paymentRepo = $paymentRepo;
        $this->feesRepository = $feesRepository;
        $this->city = $city;
        $this->emailRepo = $emailRepo;
        $this->invoiceRepo = $invoiceRepo;
        $this->currentDateTime = Carbon::now()->toDateTimeString();
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Display school listing page.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function index() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        return view('admin.school.schoollist', $data);
    }

    /**
     * Get record for school list
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $param['user_type'] = SCHOOL;
        if ($request->has('isLimited'))
            $param['limit'] = DASHBOARD_RECORD_LIMIT;
        $users = $this->userRepo->getUserList($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('school_name')) {
                                $query->where('school_name', 'like', "%{$request->get('school_name')}%");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('users.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($user) {
                            $actions = '<div class="btn-group">';

                            $actions .='<a href="javascript:void(0);" data-remote="' . route('school.show', encryptParam($user->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('school.edit', encryptParam($user->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    </div>';
                            if ($user['status'] == 'Active') {
                                $actions .='&nbsp;<a href="' . route('user.upgradeaccount', encryptParam($user->id)) . '" alt="Upgrade Subscription" title="Upgrade Subscription"><i class="glyphicon glyphicon-equalizer"></i></a>
                                    </div>';
                                $actions .=' <a href="' . route('user.renewaccount', encryptParam($user->id)) . '" alt="Renew Subscription" title="Renew Subscription"><i class="glyphicon glyphicon-gift"></i></a>
                                    </div>';
                            }
                            $actions .= '<a href="javascript:void(0);" data-id="' . encryptParam($user->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>
                                    <a href="/adminreport/schooloverview?id=' . $user->id . '&report=dashboard&tab=math" alt="View Report" title="View Report"><i class="glyphicon glyphicon-stats"></i></a></div>
                                    </div>';

                            return $actions;
                        })
                        ->editColumn('no_of_students', function ($user) {
                            if (!empty($user->no_of_students))
                                $no_of_students = $user->no_of_students;
                            else
                                $no_of_students = '0';
                            return '<a href="' . route('student.index', ['id' => encryptParam($user->id)]) . '" class="">' . $no_of_students . '</a>';
                        })
                        ->editColumn('no_of_teachers', function ($user) {
                            if (!empty($user->no_of_teachers))
                                $no_of_teachers = $user->no_of_teachers;
                            else
                                $no_of_teachers = '0';
                            return '<a href="' . route('teacher.index', ['id' => encryptParam($user->id)]) . '" class="">' . $no_of_teachers . '</a>';
                        })->editColumn('created_at', function ($user) {
                            return $user->created_at ? outputDateFormat($user->created_at) : '';
                        })
                        ->editColumn('updated_at', function ($user) {
                            return $user->updated_at ? outputDateFormat($user->updated_at) : '';
                        })
                        ->editColumn('subscription_expiry_date', function ($user) {
                            return ($user->subscription_expiry_date && $user->subscription_expiry_date != NULL_DATETIME) ? outputDateFormat($user->subscription_expiry_date) : 'NA';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new school.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function create() {
        $data['school_type'] = ['' => trans('admin/admin.select_option')] + Schooltype::getSchoolType() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['who_you'] = ['' => trans('admin/admin.select_option')] + Whoyou::getWhoAreYou() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['status'] = statusArray();
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        //$data['cities'] = ['' => trans('admin/admin.select_option')] + City::getCity();
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['page_heading'] = trans('admin/school.manage_school');
        $data['page_title'] = trans('admin/school.add_school');
        $data['trait'] = array('trait_1' => trans('admin/school.school'), 'trait_1_link' => route('school.index'), 'trait_2' => trans('admin/school.add_school'));
        $data['JsValidator'] = 'App\Http\Requests\School\SchoolCreateRequest';
        return view('admin.school.create', $data);
    }

    /**
     * Insert a new the school
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\School\SchoolCreateRequest $request
     * @return Response
     */
    public function store(SchoolCreateRequest $request, EmailRepository $emailRepo) {
        $inputs = $request->all();
        // checking file is valid.
        if ($request->file('image')) {
            $inputs['image'] = $this->userRepo->userImageUpload($request->file('image'));
        }
        $inputs['user_type'] = SCHOOL;
        $inputs['status'] = INACTIVE;
        $inputs['created_by'] = session()->get('user')['id'];
        $lastId = $this->userRepo->store($inputs);
        if ($lastId) {
            /* update subscription status */
            $this->userRepo->updatePayment($inputs, $lastId);
            //send confirmation email
            $emailParam = array(
                'addressData' => array(
                    'to_email' => $inputs['email'],
                    'to_name' => $inputs['username'],
                ),
                'userData' => array(
                    'username' => $inputs['username'],
                    'first_name' => $inputs['school_name'],
                    'last_name' => '',
                    'school_name' => $inputs['school_name']
                )
            );
            $emailRepo->sendEmail($emailParam, 14);
            return redirect(route('school.index'))->with('ok', trans('admin/school.added_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Show the school detail
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $status = statusArray();
        $user = $this->userRepo->showSchool(decryptParam($id));
        //asd($user);
        return view('admin.school.show', compact('user', 'status'));
    }

    /**
     * Show the form for edit school.
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $user = User::findOrFail($id)->toArray();
        $data['user'] = $user;
        $data['school_type'] = ['' => trans('admin/admin.select_option')] + Schooltype::getSchoolType() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['who_you'] = ['' => trans('admin/admin.select_option')] + Whoyou::getWhoAreYou() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['status'] = statusArray();
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['page_heading'] = trans('admin/school.manage_school');
        $data['page_title'] = trans('admin/school.edit_school');
        $data['trait'] = array('trait_1' => trans('admin/school.school'), 'trait_1_link' => route('school.index'), 'trait_2' => trans('admin/school.edit_school'));
        $data['JsValidator'] = 'App\Http\Requests\School\SchoolUpdateRequest';
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        return view('admin.school.edit')->with($data);
    }

    /**
     * Update the school.
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\School\SchoolUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(SchoolUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['user_type'] = SCHOOL; /* School */
        $userDeleteImage = FALSE;
        if (isset($inputs['image']) && empty($inputs['image'])) {
            $userDeleteImage = TRUE;
        }

        if ($request->file('image')) {
            $userDeleteImage = TRUE;
            $inputs['image'] = $this->userRepo->userImageUpload($request->file('image'));
        }
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        if ($inputs['howfinds_id'] != '-1')
            $inputs['howfinds_other'] = '';
        if ($this->userRepo->update($inputs, $id, $userDeleteImage)) {
            return redirect(route('school.index'))->with('ok', trans('admin/school.updated_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Delete a school 
     * @author     Icreon Tech - dev2.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['status'] = DELETED;
        $inputs['id'] = $id;
        $this->userRepo->destroyUser($inputs, $id);
        return response()->json();
    }

    /**
     * Display school's edit profile form
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function editProfile() {
        $id = session()->get('user')['id'];
        $user = User::findOrFail($id)->toArray();
        $data['user'] = $user;
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['school_type'] = ['' => trans('admin/admin.select_option')] + Schooltype::getSchoolType() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['who_you'] = ['' => trans('admin/admin.select_option')] + Whoyou::getWhoAreYou() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        return view('admin.school.editprofile')->with($data);
    }

    /**
     * Update school's profile
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Requests\School\SchoolUpdateProfileRequest $request
     * @return Response
     */
    public function updateProfile(SchoolUpdateProfileRequest $request) {
        $inputs = $request->all();
        $userDeleteImage = FALSE;
        if (isset($inputs['image']) && empty($inputs['image'])) {
            $userDeleteImage = TRUE;
        }
        if ($request->file('image')) {
            $userDeleteImage = TRUE;
            $inputs['image'] = $this->userRepo->userImageUpload($request->file('image'));
        }
        $id = session()->get('user')['id'];
        $inputs['updated_by'] = $id;
        $inputs['id'] = $id;
        if ($this->userRepo->updateSchoolProfile($inputs, $id, $userDeleteImage)) {
            return redirect(route('manageaccount'))->with('ok', trans('admin/admin.profile_updated_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Display school listing page.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function schooladmin() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        return view('admin.school.adminlist', $data);
    }

    /**
     * Get record for school admin list
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function schooladminlist(Request $request) {
        $param['user_type'] = TEACHER;
        $users = $this->userRepo->getUserList($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {

                            if ($request->has('name')) {
                                $query->where(function ($query) use ($request) {
                                    $query->where('first_name', 'like', "%{$request->get('name')}%")
                                    ->orwhere('last_name', 'like', "%{$request->get('name')}%")
                                    ->orwhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$request->get('name')}%");
                                });
                            }
                            if ($request->has('username')) {
                                $query->where('username', 'like', "%{$request->get('username')}%");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('users.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($user) {
                            return '<a href="javascript:void(0);" data-remote="' . route('teacher.show', encryptParam($user->id)) . '" class="view_row btn default btn-xs green-stripe">View </a>
                                        <a href="' . route('teacher.edit', encryptParam($user->id)) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> ' . trans('admin/admin.edit') . '</a>
                                        <a href="javascript:void(0);" data-id="' . encryptParam($user->id) . '" class="delete_row btn default btn-xs red"><i class="glyphicon glyphicon-edit"></i> ' . trans('admin/admin.delete') . '</a>';
                        })
                        ->editColumn('created_at', function ($user) {
                            return $user->created_at ? outputDateFormat($user->created_at) : '';
                        })
                        ->editColumn('name', function ($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->make(true);
    }

    /**
     * Get record for update the user subscription
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function upgradeAccount($id) {
        $data = array();
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['month'] = monthArray();
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['year'] = creditCardExpiryYear(date('Y'));
        $id = decryptParam($id);
        $user = User::findOrFail($id)->toArray();
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->first()->toArray();

        $clientToken = Braintree_ClientToken::generate();
        $data['clientToken'] = $clientToken;
        $data['feeRecord'] = $feeRecord;

        $data['no_of_subscription'] = $user['total_number_of_student'];
        $data['user'] = $user;
        if ($user['user_type'] == SCHOOL) {
            $data['no_of_student_slab'] = noOfStudentSlabForSchool();
        } else if ($user['user_type'] == TUTOR) {
            $data['no_of_student_slab'] = noOfStudentSlabForTutor();
        }
        $data['JsValidator'] = 'App\Http\Requests\School\UpdateSubscriptionRequest';
        return view('admin.school.upgradeaccount', $data);
    }

    /**
     * Get record for update the user subscription
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function renewaccount($id) {
        $data = array();
        $id = decryptParam($id);
        $user = User::findOrFail($id)->toArray();
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->first()->toArray();

        $clientToken = Braintree_ClientToken::generate();
        $data['clientToken'] = $clientToken;
        $data['feeRecord'] = $feeRecord;
        $data['user'] = $user;

        $data['JsValidator'] = 'App\Http\Requests\School\UpdateSubscriptionRequest';
        return view('admin.school.renewaccount', $data);
    }

    /**
     * Get record for update the user subscription
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function userupgradeAccount(UpdateSubscriptionRequest $request, $id, EmailRepository $emailRepo) {
        $userId = decryptParam($id);
        $totalNoOfStudent = $request->get('no_of_student');
        $paymentMethod = $request->get('payment_method');
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
        $user = User::findOrFail($userId)->toArray();

        if ($user['user_type'] == TUTOR) {
            $original_amount = $feeRecord[0]['per_student_fee'] * $request->get('no_of_student');
        } else if ($user['user_type'] == SCHOOL) {
            $original_amount = ($feeRecord[0]['per_5_student_fee'] / 5) * $request->get('no_of_student');
        }
        $amount = $original_amount;
        if ($paymentMethod == "Invoiced") {
            $paymentResponse['status'] = 'success';
        }
        if ($paymentResponse['status'] == 'success') {
            $paymentData['userId'] = $userId;
            $paymentData['status'] = 'success';
            $paymentData['no_of_student'] = $totalNoOfStudent;
            $paymentData['additional_students'] = $totalNoOfStudent;
            $paymentData['payment_type'] = $paymentMethod;
            $paymentData['additional_amount'] = $original_amount;
            $paymentData['original_amount'] = $original_amount;
            $paymentData['amount'] = $amount;
            $paymentData['upgrade_type'] = 1;
            // $this->paymentRepo->saveTransaction($paymentData);
            $lastId = $this->paymentRepo->saveTransaction($paymentData);
            $params['last_id'] = $lastId;
            $params['payment_type'] = 'Invoice';
            $invoiceDetail = $this->invoiceRepo->getInvoicePrintList($params)->get()->toArray();
            $invoiceDetails = $invoiceDetail[0];
            //asd($invoiceDetails);

            $emailParam = array(
                'addressData' => array(
                    'to_email' => session()->get('user')['email'],
                    'to_name' => session()->get('user')['username'],
                ),
                'userData' => array(
                    'username' => session()->get('user')['username'],
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
                    'school_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
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
                    'heading' => $invoiceDetails['payment_type'],
                )
            );
            $emailRepo->sendEmail($emailParam2, 38);
            $emailRepo->sendEmail($emailParam, 22);


            return redirect(route('user.upgradeaccount', $id))->with('ok', 'Subscription has been successfully updated.');
        } else {
            return redirect(route('user.upgradeaccount', $id))->with('error', trans('front/front.payment_fail'));
        }
    }

    public function getcities() {
        $keyword = $_POST['keyword'] . '%';
        $cities = City::getCityList($keyword);
        foreach ($cities as $rs) {
            $city_names = str_replace($_POST['keyword'], '<b>' . $_POST['keyword'] . '</b>', $rs);
            echo '<li style="list-style-type:none; border-bottom:1px solid #eeeeee; padding-top:5px; padding-bottom:5px; padding-left:10px; cursor:pointer;" onclick="set_city(\'' . str_replace("'", "\'", $rs) . '\')">' . $city_names . '</li>';
        }
    }

    public function updateSubscriptionMakePayment(Request $request, EmailRepository $emailRepo) {
        $paymentMethodNonce = $request->input('payment_method_nonce');
        $userId = decryptParam($request->get('user_id'));
        $noOfStudents = $request->get('no_of_students');
        $user = User::findOrFail($userId)->toArray();
        //asd($user);
        $user_type = $user['user_type'];
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();

        if ($user_type == TUTOR) {
            $original_amount = $feeRecord[0]['per_student_fee'] * $noOfStudents;
        } else if ($user_type == SCHOOL) {
            $original_amount = ($feeRecord[0]['per_5_student_fee'] / 5) * $noOfStudents;
        }
        $customerArray = array();
        if ($user['user_type'] == SCHOOL) {
            $customerArray['firstName'] = isset($user['school_name']) ? $user['school_name'] : '';
            $customerArray['email'] = isset($user['email']) ? $user['email'] : '';
        } else {
            $customerArray['firstName'] = isset($user['first_name']) ? $user['last_name'] : '';
            $customerArray['email'] = isset($user['email']) ? $user['email'] : '';
        }

        try {
            $result = Braintree_Transaction::sale(array(
                        'amount' => $original_amount,
                        'paymentMethodNonce' => $paymentMethodNonce,
                        'customer' => $customerArray,
                        'options' => array(
                            'submitForSettlement' => True
                        )
            ));

            if ($result->success == 1) {
                $transactionId = $result->transaction->id;
                if (!empty($transactionId)) {

                    $paymentData['userId'] = $userId;
                    $paymentData['no_of_student'] = $noOfStudents;
                    $paymentData['additional_students'] = $noOfStudents;
                    $paymentData['payment_type'] = 'Creditcard';
                    $paymentData['additional_amount'] = $original_amount;
                    $paymentData['original_amount'] = $original_amount;
                    $paymentData['amount'] = $original_amount;
                    $paymentData['upgrade_type'] = 1;
                    $paymentData['status'] = 'success';
                    $paymentData['transaction_id'] = $transactionId;
                    $paymentData['payment_date'] = $this->currentDateTime;
                    $lastId = $this->paymentRepo->saveTransaction($paymentData);

                    $this->userRepo->userSubscriptionUpdate(array(
                        'userId' => $userId,
                        'no_of_student' => $noOfStudents,
                    ));
                }
                $params['id'] = $lastId;
                $invoiceDetail = $this->invoiceRepo->getInvoicePrintList($params)->get()->toArray();
                $invoiceDetails = $invoiceDetail[0];
                $data['payment_type'] = 'Creditcard';
                //return view('front.tutor.thanks', $data);
                $emailParam = array(
                    'addressData' => array(
                        'to_email' => session()->get('user')['email'],
                        'to_name' => session()->get('user')['username'],
                    ),
                    'userData' => array(
                        'username' => session()->get('user')['username'],
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
                        'school_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                        'address' => $invoiceDetails['address'],
                        'city' => $invoiceDetails['city'],
                        'email' => $invoiceDetails['email'],
                        'year' => date("Y"),
                        'transaction_id' => (isset($invoiceDetails['transaction_id']) ? 'SC-' . date("Y") . '-' . $invoiceDetails['transaction_id'] : "N/A"),
                        'payment_date' => (($invoiceDetails['payment_date'] != NULL_DATETIME) ? outputDateFormat($invoiceDetails['payment_date']) : outputDateFormat($this->currentDateTime)),
                        'plan_students' => $invoiceDetails['plan_students'],
                        'additional_students' => $invoiceDetails['additional_students'],
                        'plan_amount' => $invoiceDetails['plan_amount'],
                        'additional_amount' => $invoiceDetails['additional_amount'],
                        'discount' => $invoiceDetails['discount_amount'],
                        'total' => $invoiceDetails['amount'],
                        'payment_type' => $invoiceDetails['payment_type'],
                        'heading' => $invoiceDetails['payment_type'],
                    )
                );
                $emailRepo->sendEmail($emailParam, 23);
                $emailRepo->sendEmail($emailParam2, 38);
                return redirect(route('user.upgradeaccount', $request->get('user_id')))->with('ok', 'Subscription has been successfully updated.');
            } else {
                return redirect(route('user.upgradeaccount', ['userId' => $request->get('user_id')]))->with('error', trans('front/front.payment_fail'));
                die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return redirect(route('user.upgradeaccount', ['userId' => $request->get('user_id'), 'paymentId' => $paymentId]))->with('error', trans('front/front.payment_fail'));
            die;
        }
        //return view('front.tutor.thanks', $data);
        //die;
    }

    public function renewSubscriptionMakePayment(Request $request, EmailRepository $emailRepo) {
        $paymentMethodNonce = $request->input('payment_method_nonce');
        $userId = decryptParam($request->get('user_id'));
        $noOfStudents = $request->get('no_of_students');
        $user = User::findOrFail($userId)->toArray();
        //asd($user);
        $user_type = $user['user_type'];
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();

        if ($user_type == TUTOR) {
            $original_amount = $feeRecord[0]['parent_sign_up_fee'];
        } else if ($user_type == SCHOOL) {
            $original_amount = $feeRecord[0]['school_sign_up_fee'];
        }
        $customerArray = array();
        if ($user['user_type'] == SCHOOL) {
            $customerArray['firstName'] = isset($user['school_name']) ? $user['school_name'] : '';
            $customerArray['email'] = isset($user['email']) ? $user['email'] : '';
        } else {
            $customerArray['firstName'] = isset($user['first_name']) ? $user['last_name'] : '';
            $customerArray['email'] = isset($user['email']) ? $user['email'] : '';
        }

        try {
            $result = Braintree_Transaction::sale(array(
                        'amount' => $original_amount,
                        'paymentMethodNonce' => $paymentMethodNonce,
                        'customer' => $customerArray,
                        'options' => array(
                            'submitForSettlement' => True
                        )
            ));
            if ($result->success == 1) {
                $transactionId = $result->transaction->id;
                if (!empty($transactionId)) {

                    $paymentData['userId'] = $userId;
                    $paymentData['no_of_student'] = 0;
                    $paymentData['additional_students'] = 0;
                    $paymentData['payment_type'] = 'Creditcard';
                    $paymentData['plan_amount'] = $original_amount;
                    $paymentData['original_amount'] = $original_amount;
                    $paymentData['amount'] = $original_amount;
                    $paymentData['upgrade_type'] = 3;
                    $paymentData['status'] = 'success';
                    $paymentData['transaction_id'] = $transactionId;
                    $paymentData['payment_date'] = $this->currentDateTime;
                    $lastId = $this->paymentRepo->saveTransaction($paymentData);

                    $this->userRepo->renewUserSubscription(array(
                        'userId' => $userId,
                        'status' => ACTIVE,
                        'subscription_status' => 1,
                        'subscription_expiry_date' => (isset($user['subscription_expiry_date']) && $user['subscription_expiry_date'] != NULL_DATETIME) ? $user['subscription_expiry_date'] : ''
                    ));
                    /* $this->userRepo->userSubscriptionUpdate(array(
                      'userId' => $userId,
                      'no_of_student' => $noOfStudents,
                      )); */
                }
                $params['id'] = $lastId;
                $invoiceDetail = $this->invoiceRepo->getInvoicePrintList($params)->get()->toArray();
                $invoiceDetails = $invoiceDetail[0];
                $data['payment_type'] = 'Creditcard';
                //return view('front.tutor.thanks', $data);
                $emailParam = array(
                    'addressData' => array(
                        'to_email' => $invoiceDetails['email'],
                        'to_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                    ),
                    'userData' => array(
                        'username' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                    )
                );
                $emailParam2 = array(
                    'addressData' => array(
                        'to_email' => $invoiceDetails['email'],
                        'to_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                        'cc_email' => env('ADMIN_CC_EMAIL'),
                        'cc_name' => env('ADMIN_CC_NAME'),
                    ),
                    'userData' => array(
                        'school_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                        'address' => $invoiceDetails['address'],
                        'city' => $invoiceDetails['city'],
                        'email' => $invoiceDetails['email'],
                        'year' => date("Y"),
                        'transaction_id' => (isset($invoiceDetails['transaction_id']) ? 'SC-' . date("Y") . '-' . $invoiceDetails['transaction_id'] : "N/A"),
                        'payment_date' => (($invoiceDetails['payment_date'] != NULL_DATETIME) ? outputDateFormat($invoiceDetails['payment_date']) : outputDateFormat($this->currentDateTime)),
                        'plan_students' => $invoiceDetails['plan_students'],
                        'additional_students' => $invoiceDetails['additional_students'],
                        'plan_amount' => $invoiceDetails['plan_amount'],
                        'additional_amount' => $invoiceDetails['additional_amount'],
                        'discount' => $invoiceDetails['discount_amount'],
                        'total' => $invoiceDetails['amount'],
                        'payment_type' => $invoiceDetails['payment_type']
                    )
                );
                $emailRepo->sendEmail($emailParam, 23);
                $emailRepo->sendEmail($emailParam2, 42);
                return redirect(route('user.renewaccount', $request->get('user_id')))->with('ok', 'Subscription has been successfully updated.');
            } else {
                return redirect(route('user.renewaccount', ['userId' => $request->get('user_id')]))->with('error', trans('front/front.payment_fail'));
                die;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            return redirect(route('user.renewaccount', ['userId' => $request->get('user_id'), 'paymentId' => $paymentId]))->with('error', trans('front/front.payment_fail'));
            die;
        }
        //return view('front.tutor.thanks', $data);
        //die;
    }

    /**
     * Get record for update the user subscription
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function userrenewAccount(Request $request, $id, EmailRepository $emailRepo) {
        $userId = decryptParam($id);
        $totalNoOfStudent = 0;
        $paymentMethod = $request->get('payment_method');
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
        $user = User::findOrFail($userId)->toArray();
        $user_type = $user['user_type'];
        if ($user_type == TUTOR) {
            $original_amount = $feeRecord[0]['parent_sign_up_fee'];
        } else if ($user_type == SCHOOL) {
            $original_amount = $feeRecord[0]['school_sign_up_fee'];
        }
        $amount = $original_amount;
        if ($paymentMethod == "Invoiced") {
            $paymentResponse['status'] = 'success';
        }
        if ($paymentResponse['status'] == 'success') {
            $paymentData['userId'] = $userId;
            $paymentData['status'] = 'success';
            $paymentData['no_of_student'] = $totalNoOfStudent;
            $paymentData['additional_students'] = $totalNoOfStudent;
            $paymentData['payment_type'] = $paymentMethod;
            $paymentData['plan_amount'] = $original_amount;
            $paymentData['additional_amount'] = 0;
            $paymentData['original_amount'] = $original_amount;
            $paymentData['amount'] = $amount;
            $paymentData['upgrade_type'] = 3;

            $lastId = $this->paymentRepo->saveTransaction($paymentData);
            $params['last_id'] = $lastId;
            $invoiceDetail = $this->invoiceRepo->getInvoicePrintList($params)->get()->toArray();
            $invoiceDetails = $invoiceDetail[0];
            $emailParam = array(
                'addressData' => array(
                    'to_email' => $invoiceDetails['email'],
                    'to_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                ),
                'userData' => array(
                    'username' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                )
            );
            $emailParam2 = array(
                'addressData' => array(
                    'to_email' => $invoiceDetails['email'],
                    'to_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
                    'cc_email' => env('ADMIN_CC_EMAIL'),
                    'cc_name' => env('ADMIN_CC_NAME'),
                ),
                'userData' => array(
                    'school_name' => ($user['user_type'] == SCHOOL) ? $invoiceDetails['school_name'] : $invoiceDetails['first_name'] . ' ' . $invoiceDetails['last_name'],
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
                    'payment_type' => $invoiceDetails['payment_type']
                )
            );
            $emailRepo->sendEmail($emailParam, 22);
            $emailRepo->sendEmail($emailParam2, 42);
            return redirect(route('user.renewaccount', $id))->with('ok', 'Subscription has been successfully updated.');
        } else {
            return redirect(route('user.renewaccount', $id))->with('error', trans('front/front.payment_fail'));
        }
    }

}
