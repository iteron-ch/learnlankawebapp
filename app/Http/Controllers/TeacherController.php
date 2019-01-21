<?php

/**
 * This controller is used for teacher.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\EmailRepository;
use App\Http\Requests\Teacher\TeacherCreateRequest;
use App\Http\Requests\Teacher\TeacherUpdateRequest;
use App\Http\Requests\Teacher\TeacherUpdateProfileRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\County;
use App\Models\School;
use DB;
use Datatables;

/**
 * This controller is used for teacher.
 * @author     Icreon Tech - dev1.
 */
class TeacherController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * Create a new TeacherController instance.
     * @param  App\Repositories\UserRepository $userRepo
     * @return void
     */
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Display teacher listing page.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function index(Request $request) {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        $id = $request->get('id');
        if (!empty($id)) {
            $id = decryptParam($id);
            $user = User::findOrFail($id)->toArray();
            $sessArray['sess_school_id'] = $id;
            $sessArray['sess_user_type'] = $user['user_type'];
            $sessArray['sess_school_name'] = $user['school_name'];
            $sessArray['sess_teacher_name'] = trim($user['first_name'] . ' ' . $user['last_name']);
            session()->put('school_teacher_param', $sessArray);
            $parentParamArray = session()->get('school_teacher_param');
            $data['parent_id'] = $id;
        } else {
            $loggedInUserDetails = session()->get('user');
            if ($loggedInUserDetails['user_type'] == SCHOOL) {

                $sessArray['sess_school_id'] = $loggedInUserDetails['id'];
                $sessArray['sess_user_type'] = $loggedInUserDetails['user_type'];
                $sessArray['sess_school_name'] = $loggedInUserDetails['school_name'];
                $sessArray['sess_teacher_name'] = trim($loggedInUserDetails['first_name'] . ' ' . $loggedInUserDetails['last_name']);
                session()->put('school_teacher_param', $sessArray);

                $parentParamArray = session()->get('school_teacher_param');
                $data['parent_id'] = $parentParamArray['sess_school_id'];
            } else if ($loggedInUserDetails['user_type'] == ADMIN) {
                $parentParamArray = session()->get('school_teacher_param');
                $data['parent_id'] = $parentParamArray['sess_school_id'];
            }
        }

        $userArray = array();
        $userArray[''] = trans('admin/admin.select_option');
        $param['user_type'] = SCHOOL; // for school listing

        $users = $this->userRepo->getUserList($param)->get()->toArray();
        foreach ($users as $key => $val) {
            $userArray[$val['id']] = $val['school_name'];
        }
        $data['school'] = $userArray;
        $data['school_name'] = $parentParamArray['sess_school_name'];

        $parentName = isset($parentParamArray['sess_school_name']) ? $parentParamArray['sess_school_name'] . ' : ' : '';
        $navgationArray = $this->getNavigation();
        if (session()->get('user')['user_type'] == ADMIN)
            $navgationArray['trait_2'] = $parentName . trans('admin/teacher.teacher');
        $data['trait'] = $navgationArray;

        //  $data['trait'] = array('trait_1' => $trait_1, 'trait_1_link' => $trait_1_link, 'trait_2' => trans('admin/teacher.edit_teacher'));
        //$data['trait'] = array('trait_1' => $trait_1, 'trait_1_link' => $trait_1_link, 'trait_2' => $trait_2);
        return view('admin.teacher.teacherlist', $data);
    }

    /**
     * Get record for teacher list
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $param['user_type'] = TEACHER;
        $parentParamArray = session()->get('school_teacher_param');
        if (!empty($parentParamArray['sess_user_type'])) {
            if ($parentParamArray['sess_user_type'] == SCHOOL)
                $param['school_id'] = $parentParamArray['sess_school_id'];
        }
        if ($request->has('isLimited')) {
            $param['limit'] = DASHBOARD_RECORD_LIMIT;
            if (session()->get('user')['user_type'] == SCHOOL)
                $param['school_id'] = session()->get('user')['id'];
        }
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
                            if ($request->has('school')) {
                                $query->where('users.school_id', '=', "{$request->get('school')}");
                            } else if (!empty($parentParamArray['sess_user_type'])) {
                                if ($parentParamArray['sess_user_type'] == SCHOOL)
                                    $query->where('users.school_id', '=', "{$parentParamArray['sess_school_id']}");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('users.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($user) {
                            return '<a href="javascript:void(0);" data-remote="' . route('teacher.show', encryptParam($user->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                        <a href="' . route('teacher.edit', encryptParam($user->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                        <a href="javascript:void(0);" data-id="' . encryptParam($user->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                        ->editColumn('created_at', function ($user) {
                            return $user->created_at ? outputDateFormat($user->created_at) : '';
                        })
                        ->editColumn('name', function ($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->editColumn('no_of_students', function ($user) {
                            return '<a href="' . route('student.index', ['id' => encryptParam($user->id), 'school' => encryptParam($user->school_id)]) . '" class="">' . $user->no_of_students . '</a>';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new teacher.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function create(Request $request) {
        $parentParamArray = session()->get('school_teacher_param');
        $id = $parentParamArray['sess_school_id'];
        if (!empty($id)) {
            $data['school_id'] = $id;
        }

        $userArray = array();
        $userArray[''] = trans('admin/admin.select_option');
        $param['user_type'] = SCHOOL;
        $users = $this->userRepo->getUserList($param)->get()->toArray();
        foreach ($users as $key => $val) {
            $userArray[$val['id']] = $val['school_name'];
        }
        $data['school'] = $userArray;

        $county = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['county'] = $county;
        $country = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['country'] = $country;

        $status = statusArray();
        $data['status'] = $status;
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['page_heading'] = trans('admin/teacher.manage_teacher');
        $data['page_title'] = trans('admin/teacher.add_teacher');

        $parentName = isset($parentParamArray['sess_school_name']) ? $parentParamArray['sess_school_name'] . ' : ' : '';
        $navgationArray = $this->getNavigation();
        $navgationArray['trait_2'] = $parentName . trans('admin/teacher.add_teacher');

        $data['trait'] = $navgationArray;

        $data['JsValidator'] = 'App\Http\Requests\Teacher\TeacherCreateRequest';
        $data['school_name'] = $parentParamArray['sess_school_name'];
        return view('admin.teacher.create', $data);
    }

    /**
     * Insert a new the teacher
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Teacher\TeacherCreateRequest $request
     * @return Response
     */
    public function store(TeacherCreateRequest $request, EmailRepository $emailRepo) {
        $inputs = $request->all();
        // checking file is valid.
        if ($request->file('image')) {
            $inputs['image'] = $this->userRepo->userImageUpload($request->file('image'));
        }
        $inputs['user_type'] = TEACHER;
        $inputs['school_id'] = $inputs['school'];
        $inputs['created_by'] = session()->get('user')['id'];
        if ($this->userRepo->store($inputs)) {
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
                    'password' => $inputs['password']
                )
            );
            $emailParam2 = array(
                'addressData' => array(
                    'to_email' => session()->get('user')['email'],
                    'to_name' => session()->get('user')['username'],
                ),
                'userData' => array(
                    'username' => session()->get('user')['username'],
                )
            );
            $emailRepo->sendEmail($emailParam, 7);
            $emailRepo->sendEmail($emailParam2, 21);
            return redirect(route('teacher.index'))->with('ok', trans('admin/teacher.added_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Show the teacher detail
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function show($id) {

        $id = decryptParam($id);
        $status = statusArray();
        $user = $this->userRepo->showTeacher($id);
        return view('admin.teacher.show', compact('user', 'status'));
    }

    /**
     * Show the form for edit teacher.
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $user = User::findOrFail($id)->toArray();
        $user['school'] = $user['school_id'];
        $data['user'] = $user;
        $userArray = array();
        $userArray[''] = trans('admin/admin.select_option');
        $param['user_type'] = SCHOOL; // for school listing    
        $users = $this->userRepo->getUserList($param)->get()->toArray();
        foreach ($users as $key => $val) {
            $userArray[$val['id']] = $val['school_name'];
        }
        $data['school'] = $userArray;

        $county = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['county'] = $county;

        $country = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['country'] = $country;

        $parentParamArray = session()->get('school_teacher_param');
        if ($parentParamArray['sess_user_type'] == SCHOOL) {
            $id = $parentParamArray['sess_school_id'];
            if (!empty($id)) {
                $data['school_id'] = $id;
            }
        }

        $status = statusArray();
        $data['status'] = $status;
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['page_heading'] = trans('admin/teacher.manage_teacher');
        $data['page_title'] = trans('admin/teacher.edit_teacher');

        $navgationArray = $this->getNavigation();
        $parentName = isset($parentParamArray['sess_school_name']) ? $parentParamArray['sess_school_name'] . ' : ' : '';
        $navgationArray['trait_2'] = $parentName . trans('admin/teacher.edit_teacher');
        $data['trait'] = $navgationArray;

        //$data['trait'] = array('trait_1' => trans('admin/teacher.teacher'), 'trait_1_link' => route('teacher.index'), 'trait_2' => trans('admin/teacher.edit_teacher'));
        $data['JsValidator'] = 'App\Http\Requests\Teacher\TeacherUpdateRequest';
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        $data['school_name'] = $parentParamArray['sess_school_name'];
        return view('admin.teacher.edit')->with($data);
    }

    /**
     * Update the teacher.
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Teacher\TeacherUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(TeacherUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['user_type'] = TEACHER;
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
            return redirect(route('teacher.index'))->with('ok', trans('admin/teacher.updated_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Delete a teacher 
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
     * Display teacher's edit profile form
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function editProfile() {
        $id = session()->get('user')['id'];
        $user = User::findOrFail($id)->toArray();
        $data['user'] = $user;

        $county = ['' => 'Select'] + County::getCounty();
        $data['county'] = $county;

        $country = ['' => 'Select'] + Country::getCountry();
        $data['country'] = $country;
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        return view('admin.teacher.editprofile')->with($data);
    }

    /**
     * Update teacher's profile
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Requests\Teacher\TeacherUpdateProfileRequest $request
     * @return Response
     */
    public function updateProfile(TeacherUpdateProfileRequest $request) {
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
        if ($this->userRepo->updateTeacherProfile($inputs, $id, $userDeleteImage)) {
            return redirect(route('manageprofile'))->with('ok', trans('admin/admin.profile_updated_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * used for the navigation
     * @return array
     */
    function getNavigation() {
        $navgationArray['trait_1'] = '';
        $navgationArray['trait_1_link'] = '';
        $navgationArray['trait_2'] = '';
        $parentParamArray = session()->get('school_teacher_param');
        if (session()->get('user')['user_type'] != TEACHER && session()->get('user')['user_type'] != TUTOR) {
            if (session()->get('user')['user_type'] == SCHOOL) {
                $navgationArray['trait_1'] = trans('admin/school.school');
                $navgationArray['trait_1_link'] = route('teacher.index');
                $navgationArray['trait_2'] = '';
            } else {
                if (isset($parentParamArray['sess_school_name'])) {
                    $navgationArray['trait_1'] = trans('admin/school.school');
                    $navgationArray['trait_1_link'] = route('school.index');
                }
            }
        }
        return $navgationArray;
    }

    /**
     * This is used for dashboad student rewards
     * @param Request $request
     * @return type
     */
    public function studentRewards(Request $request) {
        if ($request->has('isLimited'))
            $param['limit'] = DASHBOARD_RECORD_LIMIT;
        $users = $this->userRepo->getStudentRewardsList($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                            
                        })
                        ->make(true);
    }

    /**
     * This is used to add a new Event
     * @param Request $request
     * @return type
     * @author     Icreon Tech  - dev5.
     */
    public function addEvent(Request $request) {
        $inputs = $request->all();
        //asd($inputs);
        $this->userRepo->saveEvent($inputs);
    }

    /**
     * This is used to Update an Event
     * @param Request $request
     * @return type
     * @author     Icreon Tech  - dev5.
     */
    public function updateEvent(Request $request) {
        $inputs = $request->all();
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->userRepo->updateEvent($inputs);
    }

    /**
     * This is used to delete an Event
     * @param Request $request
     * @return type
     * @author     Icreon Tech  - dev5.
     */
    public function deleteEvent(Request $request) {
        $id = $request->all();
        $this->userRepo->deleteEvent($id);
    }

}
