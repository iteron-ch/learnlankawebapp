<?php

namespace App\Http\Controllers;

use App\Repositories\QuestionSetRepository;
use Illuminate\Http\Request;
use App\Http\Requests\Questionset\QuestionsetCreateRequest;
use App\Http\Requests\Questionset\QuestionsetUpdateRequest;
use App\Models\Questionset;
use Datatables;

class QuestionSetController extends Controller {

    /**
     * The QuestionSetRepository instance.
     *
     * @var App\Repositories\QuestionSetRepository
     */
    protected $questionRepo;

    /**
     * Create a new QuestionSetController instance.
     *
     * @param  App\Repositories\UserRepository $questionRepo
     * @return void
     */
    public function __construct(QuestionSetRepository $questionRepo) {
        $this->questionRepo = $questionRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + questionKeyStage();
        $data['setSubject'] = array('' => trans('admin/admin.select_option')) + questionSetSubjects();
        $data['setGroup'] = array('' => trans('admin/admin.select_option')) + questionSetGroups();
        $data['status'] = array('' => trans('admin/admin.select_option')) +  array('Published' => 'Published', 'Unpublished' => 'Unpublished');
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        return view('admin.questionset.questionsetlist', $data);
    }

    public function listRecord(Request $request) {
        $questionset = $this->questionRepo->getQuestionset();
        return Datatables::of($questionset)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('key_stage')) {
                                $query->where(function ($query) use ($request) {
                                    $query->where('ks_id', '=', "{$request->get('key_stage')}");
                                });
                            }
                            if ($request->has('year_group')) {
                                $query->where(function ($query) use ($request) {
                                    $query->where('year_group', '=', "{$request->get('year_group')}%");
                                });
                            }
                            if ($request->has('subject')) {
                                $query->where(function ($query) use ($request) {
                                    $query->where('subject', '=', "{$request->get('subject')}");
                                });
                            }
                            if ($request->has('set_name')) {
                                $query->where('set_name', 'like', "%{$request->get('set_name')}%");
                            }
                            if ($request->has('set_group')) {
                                $query->where(function ($query) use ($request) {
                                    $query->where('set_group', '=', "{$request->get('set_group')}");
                                });
                            }
                            if ($request->has('status')) {
                                $query->where(function ($query) use ($request) {
                                    $query->where('status', '=', "{$request->get('status')}");
                                });
                            }
                        })
                        ->addColumn('action', function ($questionset) {
                            $actions =  '<div class="btn-group">';
                                $actions .= '<a alt="' . trans("admin/questionset.add_question") . '" title="' . trans("admin/questionset.add_question") . '" href="' . route('questionbuilder.create',['setid'=>encryptParam($questionset->id)]) . '"><i class="glyphicon glyphicon-plus"></i> </a>'; 
                                    
                                    $actions .='<a alt="' . trans("admin/admin.view") . '" title="' . trans("admin/admin.view") . '" href="javascript:void(0);" data-remote="' . route('questionset.show', $questionset->id) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a alt="' . trans("admin/admin.edit") . '" title="' . trans("admin/admin.edit") . '" href="' . route('questionset.edit', $questionset->id) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a alt="' . trans("admin/admin.delete") . '" title="' . trans("admin/admin.delete") . '" href="javascript:void(0);" data-id="' . $questionset->id . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i> </a>
                                    </div>';
                                    return $actions;
                        })
                        ->editColumn('created_at', function ($questionset) {
                            return $questionset->created_at ? outputDateFormat($questionset->created_at) : '';
                        })
                        ->editColumn('allow_print', function ($questionset) {
                            return $questionset->is_print ? 'Yes' : 'No';
                        })
                        ->make(true);
    }
    
     /**
     * Show the form for creating a new question.
     *
     * @return Response
     */
    public function create() {
        $data['page_title'] = trans('admin/questionset.add_questionset');
        $data['trait'] = array('trait_1' => trans('admin/questionset.question_set'), 'trait_1_link' => route('questionset.index'), 'trait_2' => trans('admin/questionset.add_questionset'));
        $data['JsValidator'] = 'App\Http\Requests\Questionset\QuestionsetCreateRequest';
        $data['keyStage'] =  array('' => trans('admin/admin.select_option')) + questionKeyStage();
        $data['setSubject'] = array('' => trans('admin/admin.select_option')) + questionSetSubjects();
        $data['setGroup'] = array('Test' => TEST);
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        $data['status'] = array('' => trans('admin/admin.select_option')) + questionStatus();
        return view('admin.questionset.create', $data);
    }

    /**
     * Store a newly created QuestionSet in storage.
     *
     * @param  App\requests\StudentCreateRequest $request
     *
     * @return Response
     */
    public function store(QuestionsetCreateRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->questionRepo->store($inputs);
        return redirect(route('questionset.index'))->with('ok', trans('admin/questionset.added_successfully'));
    }

    

    public function edit($id) {
        $id = $id;
        $questionset = $this->questionRepo->getById($id)->toArray();
        /*$paperwiseSetQuestions = $this->questionRepo->getPaperwisePublishedSetQuestions(array(
            'question_set_id' => $questionset['id'],
            'key_stage' => $questionset['ks_id'],
            'year_group' => $questionset['year_group'],
        ));
        $data['paperwiseSetQuestions'] = $paperwiseSetQuestions;
        $data['subjectPapers'] = subjectPapers();
         * 
         */
        $data['questionset'] = $questionset;
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + questionKeyStage();
        $data['setSubject'] = array('' => trans('admin/admin.select_option')) + questionSetSubjects();
        $data['setGroup'] = array('Test' => TEST);
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        $data['status'] = array('' => trans('admin/admin.select_option')) +  array('Published' => 'Published', 'Unpublished' => 'Unpublished');
        $data['page_title'] = trans('admin/questionset.question_set');
        $data['trait'] = array('trait_1' => trans('admin/questionset.question_set'), 'trait_1_link' => route('questionset.index'), 'trait_2' => trans('admin/questionset.edit_questionset'));
        $data['JsValidator'] = 'App\Http\Requests\Questionset\QuestionsetUpdateRequest';
        return view('admin.questionset.edit')->with($data);
    }

    public function update(QuestionsetUpdateRequest $request, $id) {
        $inputs = $request->all();
        $inputs['id'] = $id;
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->questionRepo->update($inputs, $id);
        return redirect(route('questionset.index'))->with('ok', trans('admin/questionset.updated_successfully'));
    }

    
    /* Show the form for editing a questionset.
     * 
     */
    public function show($id) {
        $questionset = $this->questionRepo->showQuestionset($id);
        $keyStage = questionKeyStage();
        $questionset->year_group = questionYearGroup()[$questionset->ks_id][$questionset->year_group];
        $questionset->ks_id = $keyStage[$questionset->ks_id];
        return view('admin.questionset.show', compact('questionset'));
    }
    
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = $inputs['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        //$inputs['status'] = DELETED;
        $inputs['id'] = $id;
        $this->questionRepo->destroyQuestionset($inputs, $id);
        return response()->json();
    }

    /**
     * This is used to assign the tests et to the students
     * @param Request $request
     * @return type
     */
    public function assignTestSet() {
        $data['page_title'] = 'Test Set';
        $data['JsValidator'] = 'App\Http\Requests\QuestionsetCreateRequest';
        $data['trait'] = array('trait_1' => 'Test Set');
        return view('admin.questionset.assigntestset', $data);
    }

}
