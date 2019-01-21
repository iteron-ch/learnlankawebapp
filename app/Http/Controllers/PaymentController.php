<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PaymentRepository;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Datatables;
use App\Http\Requests\Payment\PaymentRequest;
use DB;
use Carbon\Carbon;
use App\Repositories\MessageRepository;

class PaymentController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * The UserRepository instance.
     *
     * @var App\Repositories\UserRepository
     */
    protected $paymentRepository;
    protected $currentDateTime;

    /**
     * Create a new UserController instance.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @param  App\Repositories\RoleRepository $role_gestion
     * @return void
     */
    public function __construct(PaymentRepository $paymentRepository, UserRepository $userRepo) {
        $this->paymentRepository = $paymentRepository;
        $this->userRepo = $userRepo;
        $this->currentDateTime = Carbon::now()->toDateTimeString();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $data = array();
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        return view('admin.payment.userlist', $data);
    }

    /**
     * Display a listing of the Paymen records.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function listRecord(Request $request) {
        $param = array();
        $param['status'] = 'Pending';
        // if ($request->has('isLimited'))
        //   $param['limit'] = DASHBOARD_RECORD_LIMIT;
        $users = $this->paymentRepository->getUserPaymentList($param);
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
                            $action = '<a href="javascript:void(0);" data-remote="' . route('payment.show', encryptParam($user->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>';
                            if ($user->payment_status != SUCCESS) {
                                // $action.= $user->id.'<a href="' . route('tutor.updatepaymentstatus', encryptParam($user->id)) . '">View Payments</a>';
                            }
                            return $action;
                        })
                        ->editColumn('no_of_students', function ($user) {
                            return '<a href="' . route('student.index', ['id' => encryptParam($user->id)]) . '" class="">' . $user->no_of_students . '</a>';
                        })
                        ->editColumn('user_type', function ($user) {
                            if ($user->user_type == SCHOOL)
                                return 'School';
                            else if ($user->user_type == TUTOR)
                                return 'Tutor/Parent';
                        })
                        ->editColumn('created_at', function ($user) {
                            return $user->created_at ? outputDateFormat($user->created_at) : '';
                        })
                        ->editColumn('name', function ($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->editColumn('updated_at', function ($user) {
                            return $user->updated_at ? outputDateFormat($user->updated_at) : '';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PaymentmethodSaveRequest $request) {
        // $this->paymentmethodRepository->store($request->all());
        //return redirect('cmspage')->with('ok', trans('admin/cmspage.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $data = array();
        $param = array();
        $param['user_id'] = decryptParam($id);
        $payment = $this->userRepo->getPaymentDetails($param);
        $data['payment'] = $payment;
        return view('admin.payment.updatepaymentstatus')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $title = trans('admin/paymentmethod.template_heading');
        return view('admin.paymentmethod.paymentmethod', array_merge($this->paymentmethodRepository->edit($id), compact('title')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PaymentRequest $request, $id, MessageRepository $messageRepo) {
        $inputs = $request->all();

        $inputs['updated_by'] = session()->get('user')['id'];

        $title = trans('admin/paymentmethod.template_heading');
        $ok = trans('admin/paymentmethod.success_msg');
        $upgrade_type = $inputs['upgrade_type'];


        //if ($inputs['upgrade_type'] == 3)
        //        $inputs['upgrade_type'] = 1;
        $payment = $this->paymentRepository->update($inputs, $id);
        $userId = $payment['user_id'];
        $user = User::findOrFail($userId)->toArray();

        $updateData = array(
            'userId' => $userId,
            'status' => ACTIVE,
            'no_of_student' => $payment['no_of_students']
        );
        if ($upgrade_type == 2) {
            /* Welcome message to inbox */
            $messageRepo->storeMessage(array(
                'subject' => WELCOME_MESSAGE_SUBJECT,
                'created_by' => SUPERADMIN_ID,
                'message' => WELCOME_MESSAGE_MESSAGE,
                'recipients' => [$user['id']],
            ));
            /* End */
        }

        if ($upgrade_type == 3) { // $upgrade_type is used to renew subscription
            $subscription_expiry_date = (isset($user['subscription_expiry_date']) && $user['subscription_expiry_date'] != NULL_DATETIME) ? $user['subscription_expiry_date'] : '';

            if (!empty($subscription_expiry_date)) {
                $date = strtotime($subscription_expiry_date);
                $new_date = strtotime('+ 1 year', $date);
                $endDate = date('Y-m-d', $new_date);
                $updateData['subscription_expiry_date'] = $endDate;
            }
        } else {
            if ($user['subscription_start_date'] == NULL_DATETIME) {
                $dateParam['start_date'] = $this->currentDateTime;
                $dateParam['user_type'] = $user['user_type'];
                $updateData['subscription_start_date'] = $this->currentDateTime;
                $updateData['subscription_expiry_date'] = getSubscriptionExpiryDate($dateParam);
            }
        }
        $this->userRepo->userSubscriptionUpdate($updateData);
        return redirect(route('payment.show'))->with('ok', 'Payment status has been successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
