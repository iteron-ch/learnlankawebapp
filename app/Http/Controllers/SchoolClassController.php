<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\GroupClassRepository;
use App\Http\Requests\SchoolClassRequest;
use Datatables;
use Illuminate\Http\Request;

class SchoolClassController extends Controller {

    /**
     * The GroupClassRepository instance.
     *
     * @var App\Repositories\GroupClassRepository
     */
    protected $groupClassRepo;

    /**
     * The UserRepository instance.
     *
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * Create a new SchoolClass instance.
     * @param  App\Repositories\SchoolClassRepository 
     * @param  App\Repositories\UserRepository 
     * @return void
     */
    public function __construct(GroupClassRepository $groupClassRepo, UserRepository $userRepo) {
        $this->groupClassRepo = $groupClassRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * @author     Icreon Tech  - dev5.
     * @return Response
     */
    public function index() {
        $data['status'] = ['' => trans('admin/admin.select_option')] + statusArray();
        $year = ['' => trans('admin/admin.select_option')] + array(1 => 'YG 1', 2 => 'YG 2', 3 => 'YG 3', 4 => 'YG 4', 5 => 'YG 5', 6 => 'YG 6', 7 => 'YG 7', 8 => 'YG 8', 9 => 'YG 9', 10 => 'YG 10', 11 => 'YG 11');
        $data['year'] = ['' => trans('admin/admin.select_option')] + $year;
        return view('admin.schoolclass.schoolclasslist', $data);
    }

    /**
     * Display a listing of the SchoolClass.
     * @author     Icreon Tech  - dev5.
     * @return Response
     */
    public function listRecord(Request $request) {
        $params['created_by'] = session()->get('user')['id'];
        $schoolclasses = $this->groupClassRepo->getSchoolclassesList($params);

        unset($params);
        return Datatables::of($schoolclasses)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('class_name')) {
                                $query->where('class_name', 'like', "%{$request->get('class_name')}%");
                            }
                            if ($request->has('year')) {
                                $query->where('year', 'like', "%{$request->get('year')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('status', '=', "{$request->get('status')}");
                            }
                        })
                        ->editColumn('class_name', function ($schoolclass) {
                            if ($schoolclass->cnt_student)
                                return '<a href="' . route('manageclass.classstudents', array('id' => encryptParam($schoolclass->id))) . '">' . $schoolclass->class_name . '</a>';
                            else
                                return $schoolclass->class_name;
                        })
                        ->editColumn('cnt_student', function ($schoolclass) {
                            if ($schoolclass->cnt_student)
                                return '<a href="' . route('manageclass.classstudents', array('id' => encryptParam($schoolclass->id))) . '">' . $schoolclass->cnt_student . '</a>';
                            else
                                return '0';
                        })
                        ->addColumn('action', function ($schoolclass) {
                            return '<a href="javascript:void(0);" data-remote="' . route('manageclass.show', encryptParam($schoolclass->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('manageclass.edit', encryptParam($schoolclass->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($schoolclass->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>
                                    <a href="' . route('manageclass.edit', encryptParam($schoolclass->id)) . '"  alt="Add Student" title="Add Student"><i class="glyphicon glyphicon-plus"></i></a>
                                    <a href="/adminreport/classgap?schoolId=' . $schoolclass->class_id . '" alt="Gap Analysis" title="Gap Analysis"><i class="glyphicon glyphicon-tasks"></i></a>
                                    <a href="/adminreport/classreport?id=' . $schoolclass->id . '&report=dashboard&tab=math"  alt="Class Report" title="Class Report"><i class="glyphicon glyphicon-stats"></i></a>';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new class.
     * @author     Icreon Tech  - dev5.
     * @return Response
     */
    public function create() {
        $params['user_type'] = STUDENT;
        $params['school_id'] = session()->get('user')['id'];
        $data['studentsData'] = $this->userRepo->schoolStudentsList($params);
        unset($params);
        $year = ['' => trans('admin/admin.select_option')] + allYearGroups();
        $data['year'] = ['' => trans('admin/admin.select_option')] + $year;
        $data['status'] = array('' => trans('admin/admin.select_option')) + statusArray();
        $data['page_title'] = trans('admin/schoolclass.add_template');
        $data['page_heading'] = trans('admin/schoolclass.manage_classes');
        $data['trait'] = array('trait_1' => trans('admin/schoolclass.template_heading'), 'trait_1_link' => route('manageclass.index'), 'trait_2' => trans('admin/schoolclass.add_template'));
        $data['JsValidator'] = 'App\Http\Requests\SchoolClassRequest';
        return view('admin.schoolclass.create', $data);
    }

    /**
     * Store a newly created class Record in storage.
     * @author     Icreon Tech  - dev5.
     * @param  Request  $request
     * @return Response
     */
    public function store(SchoolClassRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->groupClassRepo->storeClass($inputs);
        return redirect(route('manageclass.index'))->with('ok', trans('admin/schoolclass.added_successfully'));
    }

    /**
     * Display the specified class record.
     * @author     Icreon Tech  - dev5.
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $schoolClass = $this->groupClassRepo->getSchoolClass(decryptParam($id));
        $schoolClass->students = $this->groupClassRepo->getSchoolClassStudents($schoolClass->id);
        $schoolClass->studentsNum = count($schoolClass->students);
        return view('admin.schoolclass.show', compact('schoolClass'));
    }

    /**
     * Show the form for editing the specified class record.
     * @author     Icreon Tech  - dev5.
     * @param  int  $id
     * @return Response
     */
    public function edit($id, Request $request) {
        if (!empty($request->segment(3)))
            $data['pgAct'] = $request->segment(3);
        else
            $data['pgAct'] = '';
        $id = decryptParam($id);
        $schoolClass = $this->groupClassRepo->getSchoolClass($id)->toArray();
        $data['schoolClass'] = $schoolClass;
        $data['schoolClass']['selected_students'] = $this->groupClassRepo->schoolClassStudentIds($schoolClass['id']);
        $current_id = session()->get('user')['id'];
        $year = ['' => trans('admin/admin.select_option')] + allYearGroups();
        $data['year'] = ['' => trans('admin/admin.select_option')] + $year;
        $params['user_type'] = STUDENT;
        $params['school_id'] = session()->get('user')['id'];
        $data['studentsData'] = $this->userRepo->schoolStudentsList($params);
        unset($params);

        $data['status'] = array('' => trans('admin/admin.select_option')) + statusArray();
        $data['page_title'] = trans('admin/schoolclass.edit_template');
        $data['page_heading'] = trans('admin/schoolclass.manage_classes');
        $data['trait'] = array('trait_1' => trans('admin/schoolclass.template_heading'), 'trait_1_link' => route('manageclass.index'), 'trait_2' => trans('admin/schoolclass.edit_template'));
        $data['JsValidator'] = 'App\Http\Requests\SchoolClassRequest';
        return view('admin.schoolclass.edit', $data);
    }

    /**
     * Update the specified class record in storage.
     * @author     Icreon Tech  - dev5.
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(SchoolClassRequest $request, $id) {
        $inputs = $request->all();
        $id = decryptParam($id);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->groupClassRepo->updateClass($inputs, $id);
        return redirect(route('manageclass.index'))->with('ok', trans('admin/schoolclass.updated_successfully'));
    }

    /**
     * Delete a class 
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->groupClassRepo->destroySchoolClass($inputs, $id);
        return response()->json();
    }

    public function classStudents($id) {
        $data = array();
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        $data['id'] = $id;
        return view('admin.schoolclass.studentlist', $data);
    }

    public function classStudentListRecord($id, Request $request) {

        $params['user_type'] = STUDENT;
        $classId = decryptParam($id);
        $params['id'] = $classId;
        $users = $this->groupClassRepo->getClassesStudentList($params);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {

                            if ($request->has('first_name')) {
                                $query->where('first_name', 'like', "%{$request->get('first_name')}%");
                            }
                            if ($request->has('last_name')) {
                                $query->where('last_name', 'like', "%{$request->get('last_name')}%");
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
                                        <a href="' . route('student.edit', encryptParam($user->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>';
                        })
                        ->editColumn('created_at', function ($user) {
                            return $user->created_at ? outputDateFormat($user->created_at) : '';
                        })
                        ->editColumn('name', function ($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->make(true);
    }
    
    public function teacherClassStudent(Request $request){
        $inputs = $request->all();
        $inputs['teacher_id'] = session()->get('user')['id'];
        $inputs['multidata'] = TRUE;
        $teacherClassStudent = $this->groupClassRepo->teacherClassStudent($inputs);
        return response()->json($teacherClassStudent);
    }

}
