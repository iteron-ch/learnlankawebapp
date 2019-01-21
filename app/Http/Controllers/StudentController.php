<?php

/**
 * This controller is used for student.
 * @package    User, Student
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\EmailRepository;
use App\Repositories\GroupClassRepository;
use App\Http\Requests\Student\StudentCreateRequest;
use App\Http\Requests\Student\StudentUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\Howfind;
use App\Models\Ethnicitiy;
use App\Models\County;
use Datatables;

/**
 * This controller is used for student.
 * @author     Icreon Tech - dev1.
 */
class StudentController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * Create a new StudentController instance.
     * @param  App\Repositories\UserRepository $userRepo
     * @return void
     */
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Display student listing page.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function index(Request $request) {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        $id = $request->get('id');
        $userArray = array();
        $userArray[''] = trans('admin/admin.select_option');
        $param['user_type'] = TEACHER;
        $param['status'] = ACTIVE;
        if (!empty($id)) {
            $id = decryptParam($id);
            $schoolId = decryptParam($request->get('school'));
            $user = User::findOrFail($id)->toArray();

            if ($user['user_type'] == TUTOR) {
                $sessArray['sess_tutor_id'] = $id;
            } else {
                if (!empty($schoolId)) {
                    $sessArray['sess_school_id'] = $schoolId;
                    $sessArray['sess_teacher_id'] = $id;
                    $data['teacher_id'] = $id;
                    $param['school_id'] = $schoolId;
                } else {
                    $sessArray['sess_school_id'] = $id;
                    $sessArray['sess_teacher_id'] = '';
                    $param['school_id'] = $user['id'];
                    $data['teacher_id'] = '';
                }
            }
            $sessArray['sess_user_type'] = $user['user_type'];
            if ($user['user_type'] == SCHOOL)
                $sessArray['sess_parent_name'] = $user['school_name'];
            else
                $sessArray['sess_parent_name'] = trim($user['first_name'] . ' ' . $user['last_name']);

            session()->put('school_student_param', $sessArray);
            $parentParamArray = session()->get('school_student_param');
            $data['user_type'] = $user['user_type'];
            $data['sess_parent_name'] = $parentParamArray['sess_parent_name'];
        } else {
            $loggedInUserDetails = session()->get('user');
            if ($loggedInUserDetails['user_type'] == TUTOR) {
                $sessArray['sess_tutor_id'] = $loggedInUserDetails['id'];
                $sessArray['sess_user_type'] = $loggedInUserDetails['user_type'];
                $sessArray['sess_parent_name'] = trim($loggedInUserDetails['first_name'] . ' ' . $loggedInUserDetails['last_name']);
                session()->put('school_student_param', $sessArray);
                $parentParamArray = session()->get('school_student_param');
                $data['tutor_id'] = $sessArray['sess_tutor_id'];
            } else if ($loggedInUserDetails['user_type'] == TEACHER) {
                $sessArray['sess_teacher_id'] = $loggedInUserDetails['id'];
                $sessArray['sess_school_id'] = $loggedInUserDetails['school_id'];
                $sessArray['sess_user_type'] = $loggedInUserDetails['user_type'];
                $sessArray['sess_school_name'] = $loggedInUserDetails['school_name'];
                $sessArray['sess_teacher_name'] = trim($loggedInUserDetails['first_name'] . ' ' . $loggedInUserDetails['last_name']);
                $sessArray['sess_parent_name'] = trim($loggedInUserDetails['first_name'] . ' ' . $loggedInUserDetails['last_name']);
                session()->put('school_student_param', $sessArray);
                $parentParamArray = session()->get('school_student_param');
                $data['school_id'] = $parentParamArray['sess_school_id'];
                $data['teacher_id'] = $parentParamArray['sess_teacher_id'];
                $param['school_id'] = $parentParamArray['sess_school_id'];
                $param['teacher_id'] = $parentParamArray['sess_teacher_id'];
            } else if ($loggedInUserDetails['user_type'] == SCHOOL) {
                $parentParamArray = session()->get('school_student_param');
                $data['school_id'] = $parentParamArray['sess_school_id'];
                $data['teacher_id'] = $parentParamArray['sess_teacher_id'];
                $param['school_id'] = $parentParamArray['sess_school_id'];
                $param['teacher_id'] = $parentParamArray['sess_teacher_id'];
            } else {
                $parentParamArray = session()->get('school_student_param');
                if ($parentParamArray['sess_user_type'] == TUTOR) {
                    $data['tutor_id'] = $parentParamArray['sess_tutor_id'];
                } else {
                    $loggedInUserDetails = session()->get('user');
                    $parentParamArray = session()->get('school_student_param');
                }
            }
            $data['user_type'] = $parentParamArray['sess_user_type'];
            $data['sess_parent_name'] = $parentParamArray['sess_parent_name'];
        }
        $users = $this->userRepo->getUser($param)->get()->toArray();

        foreach ($users as $key => $val) {
            $userArray[$val['id']] = trim($val['first_name'] . ' ' . $val['last_name']);
        }


        $updateParam = array();
        $logged_user_type = session()->get('user')['user_type'];

        if ($logged_user_type == TUTOR)
            $updateParam['id'] = session()->get('user')['id'];



        if ($logged_user_type == TEACHER || $logged_user_type == SCHOOL || $logged_user_type == ADMIN) {
            if ($parentParamArray['sess_user_type'] == TUTOR)
                $updateParam['id'] = $parentParamArray['sess_tutor_id'];
            else
                $updateParam['id'] = $parentParamArray['sess_school_id'];
        }

        if (!empty($updateParam))
            $userDetailsData = $this->userRepo->getUser($updateParam)->get()->toArray();

        $data['remaining_no_of_student'] = 0;

        if (!empty($userDetailsData))
            $data['remaining_no_of_student'] = $userDetailsData['0']['remaining_no_of_student'];

        $data['user_array'] = $userArray;
        $parentName = isset($parentParamArray['sess_parent_name']) ? $parentParamArray['sess_parent_name'] . ' : ' : '';
        $navgationArray = $this->getNavigation();
        if (session()->get('user')['user_type'] != TEACHER && session()->get('user')['user_type'] != TUTOR)
            $navgationArray['trait_2'] = $parentName . trans('admin/student.student');
        else
            $navgationArray['trait_1'] = trans('admin/student.student');
        $data['trait'] = $navgationArray;
        
        if (!empty($sessArray['sess_school_id']))
            $data['school_record_id'] = encryptParam($sessArray['sess_school_id']);
        else
            $data['school_record_id'] = '';

        if (!empty($data['school_id']))
            $data['school_record_id'] = encryptParam($data['school_id']);

        return view('admin.student.studentlist', $data);
    }

    /**
     * Get record for student list
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $param['user_type'] = STUDENT; // student    
        $parentParamArray = session()->get('school_student_param');
        if (!empty($parentParamArray['sess_user_type'])) {
            if ($parentParamArray['sess_user_type'] == TEACHER)
                $param['teacher_id'] = $parentParamArray['sess_teacher_id'];
            else if ($parentParamArray['sess_user_type'] == SCHOOL)
                $param['school_id'] = $parentParamArray['sess_school_id'];
            else if ($parentParamArray['sess_user_type'] == TUTOR)
                $param['tutor_id'] = $parentParamArray['sess_tutor_id'];
        }
        if ($request->has('isLimited')) {
            $param['limit'] = DASHBOARD_RECORD_LIMIT;
            if (session()->get('user')['user_type'] == TEACHER)
                $param['teacher_id'] = session()->get('user')['id'];
            else if (session()->get('user')['user_type'] == SCHOOL)
                $param['school_id'] = session()->get('user')['id'];
            else if (session()->get('user')['user_type'] == TUTOR)
                $param['tutor_id'] = session()->get('user')['id'];
        }

        $users = $this->userRepo->getUserList($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('first_name')) {
                                $query->where('first_name', 'like', "%{$request->get('first_name')}%");
                            }
                            if ($request->has('last_name')) {
                                $query->where('last_name', 'like', "%{$request->get('last_name')}%");
                            }
                            if ($request->has('teacher_name')) {
                                $query->where('teacher_id', '=', "{$request->get('teacher_name')}");
                            } else if (!empty($parentParamArray['sess_user_type'])) {
                                if ($parentParamArray['sess_user_type'] == TEACHER)
                                    $query->where('teacher_id', '=', "{$parentParamArray['sess_teacher_id']}");
                                else if ($parentParamArray['sess_user_type'] == SCHOOL)
                                    $query->where('school_id', '=', "{$parentParamArray['sess_school_id']}");
                                else if ($parentParamArray['sess_user_type'] == TUTOR)
                                    $query->where('tutor_id', '=', "{$parentParamArray['sess_tutor_id']}");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('users.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($user) {
                            return '<a href="javascript:void(0);" data-remote="' . route('student.show', encryptParam($user->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                        <a href="' . route('student.edit', encryptParam($user->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                        <a href="javascript:void(0);" data-id="' . encryptParam($user->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>
                                        <a href="/adminreport/studenttest?id=' . $user->id . '&report=test&tab=math" alt="View Report" title="View Report"><i class="glyphicon glyphicon-stats"></i></a>';
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
     * Show the form for creating a new student.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    public function create(GroupClassRepository $groupClassRepo) {
        if (session()->get('user')['user_type'] != TUTOR) {
            if (session()->get('user')['user_type'] == SCHOOL) {
                $schoolId = session()->get('user')['id'];
            } else if (session()->get('user')['user_type'] == TEACHER) {
                $schoolId = session()->get('user')['school_id'];
            } else if (session()->get('user')['user_type'] == ADMIN) {
                $session_data = session()->get('school_student_param');
                if (!empty($session_data['sess_school_id']))
                    $schoolId = $session_data['sess_school_id'];
            }
            if (!empty($schoolId)) {
                $data['all_groups'] = $groupClassRepo->getGroups(array('created_by' => $schoolId));
                $data['stClass'] = ['' => trans('admin/admin.select_option')] + $groupClassRepo->getClasses(array('created_by' => $schoolId));
            }
        }
        $ethnicity = ['' => trans('admin/admin.select_option')] + Ethnicitiy::getEthnicitiy();
        $sen_details = ['' => trans('admin/admin.select_option')] + array('1' => 'SEN Support', '2' => 'EHC Plan');
        $termOfBirth = ['' => trans('admin/admin.select_option')] + array('1' => 'Autumn Term', '2' => 'Spring Term', '3' => 'Summer Term');
        $data['termOfBirth'] = $termOfBirth;
        $data['sen_details'] = $sen_details;
        $data['ethnicity'] = $ethnicity;
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + array(2 => 'KS 2');
        $data['yearKeys'] = array('' => trans('admin/admin.select_option')) + array(5 => 'YG 5', 6 => 'YG 6');
        //$data['yearKeysJson'] = json_encode($yearKeys);

        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        //asd($data['country']);
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind();
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $parentParamArray = session()->get('school_student_param');
        $data['sess_parent_name'] = $parentParamArray['sess_parent_name'];
        if ($parentParamArray['sess_user_type'] == TUTOR) {
            $data['tutor_id'] = $parentParamArray['sess_tutor_id'];
        } else {
            if(empty($parentParamArray['sess_school_id'])){
                return redirect(route('dashboard'));
            }
            $data['school_id'] = $parentParamArray['sess_school_id'];
            $teacherArray = array();
            $teacherArray[''] = trans('admin/admin.select_option');
            $param['user_type'] = TEACHER;
            $param['school_id'] = $parentParamArray['sess_school_id'];
            $param['status'] = ACTIVE;
            $users = $this->userRepo->getUser($param)->get()->toArray();
            foreach ($users as $key => $val) {
                $teacherArray[$val['id']] = trim($val['first_name'] . ' ' . $val['last_name']);
            }
            $data['teacher'] = $teacherArray;
        }

        if (!empty($parentParamArray['sess_teacher_id']))
            $data['teacher_id'] = $parentParamArray['sess_teacher_id'];

        // echo "tutor".$data['tutor_id'];
        $data['sess_user_type'] = $parentParamArray['sess_user_type'];
        $data['page_heading'] = trans('admin/student.manage_student');
        $data['page_title'] = trans('admin/student.add_student');
        $navgationArray = $this->getNavigation();
        $parentName = isset($parentParamArray['sess_parent_name']) ? $parentParamArray['sess_parent_name'] . ' : ' : '';
        $navgationArray['trait_2'] = $parentName . trans('admin/student.add_student');
        $data['trait'] = $navgationArray;
        $data['JsValidator'] = 'App\Http\Requests\Student\StudentCreateRequest';

        return view('admin.student.create', $data);
    }

    /**
     * Insert a new the student
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Student\StudentCreateRequest $request
     * @return Response
     */
    public function store(StudentCreateRequest $request, EmailRepository $emailRepo) {

        $parentParamArray = session()->get('school_student_param');
        $inputs = $request->all();
        $params['user_id'] = session()->get('user')['id'];
        // checking file is valid.
        if ($request->file('image')) {
            $inputs['image'] = $this->userRepo->userImageUpload($request->file('image'));
        }
        $inputs['session_user_type'] = $parentParamArray['sess_user_type'];
        if ($parentParamArray['sess_user_type'] == TUTOR)
            $inputs['sess_tutor_id'] = $parentParamArray['sess_tutor_id'];
        $inputs['user_type'] = STUDENT; /* student */
        $inputs['created_by'] = session()->get('user')['id'];
        
        if (session()->get('user')['school_name'] != '') {
            $email_name = session()->get('user')['school_name'];
        }
        else{
            $email_name = session()->get('user')['first_name']. ' '.session()->get('user')['last_name'];
        }
        
        if ($this->userRepo->store($inputs)) {
            $emailParam = array(
                'addressData' => array(
                    'to_email' => $inputs['email'],
                    'to_name' => $inputs['username'],
                ),
                'userData' => array(
                    'username' => $inputs['username'],
                    'first_name' => $inputs['first_name'],
                    'last_name' => $inputs['last_name'],
                    'password' => $inputs['password'],
                )
            );
            $emailParam2 = array(
                'addressData' => array(
                    'to_email' => session()->get('user')['email'],
                    'to_name' => $email_name,
                ),
                'userData' => array(
                    'username' => session()->get('user')['username'],
                    'first_name' => $inputs['first_name'],
                    'last_name' => $inputs['last_name'],
                    'school_name' => session()->get('user')['school_name'],
                    'user_first_name' => session()->get('user')['first_name'],
                    'user_last_name' => session()->get('user')['last_name'],
                )
            );

            $updateParam = array();
            $updateParam['user_type'] = session()->get('user')['user_type'];

            if ($updateParam['user_type'] == TUTOR)
                $updateParam['user_id'] = $params['user_id'];

            if ($updateParam['user_type'] == TEACHER || $updateParam['user_type'] == SCHOOL || $updateParam['user_type'] == ADMIN) {
                if ($parentParamArray['sess_user_type'] == TUTOR)
                    $updateParam['user_id'] = $parentParamArray['sess_tutor_id'];
                else
                    $updateParam['user_id'] = $parentParamArray['sess_school_id'];
            }

            // if ($updateParam['user_type'] == TEACHER || $updateParam['user_type'] == SCHOOL || $updateParam['user_type'] == ADMIN)
            //      $updateParam['user_id'] = $parentParamArray['sess_school_id'];
            $this->userRepo->updateRemainingAccount($updateParam);
            if (!empty($inputs['email']))
            $emailRepo->sendEmail($emailParam, 2);
            $emailRepo->sendEmail($emailParam2, 4);
            return redirect(route('student.index'))->with('ok', trans('admin/student.added_successfully'));
        }else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Show the student detail
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function show(GroupClassRepository $groupClassRepo, $id) {
        $status = statusArray();
        $user = $this->userRepo->showStudent(decryptParam($id));

        $studentClass = $groupClassRepo->getStudentClass($user->id);
        if (isset($studentClass[0])) {
            $user->studentClass = $studentClass[0]['class_name'];
        }
        $groups = $groupClassRepo->getStudentGroups($user->id);
        if (count($groups)) {
            $user->groups = $groups;
        }
        $sess_user_type = session()->get('school_student_param')['sess_user_type'];
        return view('admin.student.show', compact('user', 'status', 'sess_user_type'));
    }

    /**
     * Show the form for edit student.
     * @author Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function edit($id, GroupClassRepository $groupClassRepo) {
        $parentParamArray = session()->get('school_student_param');
        $loggedInUserDetails = session()->get('user');
        //asd($loggedInUserDetails);

        $data['sess_parent_name'] = $parentParamArray['sess_parent_name'];
        $data['sess_user_type'] = $parentParamArray['sess_user_type'];

        if (!empty($parentParamArray['sess_school_id']) && $parentParamArray['sess_user_type'] != TUTOR) {
            $data['school_id'] = $parentParamArray['sess_school_id'];
            $param['school_id'] = $parentParamArray['sess_school_id'];
            if ($loggedInUserDetails['user_type'] != SCHOOL && $loggedInUserDetails['user_type'] != ADMIN)
                $data['teacher_id'] = $parentParamArray['sess_teacher_id'];
        } else {
            $param['tutor_id'] = $parentParamArray['sess_tutor_id'];
            $data['tutor_id'] = $parentParamArray['sess_tutor_id'];
            if (!empty($parentParamArray['sess_teacher_id']))
                $data['teacher_id'] = $parentParamArray['sess_teacher_id'];
        }
        $id = decryptParam($id);
        $user = User::findOrFail($id)->toArray();
        $ethnicity = ['' => trans('admin/admin.select_option')] + Ethnicitiy::getEthnicitiy();
        $sen_details = ['' => trans('admin/admin.select_option')] + array('1' => 'SEN Support', '2' => 'EHC Plan');
        $termOfBirth = ['' => trans('admin/admin.select_option')] + array('1' => 'Autumn Term', '2' => 'Spring Term', '3' => 'Summer Term');
        $data['termOfBirth'] = $termOfBirth;
        $data['sen_details'] = $sen_details;
        $data['ethnicity'] = $ethnicity;
        $data['user'] = $user;
        $user['date_of_birth'] = outputDateFormat($user['date_of_birth']);
        $user['date_of_entry'] = outputDateFormat($user['date_of_entry']);
        $data['user'] = $user;
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty();
        $data['country'] = ['' => trans('admin/admin.select_option')] + Country::getCountry();
        $data['howfind'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFind();
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $session_data = session()->get('school_student_param');

        if (session()->get('user')['user_type'] != TUTOR) {
            if (session()->get('user')['user_type'] == SCHOOL) {
                $schoolId = session()->get('user')['id'];
            } else if (session()->get('user')['user_type'] == TEACHER) {
                $schoolId = session()->get('user')['school_id'];
            } else if (session()->get('user')['user_type'] == ADMIN) {
                $session_data = session()->get('school_student_param');
                if (!empty($session_data['sess_school_id']))
                    $schoolId = $session_data['sess_school_id'];
            }
            if (!empty($schoolId)) {
                $data['all_groups'] = $groupClassRepo->getGroups(array('created_by' => $schoolId));
                $data['stClass'] = ['' => trans('admin/admin.select_option')] + $groupClassRepo->getClasses(array('created_by' => $schoolId));
            }
        }
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + array(2 => 'KS 2');
        $data['yearKeys'] = array('' => trans('admin/admin.select_option')) + array(5 => 'YG 5', 6 => 'YG 6');
        $data['user']['groups'] = $groupClassRepo->studentGroupIds($id);
        $studentClass = $groupClassRepo->getStudentClass($id);
        $data['user']['schoolclasses_id'] = isset($studentClass[0]) ? $studentClass[0]['id'] : '';
        $data['status'] = statusArray();
        $teacherArray = array();
        $teacherArray[''] = trans('admin/admin.select_option');
        $param['user_type'] = TEACHER;
        $param['status'] = ACTIVE;
        $users = $this->userRepo->getUser($param)->get()->toArray();
        foreach ($users as $key => $val) {
            $teacherArray[$val['id']] = trim($val['first_name'] . ' ' . $val['last_name']);
        }
        $data['teacher'] = $teacherArray;
        $data['page_heading'] = trans('admin/student.manage_student');
        $data['page_title'] = trans('admin/student.edit_student');
        $parentName = isset($parentParamArray['sess_parent_name']) ? $parentParamArray['sess_parent_name'] . ' : ' : '';
        $navgationArray = $this->getNavigation();
        $navgationArray['trait_2'] = $parentName . trans('admin/student.edit_student');
        $data['trait'] = $navgationArray;
        //  $data['trait'] = array('trait_1' => trans('admin/student.student'), 'trait_1_link' => route('student.index'), 'trait_2' => trans('admin/student.edit_student'));
        $data['JsValidator'] = 'App\Http\Requests\Student\StudentUpdateRequest';
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        return view('admin.student.edit')->with($data); //, );
    }

    /**
     * Update the student.
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Student\StudentUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(StudentUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['user_type'] = STUDENT;
        $userDeleteImage = FALSE;
        if (isset($inputs['image']) && empty($inputs['image'])) {
            $userDeleteImage = TRUE;
        }
        if ($request->file('image')) {
            $userDeleteImage = TRUE;
            $inputs['image'] = $this->userRepo->userImageUpload($request->file('image'));
        }
        $parentParamArray = session()->get('school_student_param');

        $inputs['session_user_type'] = $parentParamArray['sess_user_type'];
        if ($parentParamArray['sess_user_type'] == TUTOR)
            $inputs['sess_tutor_id'] = $parentParamArray['sess_tutor_id'];

        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        if ($this->userRepo->update($inputs, $id, $userDeleteImage)) {
            return redirect(route('student.index'))->with('ok', trans('admin/student.updated_successfully'));
        } else {
            return back()->with('error', trans('admin/admin.submit_exception'));
        }
    }

    /**
     * Delete a student 
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
     * used for the navigation
     * @return array
     */
    function getNavigation() {
        $navgationArray['trait_1'] = '';
        $navgationArray['trait_1_link'] = '';
        $navgationArray['trait_2'] = '';
        $parentParamArray = session()->get('school_student_param');
        if (session()->get('user')['user_type'] != TEACHER && session()->get('user')['user_type'] != TUTOR) {
            if (isset($parentParamArray['sess_parent_name'])) {
                if ($parentParamArray['sess_user_type'] == SCHOOL) {
                    $navgationArray['trait_1'] = trans('admin/school.school');
                    $navgationArray['trait_1_link'] = route('school.index');
                } else if ($parentParamArray['sess_user_type'] == TEACHER) {
                    $navgationArray['trait_1'] = trans('admin/teacher.teacher');
                    $navgationArray['trait_1_link'] = route('teacher.index');
                } else if ($parentParamArray['sess_user_type'] == TUTOR) {
                    $navgationArray['trait_1'] = trans('admin/tutor.tutor');
                    $navgationArray['trait_1_link'] = route('tutor.index');
                }
            } else {
                $navgationArray['trait_1'] = '';
                $navgationArray['trait_1_link'] = '';
            }
        } else {
            $navgationArray['trait_1'] = trans('admin/student.student');
            $navgationArray['trait_1_link'] = route('student.index');
            $navgationArray['trait_2'] = '';
        }
        return $navgationArray;
    }

    public function importStudent($id) {
        $data = array();
        if (empty($id)) {
            return redirect('error404');
        }
        $data['school_id'] = $id;
        return view('admin.student.importstudent')->with($data);
    }
    
    public function checkSchoolLicenceCount($school){
            $remaining_no_of_student = 0;
            $getStudentParam['school_id'] = $school;
            $userRecordResult = $this->userRepo->getRemainingAccountStudent($getStudentParam);
            if(!empty($userRecordResult))
            {
                return $remaining_no_of_student = $userRecordResult->remaining_no_of_student;
            }
           // if($remaining_no_of_student < $csvRecordCount){
               // $dataPostParam['school_id'] = $inputs['school'];
                //return $remaining_no_of_student;
                //return $dataPostParam['recordsCountError'] = "Remaining Number of Pupil Licenses: ".$remaining_no_of_student.". Please upgrade your subscription to add the more students.";
                //return view('admin.student.importstudent')->with($dataPostParam);
                //die;
           // }        
        
    }
    public function importStudentStore(Request $request) {
        $inputs = $request->all();
        if (isset($inputs['file1'])) {
            $bulk_file = $inputs['file1'];
            
            $file = fopen($bulk_file, "r");
            $uploadedHeader = fgetcsv($file);
            $emptyRecords = array();
            
            $fprec = file($bulk_file);
            $csvRecordCount = count($fprec) - 1;
            
            
            $duplicateExistsReocrds = array();
            $i = 1;
            $insertedRecords = array();
            $doesnotExistsReocrds = array();
            $getStudentParam = array();
            $remaining_no_of_student = 0;
            $dataMsg = array();
            
            while (!feof($file)) {
                $data = fgetcsv($file);
                //asd($data);
                if (is_array($data)) {
                    $remaining_no_of_student = $this->checkSchoolLicenceCount(decryptParam($inputs['school']));
                    if($remaining_no_of_student > 0)
                    {
                    $inputParam['username'] = trim($data[0]);
                    $inputParam['email'] = (isset($data[1]) && !empty($data[1]))?trim($data[1]):'';
                    $stParam = array();
                    $stParam['username'] = $inputParam['username'];
                    $stParam['email'] = $inputParam['email'];
                    if (empty(trim($data[0]))) {
                        $emptyRecords[$i][] = "Username";
                    }
                    //else if (empty(trim($data[1]))) {
                    //    $alreadyExistsReocrds[] = "Email Id";
                    //}
                    if (empty(trim($data[2]))) {
                        $emptyRecords[$i][] = "Pasword";
                    }
                    if (empty(trim($data[3]))) {
                        $emptyRecords[$i][] = "Teacher Name";
                    }
                    if (empty(trim($data[4]))) {
                        $emptyRecords[$i][] = "Address";
                    }
                    if (empty(trim($data[5]))) {
                        $emptyRecords[$i][] = "City";
                    }
                    if (empty(trim($data[6]))) {
                        $emptyRecords[$i][] = "Postal Code";
                    }
                    if (empty(trim($data[7]))) {
                        $emptyRecords[$i][] = "County";
                    }
                    if (empty(trim($data[8]))) {
                        $emptyRecords[$i][] = "Country";
                    }
                    //else if (empty(trim($data[9]))) {
                    //    $alreadyExistsReocrds[] = "Telephone No.";
                    //}
                    if (empty(trim($data[10]))) {
                        $emptyRecords[$i][] = "First Name";
                    }
                    if (empty(trim($data[11]))) {
                        $emptyRecords[$i][] = "Last Name";
                    }
                    if (empty(trim($data[12]))) {
                        $emptyRecords[$i][] = "Date of Birth";
                    }
                    if (empty(trim($data[13]))) {
                        $emptyRecords[$i][] = "Key Stage";
                    }
                    if (empty(trim($data[14]))) {
                        $emptyRecords[$i][] = "Year Group";
                    }
                    if (empty(trim($data[15]))) {
                        $emptyRecords[$i][] = "Ethnicity";
                    }
                    if ((trim($data[16]) != '' ) && (trim($data[16]) == '1' )) {
                        if ((trim($data[17]) == '')) {
                            $emptyRecords[$i][] = "SEN Provision Details";
                        }
                    }


                    // if (empty(trim($data[16])) && (trim($data[17])) != 0) {
                    //     $emptyRecords[$i][] = "SEN Provision ";
                    // }
//                    if (empty(trim($data[18]))&& (trim($data[18]))!= 0) {
//                        
//                        $alreadyExistsReocrds[$i][] = "SATs Exempt";
//                    }
                    //if (empty(trim($data[19]))) {
                    //    $emptyRecords[$i][] = "KS1 Average Point Score";
                    //}
                    if (trim(strtolower($data[19])) == 'percentage' || trim(strtolower($data[19])) == 'score') {
                        if (empty(trim($data[20]))) {
                            $emptyRecords[$i][] = "KS1 Average Point Score Value";
                        }
                    }



                    if (empty($emptyRecords)) {
                        if (!empty(trim($data[1]))) {
                            $userRecord = $this->userRepo->getDuplicateUser($stParam)->get()->toArray(); /* duplicate record checking */
                        }
                        if (empty(trim($data[1]))) {
                            $stParam = array();
                            $stParam['username'] = $inputParam['username'];
                            $userRecord = $this->userRepo->getDuplicateUsername($stParam)->get()->toArray(); /* duplicate record checking */
                        }
                        //  asd($userRecord);

                        $teacherSearchParam['email'] = trim($data[3]);
                        $teacherRecord = $this->userRepo->getUser($teacherSearchParam)->get()->toArray(); /* teacher exists check */
                        if (empty($teacherRecord)) { // list of teachers does not exists.
                            $doesnotExistsReocrds[] = "<strong>Row No. " . ($i + 1) . "</strong> Teacher for this Email Id: <b>" . $data[3] . '</b> does not exists.';
                        } else {
                            if (!empty($userRecord)) { // list of students already exists.
                                $duplicateExistsReocrds[] = "<strong>Row No. " . ($i + 1) . "</strong> Either Username: <b>" . $inputParam['username'] . "</b> or Email Id: <b>" . $inputParam['email'] . '</b> already exists.';
                            } else {
                                $inputParam['password'] = trim($data[2]);
                                $inputParam['user_type'] = STUDENT;
                                $inputParam['school'] = decryptParam($inputs['school']); // = trim($data[4]);
                                /* code to get the teacher id */
                                $teacher = trim($data[3]);
                                $param['email'] = $teacher;
                                $param['user_type'] = TEACHER;
                                $userRecord = array();
                                $userRecord = $this->userRepo->getUser($param)->get()->toArray();
                                if (!empty($userRecord)) {
                                    $inputParam['teacher_id'] = $userRecord[0]['id'];
                                } else {
                                    $inputParam['teacher_id'] = '0';
                                }
                                /* end code to get the teacher id */
                                $inputParam['address'] = trim($data[4]);
                                $inputParam['city'] = trim($data[5]);
                                $inputParam['postal_code'] = trim($data[6]);

                                /* code to get the county id */
                                $param = array();
                                $param['county'] = trim($data[7]);
                                $resultRecord = $this->userRepo->getCounty($param);
                                if (!empty($resultRecord)) {
                                    $inputParam['county'] = $resultRecord->id;
                                } else {
                                    $inputParam['county'] = '0';
                                }
                                /* end code to get the county id */
                                $inputParam['country'] = trim($data[8]);
                                
                                $inputParam['telephone_no'] = trim($data[9]);
                                $inputParam['first_name'] = trim($data[10]);
                                $inputParam['last_name'] = trim($data[11]);
                                $inputParam['date_of_birth'] = inputDateFormat(trim($data[12]));
                                $inputParam['key_stage'] = trim($data[13]);
                                $inputParam['year_group'] = trim($data[14]);
                                /* code to get the ethnicity id */
                                $param = array();
                                $param['ethnicity'] = trim($data[15]);
                                $resultRecord = $this->userRepo->getEthnicity($param);
                                if (!empty($resultRecord)) {
                                    $inputParam['ethnicity'] = $resultRecord->id;
                                } else {
                                    $inputParam['ethnicity'] = '0';
                                }
                                /* end code to get the ethnicity id */
                                $inputParam['sen_provision'] = trim($data[16]);
                                
                                if(trim(strtolower($data[17])) == 'sen support')
                                    $inputParam['sen_provision_desc'] = 1;
                                else if(trim(strtolower($data[17])) == 'ehc plan')
                                    $inputParam['sen_provision_desc'] = 2;
                                    
                                $inputParam['sats_exempt'] = trim($data[18]);
                                
                                if (trim($data[19]) == PERCENTAGE || trim($data[19]) == SCORE) {
                                    $inputParam['ks1_average_point_score'] = array_search(trim($data[19]), baseLineScoreType());
                                } else {
                                    $inputParam['ks1_average_point_score'] = 3;
                                }
                                $inputParam['ks1_average_point_score_value'] = trim($data[20]);

                                if (trim($data[21]) == PERCENTAGE || trim($data[21]) == SCORE) {
                                    $inputParam['ks1_maths_baseline'] = array_search(trim($data[21]), baseLineScoreType());
                                } else {
                                    $inputParam['ks1_maths_baseline'] = 1;
                                }
                                $inputParam['ks1_maths_baseline_value'] = trim($data[22]);

                                if (trim($data[23]) == PERCENTAGE || trim($data[23]) == SCORE) {
                                    $inputParam['ks1_english_baseline'] = array_search(trim($data[23]), baseLineScoreType());
                                } else {
                                    $inputParam['ks1_english_baseline'] = 1;
                                }
                                $inputParam['ks1_english_baseline_value'] = trim($data[24]);

                                if (trim($data[25]) == PERCENTAGE || trim($data[25]) == SCORE) {
                                    $inputParam['ks2_maths_baseline'] = array_search(trim($data[25]), baseLineScoreType());
                                } else {
                                    $inputParam['ks2_maths_baseline'] = 1;
                                }
                                $inputParam['ks2_maths_baseline_value'] = trim($data[26]);

                                if (trim($data[27]) == PERCENTAGE || trim($data[27]) == SCORE) {
                                    $inputParam['ks2_english_baseline'] = array_search(trim($data[27]), baseLineScoreType());
                                } else {
                                    $inputParam['ks2_english_baseline'] = 1;
                                }
                                $inputParam['ks2_english_baseline_value'] = trim($data[28]);

                                if (trim($data[29]) == PERCENTAGE || trim($data[29]) == SCORE) {
                                    $inputParam['maths_target'] = array_search(trim($data[29]), baseLineScoreType());
                                } else {
                                    $inputParam['maths_target'] = 1;
                                }
                                $inputParam['maths_target_value'] = trim($data[30]);

                                if (trim($data[31]) == PERCENTAGE || trim($data[31]) == SCORE) {
                                    $inputParam['english_target'] = array_search(trim($data[31]), baseLineScoreType());
                                } else {
                                    $inputParam['english_target'] = 1;
                                }
                                $inputParam['english_target_value'] = trim($data[32]);

                                $inputParam['UPN'] = trim($data[33]);
                                if (!empty(trim($data[34]))) 
                                    $inputParam['date_of_entry'] = inputDateFormat(trim($data[34]));
                                else 
                                    $inputParam['date_of_entry'] = NULL_DATETIME;
                                
                                $inputParam['fsm_eligibility'] = trim($data[35]);
                                $inputParam['eal'] = trim($data[36]);

                                $inputParam['term_of_birth'] = trim($data[37]);

                                /* code to get the class id */
                                $param = array();
                                $param['class_name'] = trim($data[38]);
                                $param['school_id'] = decryptParam($inputs['school']);
                                $resultRecord = $this->userRepo->getClassName($param);
                                if (!empty($resultRecord)) {
                                    $inputParam['schoolclasses_id'] = $resultRecord->id;
                                } else {
                                    $inputParam['schoolclasses_id'] = '0';
                                }
                                /* end code to get the class id */

                                /* code to get the class id */
                                $param = array();
                                $inputParam['groups'] = array();
                                $groupIdArray = array();
                                $groupArray = explode(',', trim($data[39]));
                                if (!empty($groupArray)) {
                                    foreach ($groupArray as $gKey => $gVal) {
                                        $param['group_name'] = $gVal;
                                        $param['school_id'] = decryptParam($inputs['school']);
                                        $resultRecord = $this->userRepo->getGroupName($param);
                                        if (!empty($resultRecord)) {
                                            $groupIdArray[] = $resultRecord->id;
                                        }
                                    }
                                    /* end code to get the class id */
                                }
                                if (!empty($groupIdArray))
                                    $inputParam['groups'] = $groupIdArray;
                                else
                                    $inputParam['groups'] = array();
                                $inputParam['updated_by'] = session()->get('user')['id'];
                             //   asd($inputParam);
                                
                                $this->userRepo->importStudent($inputParam);
                                
                                $updateCountParam = array();
                                $updateCountParam['user_id'] = $inputParam['school'];
                                $this->userRepo->updateRemainingAccount($updateCountParam);
                                
                                $insertedRecords[] = 1;
                            }
                        }
                    }
                    $i++;
                }
                else{
                        $dataMsg['recordsCountError'] = "Remaining Number of Pupil Licenses: ".$remaining_no_of_student.". Please upgrade your subscription to add the more pupils.";
                    }
                }
            }
            // asd($alreadyExistsReocrds);
            $dataMsg['emptyRecords'] = $emptyRecords;
            $dataMsg['doesnotExistsReocrds'] = $doesnotExistsReocrds;
            $dataMsg['insertedRecords'] = $insertedRecords;
            $dataMsg['duplicateExistsReocrds'] = $duplicateExistsReocrds;
            $dataMsg['school_id'] = $inputs['school'];
            return view('admin.student.importstudent')->with($dataMsg);
            die;
        } else {
            return back()->with('error', "Please upload a valid file");
        }
    }

}
