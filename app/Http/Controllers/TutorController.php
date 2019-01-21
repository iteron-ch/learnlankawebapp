<?php

/**
 * This controller is used for Tutor.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\EmailRepository;
use App\Http\Requests\Tutor\TutorCreateRequest;
use App\Http\Requests\Tutor\TutorUpdateRequest;
use App\Http\Requests\Tutor\TutorUpdateProfileRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\Howfind;
use App\Models\County;
use DB;
use Datatables;

/**
 * This controller is used for tutor.
 * @author     Icreon Tech - dev1.
 */
class TutorController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * Create a new TutorController instance.
     * @param  App\Repositories\UserRepository $userRepo
     * @return void
     */
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Display tutor/parent listing page.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function index() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        return view('admin.tutor.tutorlist', $data);
    }

    /**
     * Get record for tutor/parent list
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $param['user_type'] = TUTOR;
        if ($request->has('isLimited'))
            $param['limit'] = DASHBOARD_RECORD_LIMIT;
        $users = $this->userRepo->getUserList($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                            /* if ($request->has('name')) {
                              $query->where(function ($query) use ($request) {
                              $query->where('first_name', 'like', "%{$request->get('name')}%")
                              ->orwhere('last_name', 'like', "%{$request->get('name')}%")
                              ->orwhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$request->get('name')}%");
                              });
                              } */
                            if ($request->has('first_name')) {
                                $query->where('first_name', 'like', "%{$request->get('first_name')}%");
                            }
                            if ($request->has('last_name')) {
                                $query->where('last_name', 'like', "%{$request->get('last_name')}%");
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
                            $actions = '<div class="btn-group">';

                            $actions .='<a href="javascript:void(0);" data-remote="' . route('tutor.show', encryptParam($user->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('tutor.edit', encryptParam($user->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    </div>';
                            if ($user['status'] == 'Active') {
                                $actions .=' <a href="' . route('user.upgradeaccount', encryptParam($user->id)) . '" alt="Upgrade Subscription" title="Upgrade Subscription"><i class="glyphicon glyphicon-equalizer"></i></a>
                                    </div>';
                                
                                $actions .=' <a href="' . route('user.renewaccount', encryptParam($user->id)) . '" alt="Renew Subscription" title="Renew Subscription"><i class="glyphicon glyphicon-gift"></i></a>
                                    </div>';
                            }
                            $actions .= '<a href="javascript:void(0);" data-id="' . encryptParam($user->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a></div>';

                            return $actions;
                        })
                        ->editColumn('no_of_students', function ($user) {
                            return '<a href="' . route('student.index', ['id' => encryptParam($user->id)]) . '" class="">' . $user->no_of_students . '</a>';
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
                        ->editColumn('subscription_expiry_date', function ($user) {
                            return ($user->subscription_expiry_date && $user->subscription_expiry_date != NULL_DATETIME) ? outputDateFormat($user->subscription_expiry_date) : 'NA';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new tutor/parent.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function create() {
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['howfind'] = [ '' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];

        $data['page_heading'] = trans('admin/tutor.manage_tutor');
        $data['page_title'] = trans('admin/tutor.add_tutor');
        $data['trait'] = array('trait_1' => trans('admin/tutor.tutor'), 'trait_1_link' => route('tutor.index'), 'trait_2' => trans('admin/tutor.add_tutor'));
        $data['JsValidator'] = 'App\Http\Requests\Tutor\TutorCreateRequest';

        return view('admin.tutor.create', $data);
    }

    /**
     * Insert a new the tutor/parent
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Tutor\TutorCreateRequest $request
     * @return Response
     */
    public function store(TutorCreateRequest $request, EmailRepository $emailRepo) {
        $inputs = $request->all();
        //asd($inputs);
        // checking file is valid.
        if ($request->file('image')) {
            $inputs['image'] = $this->userRepo->userImageUpload($request->file('image'));
        }
        $inputs['user_type'] = TUTOR;
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
                    'first_name' => $inputs['first_name'],
                    'last_name' => $inputs['last_name'],
                )
            );
            $emailRepo->sendEmail($emailParam, 11);
            return redirect(route('tutor.index'))->with('ok', trans('admin/tutor.added_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Show the tutor/parent detail
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $status = statusArray();
        $user = $this->userRepo->showTutor(decryptParam($id));
        //asd($user);
        return view('admin.tutor.show', compact('user', 'status'));
    }

    /**
     * Show the form for edit tutor/parent.
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $user = User::findOrFail($id)->toArray();
        //asd($user);
        $user['date_of_birth'] = outputDateFormat($user['date_of_birth']);
        $data['user'] = $user;
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['status'] = statusArray();
        $data['page_heading'] = trans('admin/tutor.manage_tutor');
        $data['page_title'] = trans('admin/tutor.edit_tutor');
        $data['trait'] = array('trait_1' => trans('admin/tutor.tutor'), 'trait_1_link' => route('tutor.index'),
            'trait_2' => trans('admin/tutor.edit_tutor'));
        $data['JsValidator'] = 'App\Http\Requests\Tutor\TutorUpdateRequest';
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        return view('admin.tutor.edit')->with($data);
    }

    /**
     * Update the tutor/parent.
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Tutor\TutorUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(TutorUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['user_type'] = TUTOR;
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
        if ($this->userRepo->update($inputs, $id, $userDeleteImage)) {
            return redirect(route('tutor.index'))->with('ok', trans('admin/tutor.updated_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Delete a tutor/parent 
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
     * Display tutor/parent's edit profile form
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function editProfile() {
        $id = session()->get('user')['id'];
        $user = User::findOrFail($id)->toArray();
        $user['date_of_birth'] = outputDateFormat($user['date_of_birth']);
        $data['user'] = $user;
        $data['county'] = ['' => 'Select'] + County::getCounty();
        $data['country'] = ['' => 'Select'] + Country::getCountry();
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', [ 'file' => $user['image'], 'size' => 'large']) :
                '';
        return view('admin.tutor.editprofile')->with($data);
    }

    /**
     * Update tutor/parent's profile
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Requests\Tutor\TutorUpdateProfileRequest $request
     * @return Response
     */
    public function updateProfile(
    TutorUpdateProfileRequest $request) {
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
        if ($this->userRepo->updateTutorProfile($inputs, $id, $userDeleteImage)) {
            return redirect(route('myaccount'))->with('ok', trans('admin/admin.profile_updated_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    public function updatePaymentStatus($id) {
        $data = array();
        $status = statusArray();

        $user = $this->userRepo->showTutor(decryptParam($id));
        $param = array();
        $param['user_id'] = decryptParam($id);
        $payment = $this->userRepo->getPaymentDetails($param);
        //  asd($payment);
        $data['user'] = $user;
        $data['status'] = $status;
        $data['payment'] = $payment;
        return view('admin.tutor.updatepaymentstatus')->with($data);
    }

}
