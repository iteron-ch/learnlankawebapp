<?php

/**
 * This controller is used for Groupss.
 * @package    group
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\GroupClassRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\GroupRequest;
use Illuminate\Http\Request;
use Datatables;

/**
 * This controller is used for Group.
 * @author     Icreon Tech - dev5.
 */
class GroupController extends Controller {

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
     * Create a new GroupController instance.
     * @param  App\Repositories\GroupRepository groupRepo
     * @param  App\Repositories\UserRepository 
     * @return void
     */
    public function __construct(GroupClassRepository $groupClassRepo, UserRepository $userRepo) {
        $this->groupClassRepo = $groupClassRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $data['status'] = ['' => trans('admin/admin.select_option')] + statusArray();
        return view('admin.group.grouplist', $data);
    }

    /**
     * Get record for Group list
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $params['created_by'] = session()->get('user')['id'];
        $groups = $this->groupClassRepo->getGroupList($params);
        unset($params);
        return Datatables::of($groups)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('group_name')) {
                                $query->where('group_name', 'like', "%{$request->get('group_name')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($groups) {
                            return '<a href="javascript:void(0);" data-remote="' . route('managegroup.show', encryptParam($groups->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('managegroup.edit', encryptParam($groups->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($groups->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>
                                        <a href="' . route('managegroup.edit', encryptParam($groups->id)) . '" alt="Add Student" title="Add Student"><i class="glyphicon glyphicon-plus"></i></a>';
                        })
                        ->editColumn('cnt_student', function ($groups) {
                            if ($groups->cnt_student)
                                return '<a href="' . route('managegroup.grouptudents', array('id' => encryptParam($groups->id))) . '">' . $groups->cnt_student . '</a>';
                            else
                                return '0';
                        })
                        ->editColumn('group_name', function ($groups) {
                            if ($groups->cnt_student)
                                return '<a href="' . route('managegroup.grouptudents', array('id' => encryptParam($groups->id))) . '">' . $groups->group_name . '</a>';
                            else
                                return $groups->group_name;
                        })
                        ->editColumn('created_at', function ($groups) {
                            return $groups->created_at ? outputDateFormat($groups->created_at) : '';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new Group.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function create() {
        $params['user_type'] = STUDENT;
        $params['school_id'] = session()->get('user')['id'];
        $data['studentsData'] = $this->userRepo->schoolStudentsList($params);
        unset($params);
        $data['page_heading'] = trans('admin/group.manage_groups');
        $data['page_title'] = trans('admin/group.add_group');
        $data['trait'] = array('trait_1' => trans('admin/group.template_heading'), 'trait_1_link' => route('managegroup.index'), 'trait_2' => trans('admin/group.add_group'));
        $data['JsValidator'] = 'App\Http\Requests\GroupRequest';
        $data['status'] = array('' => trans('admin/admin.select_option')) + statusArray();
        return view('admin.group.create', $data);
    }

    /**
     * Insert a new the Group
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Group\GroupCreateRequest $request
     * @return Response
     */
    public function store(GroupRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->groupClassRepo->storeGroup($inputs);
        return redirect(route('managegroup.index'))->with('ok', trans('admin/group.added_successfully'));
    }

    /**
     * Show the Group detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $group = $this->groupClassRepo->getGroup(decryptParam($id));
        $group->students = $this->groupClassRepo->getGroupStudents($group->id);
        $group->studentsNum = count($group->students);
        return view('admin.group.show', compact('group'));
    }

    /**
     * Show the form for edit Group.
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function edit($id, Request $request) {
        if (!empty($request->segment(3)))
            $data['pgAct'] = $request->segment(3);
        else
            $data['pgAct'] = '';

        $id = decryptParam($id);
        $group = $this->groupClassRepo->getGroup($id)->toArray();
        $data['group'] = $group;
        $data['group']['selected_students'] = $this->groupClassRepo->groupStudentIds($group['id']);

        $params['user_type'] = STUDENT;
        $params['school_id'] = session()->get('user')['id'];
        $data['studentsData'] = $this->userRepo->schoolStudentsList($params);
        unset($params);

        $data['status'] = array('' => trans('admin/admin.select_option')) + statusArray();
        $data['page_heading'] = trans('admin/group.manage_groups');
        $data['page_title'] = trans('admin/group.edit_group');
        $data['trait'] = array('trait_1' => trans('admin/group.template_heading'), 'trait_1_link' => route('managegroup.index'), 'trait_2' => trans('admin/group.edit_group'));
        $data['JsValidator'] = 'App\Http\Requests\GroupRequest';
        return view('admin.group.edit')->with($data);
    }

    /**
     * Update the Group.
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Group\GroupUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(GroupRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->groupClassRepo->updateGroup($inputs, $id);
        return redirect(route('managegroup.index'))->with('ok', trans('admin/group.updated_successfully'));
    }

    /**
     * Delete a Group 
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->groupClassRepo->destroyGroup($inputs, $id);
        return response()->json();
    }

    public function groupStudents($id) {
        $data = array();
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        $data['id'] = $id;
        return view('admin.group.studentlist', $data);
    }

    public function groupStudentListRecord($id, Request $request) {

        $params['user_type'] = STUDENT;
        $classId = decryptParam($id);
        $params['id'] = $classId;
        $users = $this->groupClassRepo->getGroupStudentList($params);
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
    
    public function teacherGroupStudent(Request $request){
        $inputs = $request->all();
        $inputs['teacher_id'] = session()->get('user')['id'];
        $inputs['multidata'] = TRUE;
        $teacherGroupStudent = $this->groupClassRepo->teacherGroupStudent($inputs);
        return response()->json($teacherGroupStudent);
    }

}
