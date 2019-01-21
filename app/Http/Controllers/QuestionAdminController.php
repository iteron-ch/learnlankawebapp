<?php

/**
 * This controller is used for QuestionAdmin.
 * @package    QuestionAdmin
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\QuestionAdminRepository;
use App\Http\Requests\QuestionAdmin\QuestionAdminCreateRequest;
use App\Http\Requests\QuestionAdmin\QuestionAdminUpdateRequest;
use App\Http\Requests\QuestionAdmin\QuestionAdminUpdateProfileRequest;
use Illuminate\Http\Request;
use App\Models\QuestionAdmin;
use DB;
use Datatables;

/**
 * This controller is used for questionadmin.
 * @author     Icreon Tech - dev5.
 */
class QuestionAdminController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\QuestionAdminRepository
     */
    protected $questionAdminRepo;

    /**
     * Create a new QuestionAdminController instance.
     * @param  App\Repositories\QuestionAdminRepository $questionAdminRepo
     * @return void
     */
    public function __construct(QuestionAdminRepository $questionAdminRepo) {
        $this->questionAdminRepo = $questionAdminRepo;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Display QuestionAdmin listing page.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function index() {
//        $status = ['' => trans('questionadmin/questionadmin.select_option')] + statusArray();
//        $data['status'] = $status;
        return view('admin.questionadmin.questionadminlist');
    }

    /**
     * Get record for Question Admin list
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $param['user_type'] = QUESTIONADMIN;
        $questionAdminRepo = $this->questionAdminRepo->getQuestionAdmin($param);
        return Datatables::of($questionAdminRepo)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('first_name')) {
                                $query->where('first_name', 'like', "%{$request->get('first_name')}%");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('username')) {
                                $query->where('username', 'like', "%{$request->get('username')}%");
                            }
                            
                        })
                       
                        ->addColumn('action', function ($questionAdminRepo) {
                            return '<a href="javascript:void(0);" data-remote="' . route('questionadmin.show', ($questionAdminRepo->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('questionadmin.edit', encryptParam($questionAdminRepo->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($questionAdminRepo->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                        ->editColumn('last_login', function ($questionAdminRepo) {
                            return $questionAdminRepo->last_login ? outputDateTimeFormat($questionAdminRepo->last_login) : '';
                        })
                        ->editColumn('question_count', function ($questionAdminRepo) {
                            return $questionAdminRepo->question_count ? $questionAdminRepo->question_count : '0';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new QuestionAdmin.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function create() {
        $data['page_heading'] = trans('admin/questionadmin.add_admin');
        $data['page_title'] = trans('admin/questionadmin.add_admin');
        $data['trait'] = array('trait_1' => trans('admin/questionadmin.questionadmin'), 'trait_1_link' => route('questionadmin.index'), 'trait_2' => trans('admin/questionadmin.add_admin'));
        $data['JsValidator'] = 'App\Http\Requests\QuestionAdmin\QuestionAdminCreateRequest';
        return view('admin.questionadmin.create', $data);
    }

    /**
     * Insert a new the QuestionAdmin
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\QuestionAdmin\QuestionAdminCreateRequest $request
     * @return Response
     */
    public function store(QuestionAdminCreateRequest $request) {
        $inputs = $request->all();
        if ($request->file('image')) {
            $inputs['image'] = $this->questionAdminRepo->userImageUpload($request->file('image'));
        }
        $inputs['user_type'] = QUESTIONADMIN;
        $inputs['created_by'] = session()->get('user')['id'];
        $this->questionAdminRepo->store($inputs);
        return redirect(route('questionadmin.index'))->with('ok', trans('admin/questionadmin.added_successfully'));
    }

    /**
     * Show the QuestionAdmin detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $param['user_type'] = QUESTIONADMIN;
        $questionAdminRepo = $this->questionAdminRepo->showQuestionAdmin($id);
        return view('admin.questionadmin.show', compact('questionAdminRepo'));
    }

    /**
     * Show the form for edit QuestionAdmin.
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $questionAdminRepo = QuestionAdmin::findOrFail($id)->toArray();
        $questionAdminRepo['date_of_birth'] = outputDateFormat($questionAdminRepo['date_of_birth']);
        $data['QuestionAdminRepo'] = $questionAdminRepo;
        $data['page_heading'] = trans('admin/questionadmin.manage_questionadmin');
        $data['page_title'] = trans('admin/questionadmin.edit_questionadmin');
        $data['trait'] = array('trait_1' => trans('admin/questionadmin.questionadmin'), 'trait_1_link' => route('questionadmin.index'), 'trait_2' => trans('admin/questionadmin.edit_questionadmin'));
        $data['JsValidator'] = 'App\Http\Requests\Tutor\TutorUpdateRequest';
        $data['fileinput_preview'] = !empty($questionAdminRepo['image']) ? route('userimg', ['file' => $questionAdminRepo['image'], 'size' => 'large']) : '';
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        return view('admin.questionadmin.edit')->with($data);
    }

    /**
     * Update the QuestionAdmin.
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\QuestionAdmin\QuestionAdminUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(QuestionAdminUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['user_type'] = QUESTIONADMIN;
        $userDeleteImage = FALSE;
        if (isset($inputs['image']) && empty($inputs['image'])) {
            $userDeleteImage = TRUE;
        }
        if ($request->file('image')) {
            $userDeleteImage = TRUE;
            $inputs['image'] = $this->questionAdminRepo->userImageUpload($request->file('image'));
        }
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->questionAdminRepo->update($inputs, $id, $userDeleteImage);
        return redirect(route('questionadmin.index'))->with('ok', trans('admin/questionadmin.updated_successfully'));
    }

    /**
     * Delete a QuestionAdmin 
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['status'] = DELETED;
        $inputs['id'] = $id;
        $this->questionAdminRepo->destroyAdmin($inputs, $id);
        return response()->json();
    }

    /**
     * Display QuestionAdmin edit profile form
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function editProfile() {
        $id = session()->get('user')['id'];
        $user = User::findOrFail($id)->toArray();
        $user['date_of_birth'] = outputDateFormat($user['date_of_birth']);
        $data['user'] = $user;
        $county = ['' => 'Select'] + County::getCounty();
        $data['county'] = $county;
        $country = ['' => 'Select'] + Country::getCountry();
        $data['country'] = $country;
        $howfind = ['' => 'Select'] + Howfind::getHowFind();
        $data['howfind'] = $howfind;
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        return view('admin.tutor.editprofile')->with($data);
    }

    /**
     * Update QuestionAdmin profile
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\QuestionAdmin\QuestionAdminUpdateProfileRequest $request
     * @return Response
     */
    public function updateProfile(TutorUpdateProfileRequest $request) {
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
        $this->userRepo->updateTutorProfile($inputs, $id, $userDeleteImage);
        return redirect(route('myaccount'))->with('ok', trans('admin/admin.profile_updated_successfully'));
    }

}
