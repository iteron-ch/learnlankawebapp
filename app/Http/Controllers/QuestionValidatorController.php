<?php

/**
 * This controller is used for questionValiRepo.
 * @package    QuestionValidator
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\QuestionValidatorRepository;
use App\Http\Requests\QuestionValidator\QuestionValidatorCreateRequest;
use App\Http\Requests\QuestionValidator\QuestionValidatorUpdateRequest;
use App\Http\Requests\QuestionAdmin\QuestionValidatorProfileRequest;
use Illuminate\Http\Request;
use App\Models\QuestionValidator;
use DB;
use Datatables;

/**
 * This controller is used for QuestionValidator.
 * @author     Icreon Tech - dev5.
 */
class QuestionValidatorController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\QuestionValidatorRepository
     */
    protected $questionValiRepo;

    /**
     * Create a new QuestionAdminController instance.
     * @param  App\Repositories\QuestionValidatorRepository $questionAdminRepo
     * @return void
     */
    public function __construct(QuestionValidatorRepository $questionValiRepo) {
        $this->questionValiRepo = $questionValiRepo;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Display QuestionAdmin listing page.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function index() { 
        return view('admin.questionvalidator.questionvalidatorlist');
    }

    /**
     * Get record for QuestionValidator list
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) { 
        $param['user_type'] = QUESTIONVALIDATOR;
        $questionAdminRepo = $this->questionValiRepo->getQuestionValidator($param);
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
                            return '<a href="javascript:void(0);" data-remote="' . route('questionvalidator.show', ($questionAdminRepo->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('questionvalidator.edit', encryptParam($questionAdminRepo->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($questionAdminRepo->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                       ->editColumn('last_login', function ($questionAdminRepo) {
                            return $questionAdminRepo->last_login ? outputDateTimeFormat($questionAdminRepo->last_login) : '';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new QuestionValidator.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function create() {
        $data['page_heading'] = trans('admin/questionvalidator.manage_questionvalidator');
        $data['page_title'] = trans('admin/questionvalidator.add_questionvalidator');
        $data['trait'] = array('trait_1' => trans('admin/questionvalidator.questionvalidator'), 'trait_1_link' => route('questionvalidator.index'), 'trait_2' => trans('admin/questionvalidator.add_questionvalidator'));
        $data['JsValidator'] = 'App\Http\Requests\QuestionValidator\QuestionValidatorCreateRequest';
        return view('admin.questionvalidator.create', $data);
    }

    /**
     * Insert a new the QuestionValidator
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\QuestionValidator\QuestionValidatorCreateRequest $request
     * @return Response
     */
    public function store(QuestionValidatorCreateRequest $request) {
        $inputs = $request->all();
        if ($request->file('image')) {
            $inputs['image'] = $this->questionValiRepo->userImageUpload($request->file('image'));
        }
        $inputs['user_type'] = QUESTIONVALIDATOR;
        $inputs['created_by'] = session()->get('user')['id'];
        $this->questionValiRepo->store($inputs);
        return redirect(route('questionvalidator.index'))->with('ok', trans('admin/questionvalidator.added_successfully'));
    }

    /**
     * Show the QuestionValidator detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $param['user_type'] = QUESTIONVALIDATOR;
        $questionAdminRepo = $this->questionValiRepo->showQuestionAdmin($id);
        return view('admin.questionvalidator.show', compact('questionAdminRepo'));
    }

    /**
     * Show the form for edit QuestionValidator.
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $questionvalidator = QuestionValidator::findOrFail($id)->toArray();
        $questionvalidator['date_of_birth'] = outputDateFormat($questionvalidator['date_of_birth']);
        $data['QuestionAdminRepo'] = $questionvalidator;
        $data['page_heading'] = trans('admin/questionvalidator.manage_questionvalidator');
        $data['page_title'] = trans('admin/questionvalidator.edit_questionvalidator');
        $data['trait'] = array('trait_1' => trans('admin/questionvalidator.questionvalidator'), 'trait_1_link' => route('questionvalidator.index'), 'trait_2' => trans('admin/questionvalidator.edit_questionvalidator'));
        $data['JsValidator'] = 'App\Http\Requests\QuestionValidator\QuestionValidatorUpdateRequest';
        $data['fileinput_preview'] = !empty($questionvalidator['image']) ? route('userimg', ['file' => $questionvalidator['image'], 'size' => 'large']) : '';
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        return view('admin.questionvalidator.edit')->with($data);
    }

    /**
     * Update the QuestionValidator.
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\QuestionValidator\QuestionValidatorUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(QuestionValidatorUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['user_type'] = QUESTIONVALIDATOR;
        $userDeleteImage = FALSE;
        if (isset($inputs['image']) && empty($inputs['image'])) {
            $userDeleteImage = TRUE;
        }
        if ($request->file('image')) {
            $userDeleteImage = TRUE;
            $inputs['image'] = $this->questionValiRepo->userImageUpload($request->file('image'));
        }
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->questionValiRepo->update($inputs, $id, $userDeleteImage);
        return redirect(route('questionvalidator.index'))->with('ok', trans('admin/questionvalidator.updated_successfully'));
    }

    /**
     * Delete a QuestionValidator 
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
        $this->questionValiRepo->destroyAdmin($inputs, $id);
        return response()->json();
    }

    /**
     * Display QuestionValidator edit profile form
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
     * Update QuestionValidator profile
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\QuestionValidator\QuestionValidatorUpdateProfileRequest $request
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
