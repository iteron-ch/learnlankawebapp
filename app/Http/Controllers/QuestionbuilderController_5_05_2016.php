<?php

/**
 * This controller is used for Question builder.
 * @package    Questionbuilder
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Controllers;

use App\Repositories\StrandRepository;
use App\Repositories\QuestionbuilderRepository;
use Illuminate\Http\Request;
use App\Models\User;
use Datatables;
use DB;

/**
 * This controller is used for Question builder.
 * @package    Questionbuilder
 * @author     Icreon Tech  - dev2.
 */
class QuestionbuilderController extends Controller {

    /**
     * The QuestionbuilderRepository instance.
     * @var App\Repositories\QuestionbuilderRepository
     */
    protected $questionBuilderRepo;
    public $strandRepo;

    /**
     * Create a new QuestionController instance.
     *
     * @param  App\Repositories\QuestionbuilderRepository $questionBuilderRepo
     * @return void
     */
    public function __construct(QuestionbuilderRepository $questionBuilderRepo, StrandRepository $strandRepo) {
        $this->questionBuilderRepo = $questionBuilderRepo;
        $this->strandRepo = $strandRepo;
    }

    /**
     * Display question builder listing page.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function index() {
        $questionBuilderData = $this->questionBuilderRepo->getQuestionBuilderData();
        $data['setSubject'] = array('' => trans('admin/admin.select_option')) + $questionBuilderData['subject'];
        $data['setGroup'] = array('' => trans('admin/admin.select_option')) + $questionBuilderData['setgroup'];
        $data['yearKeys'] = array('' => trans('admin/admin.select_option')) + $questionBuilderData['yeargroup'];
        $data['status'] = array('' => trans('admin/admin.select_option')) + questionStatus();
        $data['questionType'] = questionTypeList();

        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + $questionBuilderData['keystage'];
        $data['questionDifficulty'] = array('' => trans('admin/admin.select_option')) + $questionBuilderData['difficulty'];
        $data['setgroup'] = array('' => trans('admin/admin.select_option')) + $questionBuilderData['setgroup'];
        $data['yearKeysJson'] = json_encode($questionBuilderData['yeargroup']);
        $yearGroups = array('' => trans('admin/admin.select_option')) + allYearGroups();
        $data['yearGroups'] = $yearGroups;
        $data['paperJson'] = json_encode($questionBuilderData['paper']);
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        $data['strands'] = json_encode($arrStrands['strands']);
        $data['substrands'] = json_encode($arrStrands['substrands']);
        $data['questionType'] = json_encode($data['questionType']);

        foreach ($questionBuilderData['questionSet'] as $key => $val) {
            $questionSetFullArray[$val['id']] = $val['set_name'];
        }
        $data['questionSets'] = json_encode($questionBuilderData['questionSet']);
        $data['questionSetsArray'] = $questionBuilderData['questionSet'];
        asort($questionSetFullArray);
        $data['questionSetFullArray'] = array('' => trans('admin/admin.select_option')) + $questionSetFullArray;

        $userResult = User::getQuestionBuilderUsers();
        $questionBuilderUsers = array();
        foreach ($userResult as $key => $val) {
            $questionBuilderUsers[$val->id] = trim($val->first_name . ' ' . $val->last_name);
        }
        $data['userArray'] = array('' => trans('admin/admin.select_option')) + $questionBuilderUsers;

        $userResult1 = User::getQuestionValidatorUsers(array());
        $questionValidatorUsers = array();
        foreach ($userResult1 as $key => $val) {
            $questionValidatorUsers[$val->id] = trim($val->first_name . ' ' . $val->last_name);
        }
        
        $data['userArrayValidator'] = array('' => trans('admin/admin.select_option')) + $questionValidatorUsers;
        $data['validateReason'] = questionValidateReasonList();
        $data['validateStage'] = array('' => trans('admin/admin.select_option')) + validateStage();
        $data['userType'] = array('' => trans('admin/admin.select_option')) + array(ADMIN => 'Super Admin', QUESTIONADMIN => 'Question Builder');
        return view('admin.questionbuilder.questionbuilderlist', $data);
    }

    /**
     * Get record for question builder list
     * @author     Icreon Tech - dev2.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $keyStage = questionKeyStage();
        $status = questionStatus();
        $yearGroup = questionYearGroup();
        $paper = subjectPapers();
        $questionType = questionTypeList();
        $questionDifficulty = questionDifficulty();
        $searchParam = array();
        if (session()->get('user')['user_type'] == QUESTIONADMIN) {
            $searchParam['loggedin_user'] = session()->get('user')['id'];
        }
        if (session()->get('user')['user_type'] == QUESTIONVALIDATOR) {
            $searchParam['validator_user'] = session()->get('user')['id'];
        }
        $question = $this->questionBuilderRepo->getQuestiontList($searchParam);
        return Datatables::of($question)
                        ->filter(function ($query) use ($request) {

                            if ($request->has('questionType')) {
                                $query->where('question_type_id', '=', "{$request->get('questionType')}");
                            }
                            if ($request->has('created_by')) {
                                $query->where('questions.created_by', '=', "{$request->get('created_by')}");
                            }
                            if ($request->has('user_type')) {
                                $query->where('user.user_type', '=', "{$request->get('user_type')}");
                            }
                            if ($request->has('question_set')) {
                                $query->where('question_set_id', '=', "{$request->get('question_set')}");
                            }
                            if ($request->has('key_stage')) {
                                $query->where('questions.key_stage', '=', "{$request->get('key_stage')}");
                            }
                            if ($request->has('year_group')) {
                                $query->where('questions.year_group', '=', "{$request->get('year_group')}");
                            }
                            if ($request->has('subject')) {
                                $query->where('questions.subject', '=', "{$request->get('subject')}");
                            }
                            if ($request->has('set_group')) {
                                $query->where('questions.set_group', '=', "{$request->get('set_group')}");
                            }
                            if ($request->has('paper')) {
                                $query->where('questions.paper_id', '=', "{$request->get('paper')}");
                            }
                            if ($request->has('strands_id')) {
                                $query->where('strands_id', '=', "{$request->get('strands_id')}");
                            }
                            if ($request->has('substrands_id')) {
                                $query->where('substrands_id', '=', "{$request->get('substrands_id')}");
                            }
                            if ($request->has('status')) {
                                $query->where('questions.status', '=', "{$request->get('status')}");
                            }
                            if ($request->has('difficulty')) {
                                $query->where('questions.difficulty', '=', "{$request->get('difficulty')}");
                            }
                            if ($request->has('question_id')) {
                                $query->where('questions.question_id', 'like', "%{$request->get('question_id')}%");
                            }

                            if ($request->has('validater1')) {
                                $query->where('questions.validator_user1', '=', "{$request->get('validater1')}");
                            }
                            if ($request->has('validater2')) {
                                $query->where('questions.validator_user2', '=', "{$request->get('validater2')}");
                            }
                            if ($request->has('validate_stage')) {
                                $query->where('questions.validate_stage', '=', "{$request->get('validate_stage')}");
                            }
                            if ($request->has('validateReason1')) {
                                //$query->where('questions.validate_reason1', '=', "{$request->get('validateReason1')}");
                                $query->whereRaw(DB::raw("FIND_IN_SET(" . $request->get('validateReason1') . ",questions.validate_reason1)"));
                            }
                            if ($request->has('validateReason2') && session()->get('user')['user_type'] != QUESTIONVALIDATOR) {
                                //$query->where('questions.validate_reason2', '=', "{$request->get('validateReason2')}");
                                $query->whereRaw(DB::raw("FIND_IN_SET(" . $request->get('validateReason2') . ",questions.validate_reason2)"));
                            } else {
                                $query->where(function ($query) use ($request) {
                                    if ($request->has('validateReason1')) {
                                        $query->whereRaw(DB::raw("FIND_IN_SET(" . $request->get('validateReason1') . ",questions.validate_reason1)"))
                                        ->orwhere(DB::raw("FIND_IN_SET(" . $request->get('validateReason1') . ",questions.validate_reason2)"));
                                    }
                                });

                                //$query->whereRaw(DB::raw("FIND_IN_SET(" . $request->get('validateReason2') . ",questions.validate_reason1)"));
                            }
                        })
                        ->addColumn('action', function ($question) {
                            $action = '';
                            if (session()->get('user')['user_type'] != QUESTIONVALIDATOR) {
                                $action = $action . '&nbsp;<a href="' . route('questionbuilder.edit', encryptParam($question->id)) . '?qAct=clone"  alt="' . trans("admin/questionbuilder.copy_questionbuilder") . '" title="' . trans("admin/questionbuilder.copy_questionbuilder") . '" ><i class="glyphicon glyphicon-copyright-mark"></i></a>';
                            }
                            if (session()->get('user')['user_type'] == QUESTIONADMIN) {
                                if ($question->status == REJECT) {
                                    $statusAction = 'glyphicon glyphicon-open-file';
                                    $statusMsg = trans("admin/questionbuilder.inreview_question");
                                }
                                if ($question->status == INREVIEW) {
                                    $statusAction = 'glyphicon glyphicon-open-file';
                                    $statusMsg = trans("admin/questionbuilder.inreview_question");
                                }
                                if ($question->status == REJECT || $question->status == INREVIEW) {
                                    if ($question->created_by == session()->get('user')['id']) {
                                        $action = $action . '&nbsp;<a href="' . route('questionbuilder.edit', encryptParam($question->id)) . '"  alt="' . trans("admin/questionbuilder.edit_questionbuilder") . '" title="' . trans("admin/questionbuilder.edit_questionbuilder") . '" ><i class="glyphicon glyphicon-edit"></i></a>';
                                        $action = $action . '&nbsp;<a href="javascript:void(0);" data-id="' . encryptParam($question->id) . '" alt="' . trans("admin/questionbuilder.delete_questionbuilder") . '" title="' . trans("admin/questionbuilder.delete_questionbuilder") . '"  class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                                    }
                                }
                                if ($question->status == REJECT) {
                                    $action = $action . '&nbsp;<a href="javascript:void(0);" alt="' . $statusMsg . '" title="' . $statusMsg . '" data-id="' . encryptParam($question->id) . '" class="publish_row" data-status="' . INREVIEW . '" data-usertype="' . session()->get('user')['user_type'] . '"><i class="' . $statusAction . '"></i></a>';
                                }
                            } else if (session()->get('user')['user_type'] == ADMIN) {
                                $action = $action . '&nbsp;<a href="' . route('questionbuilder.edit', encryptParam($question->id)) . '"  alt="' . trans("admin/questionbuilder.edit_questionbuilder") . '" title="' . trans("admin/questionbuilder.edit_questionbuilder") . '" ><i class="glyphicon glyphicon-edit"></i></a>';
                                if ($question->status == INREVIEW) {
                                    $statusAction = 'glyphicon glyphicon-open-file';
                                    $statusMsg = trans("admin/questionbuilder.publish_question");
                                    $action = $action . '&nbsp;<a href="javascript:void(0);" alt="' . $statusMsg . '" title="' . $statusMsg . '" data-id="' . encryptParam($question->id) . '" class="publish_row" data-status="' . PUBLISH . '" data-usertype="' . session()->get('user')['user_type'] . '"><i class="' . $statusAction . '"></i></a>';
                                    if ($question->created_by != session()->get('user')['id']) {
                                        $statusAction2 = 'glyphicon glyphicon-exclamation-sign';
                                        $statusMsg2 = trans("admin/questionbuilder.reject_question");
                                        $action = $action . '&nbsp;<a href="javascript:void(0);" alt="' . $statusMsg2 . '" title="' . $statusMsg2 . '" data-id="' . encryptParam($question->id) . '" class="publish_row" data-status="' . REJECT . '" data-usertype="' . session()->get('user')['user_type'] . '"><i class="' . $statusAction2 . '"></i></a>';
                                    }
                                } else if ($question->status == PUBLISH) {
                                    $statusAction = 'glyphicon glyphicon-save-file';
                                    $statusMsg = trans("admin/questionbuilder.unpublish_question");
                                    $action = $action . '&nbsp;<a href="javascript:void(0);" alt="' . $statusMsg . '" title="' . $statusMsg . '" data-id="' . encryptParam($question->id) . '" class="publish_row" data-status="' . UNPUBLISH . '" data-usertype="' . session()->get('user')['user_type'] . '"><i class="' . $statusAction . '"></i></a>';
                                } else if ($question->status == UNPUBLISH) {
                                    $statusAction = 'glyphicon glyphicon-open-file';
                                    $statusMsg = trans("admin/questionbuilder.publish_question");
                                    $action = $action . '&nbsp;<a href="javascript:void(0);" alt="' . $statusMsg . '" title="' . $statusMsg . '" data-id="' . encryptParam($question->id) . '" class="publish_row" data-status="' . PUBLISH . '" data-usertype="' . session()->get('user')['user_type'] . '"><i class="' . $statusAction . '"></i></a>';
                                }
                                $action = $action . '&nbsp;<a href="javascript:void(0);" data-id="' . encryptParam($question->id) . '" alt="' . trans("admin/questionbuilder.delete_questionbuilder") . '" title="' . trans("admin/questionbuilder.delete_questionbuilder") . '"  class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                            }
                            if (session()->get('user')['user_type'] == QUESTIONVALIDATOR) {
                                return $action = $action . '&nbsp;<a href="' . route('questionbuilder.edit', encryptParam($question->id)) . '"  alt="' . trans("admin/questionbuilder.edit_questionbuilder") . '" title="' . trans("admin/questionbuilder.edit_questionbuilder") . '" ><i class="glyphicon glyphicon-edit"></i></a>';
                            }
                            return $action;
                        })
                        ->editColumn('key_stage', function ($question) use ($keyStage) {
                            return isset($keyStage[$question->key_stage]) ? $keyStage[$question->key_stage] : '';
                        })
                        ->editColumn('difficulty', function ($question) use ($questionDifficulty) {
                            if($question->difficulty == 7)
                                return '6+';
                            else
                                return isset($questionDifficulty[$question->difficulty]) ? $questionDifficulty[$question->difficulty] : '';
                        })
                        ->editColumn('year_group', function ($question) use ($yearGroup) {
                            return isset($yearGroup[$question->key_stage][$question->year_group]) ? $yearGroup[$question->key_stage][$question->year_group] : '';
                        })
                        ->editColumn('paper_id', function ($question) use ($paper) {
                            return isset($paper[$question->subject][$question->paper_id]['name']) ? $paper[$question->subject][$question->paper_id]['name'] : '';
                        })
                         ->editColumn('status', function ($question) use ($paper, $request) {
                            if ($question->status == REJECT)
                                return '<span class="glyphicon glyphicon-ban-circle" style="color:red;"></span><br>'.$question->status;

                            if($question->validate_stage == 0){
                                return $question->status;
                            }
                            else {
                                    $validate_reason_array1 = explode(',',$question->validate_reason1);
                                    $validate_reason_array2 = explode(',',$question->validate_reason2);
                                    
                                    if(in_array( '6',$validate_reason_array1) && in_array( '6',$validate_reason_array2)) {
                                        return '<span class="glyphicon glyphicon-ok " style="color:green;"></span><span class="glyphicon glyphicon-ok " style="color:green;"></span><br>'.$question->status;
                                    }
                                    else if(in_array( '6',$validate_reason_array1) || in_array( '6',$validate_reason_array2)) {
                                        return '<span class="glyphicon glyphicon-ok" style="color:green;"></span><br>'.$question->status;
                                    }
                                    else {
                                        return '<span class="glyphicon glyphicon-ban-circle" style="color:red;"></span><br>'.$question->status;
                                    }
                            }
                            
                            /*if ($question->validate_stage == '1')
                                return '<span class="glyphicon glyphicon-ok" style="color:green;"></span><br>'.$question->status;
                            if ($question->validate_stage == '2')
                                return '<span class="glyphicon glyphicon-ok " style="color:green;"></span><span class="glyphicon glyphicon-ok " style="color:green;"></span><br>'.$question->status;
                            else 
                                return $question->status;
                             */
                        
                        })
                        ->editColumn('updated_at', function ($question) { 
                            if($question->validate_stage!=0) {
                                if($question->validate_stage == 1)
                                    return outputDateFormat($question->validate_date1);
                                else if($question->validate_stage == 2)
                                        return outputDateFormat($question->validate_date2);
                            }
                            else 
                                return '';
                            // if (session()->get('user')['id'] == $question->validator_user1) {
                            //        return $question->validate_date1 ? outputDateFormat($question->validate_date1) : '';
                             //}
                            // else if (session()->get('user')['id'] == $question->validator_user2) {
                              //      return $question->validate_date2 ? outputDateFormat($question->validate_date2) : '';
                            // }
                            
                        })
                        ->editColumn('created_at', function ($question) {
                            return $question->created_at ? date("d/m/Y H:i:s",strtotime($question->created_at)) : '';
                        })
                        ->editColumn('published_date', function ($question) {
                            return $question->published_date ? outputDateFormat($question->published_date) : '';
                        })
                        ->editColumn('created_by', function ($question) {
                            return trim($question->first_name . ' ' . $question->last_name);
                        })
                        ->editColumn('strands', function ($question) {
                            return $question->strands . ' (' . $question->strand_reference_code . ')';
                        })
                        ->editColumn('substrands', function ($question) {
                            return $question->substrands . ' (' . $question->sub_strand_reference_code . ')';
                        })
                        ->editColumn('question_type', function ($question) use ($questionType) {
                            return isset($questionType[''][$question->question_type]) ? $questionType[''][$question->question_type] : '';
                        })
                        ->editColumn('created_by', function ($question) use ($questionType) {
                            if(session()->get('user')['user_type'] == ADMIN) {
                                return trim($question->first_name.' '.$question->last_name);
                            }
                            else {
                                return trim($question->validator_first_name.' '.$question->validator_last_name);
                            }
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new question.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function create(Request $request) {
        $setid = $request->get('setid');
        if (!empty($setid)) {
            $setid = decryptParam($setid);
            $question = json_encode($this->questionBuilderRepo->getQuestionFromQuestionSet($setid));
        } else {
            $question = "{}";
        }
        $page_title = trans('admin/questionbuilder.add_questionbuilder');
        $trait = array('trait_1' => trans('admin/questionbuilder.question_builder'), 'trait_1_link' => route('questionbuilder.index'), 'trait_2' => trans('admin/questionbuilder.add_questionbuilder'));
        $questionBuilderData = $this->questionBuilderRepo->getQuestionBuilderData();
        $questionBuilderData['isvalidatevisisble'] = FALSE;
        $questionBuilderData = json_encode($questionBuilderData);
        return view('admin.questionbuilder.create', compact('question', 'questionBuilderData', 'trait', 'page_title'));
    }

    /**
     * Insert a new question
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Controllers\Request $request
     * @return Response
     */
    public function store(Request $request) {
        $inputs = $request->all();

        if (isset($inputs['data']['id'])) {
            $questionId = $inputs['data']['id'];
            $inputs['data']['updated_by'] = session()->get('user')['id'];
            $inputs['data']['id'] = $questionId;
            $this->questionBuilderRepo->update($inputs['data'], $questionId);
            $returnDynamicId1 = $inputs['data']['questionparam']['dynamicId1'];
        } else {
            $inputs['data']['created_by'] = session()->get('user')['id'];
            // asd($inputs['data']['questionparam']);
            $params['ks_id'] = $inputs['data']['questionparam']['keystage'];
            $params['yg_id'] = $inputs['data']['questionparam']['yeargroup'];
            $params['subject'] = $inputs['data']['questionparam']['subject'];
            $params['difficulty_id'] = $inputs['data']['questionparam']['difficulty'];
            $params['strands_id'] = $inputs['data']['questionparam']['strand'];
            $params['substrands_id'] = $inputs['data']['questionparam']['substrand'];
            $params['question_type_id'] = $inputs['data']['questionparam']['questiontype'];
            $params['group'] = $inputs['data']['questionparam']['setgroup'];
            $params['quesnote'] = $inputs['data']['questionparam']['quesnote'];

            $resultarray = $this->questionBuilderRepo->getQuestionCount($params);
            $inputs['data']['questionparam']['dynamicId1'] = $resultarray[0]['cnt'] + 1;
            $questionId = $this->questionBuilderRepo->store($inputs['data']);

            $returnDynamicId1 = $inputs['data']['questionparam']['dynamicId1'] + 1;
        }
        return response()->json(array('questionReference' => $questionId, 'dynamicId1' => $returnDynamicId1));
    }

    /**
     * Show the form for edit a new question.
     * @author     Icreon Tech - dev2.
     * @param type String $id
     * @return Response
     */
    public function edit($id, Request $request) {
        $id = decryptParam($id);
        $page_title = trans('admin/questionbuilder.edit_questionbuilder');
        $trait = array('trait_1' => trans('admin/questionbuilder.question_builder'), 'trait_1_link' => route('questionbuilder.index'), 'trait_2' => trans('admin/questionbuilder.edit_questionbuilder'));
        $question = $this->questionBuilderRepo->getQuestion($id);
        $questionArray = $question;

        $qAct = '';
        if (!empty($request->get('qAct'))) {
            unset($question['id']);
            $qAct = $request->get('qAct');
        }
        $question = htmlentities(json_encode($question));
        $questionBuilderData = $this->questionBuilderRepo->getQuestionBuilderData();

        // $questionBuilderData['isvalidatevisisble'] = session()->get('user')['user_type'] == QUESTIONVALIDATOR ? TRUE : FALSE;
        if (isset($questionArray['questionValidate']['validate_user_id'])) {
            if (in_array(session()->get('user')['id'], $questionArray['questionValidate']['validate_user_id']) || session()->get('user')['user_type'] != QUESTIONVALIDATOR) {
                $questionBuilderData['isvalidatevisisble'] = false;
            } else {
                $questionBuilderData['isvalidatevisisble'] = true;
            }
        } else {
            $questionBuilderData['isvalidatevisisble'] = session()->get('user')['user_type'] == QUESTIONVALIDATOR ? TRUE : FALSE;
        }

        $questionBuilderData = json_encode($questionBuilderData);
       // asd($questionBuilderData)
        
        return view('admin.questionbuilder.create', compact('question', 'questionBuilderData', 'trait', 'page_title', 'qAct'));
    }

    /**
     * Update the question.
     * @author     Icreon Tech - dev2.
     * @param \Illuminate\Http\Request $request
     * @param type $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();

        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->questionBuilderRepo->update($inputs['data'], $id);
        return response()->json(array('questionReference' => $id));
    }

    /**
     * upload image
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Controllers\Request $request
     * @return Response
     */
    public function uploadImage(Request $request) {
        $inputs = $request->all();
        if (isset($inputs['selectedImg']) && !empty($inputs['selectedImg'])) {
            $this->questionBuilderRepo->questionDeleteFile($inputs['selectedImg']);
        }
        if (isset($inputs['file']) && !empty($inputs['file'])) {
            $dimension = isset($inputs['dimension']) ? $inputs['dimension']: '';
            $filename = $this->questionBuilderRepo->questionImageUpload($inputs['file'], $dimension);
            return response()->json(array('filename' => $filename));
        }
    }

    /**
     * upload image
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Controllers\Request $request
     * @return Response
     */
    public function uploadEditorFile(Request $request) {
        $inputs = $request->all();
        $msg = '';
        $image_url = '';
        $callback = ($inputs['CKEditorFuncNum']);
        if (isset($inputs['upload']) && !empty($inputs['upload'])) {
            $filename = $this->questionBuilderRepo->questionEditorFileUpload($inputs['upload']);
            $image_url = route('questionimg', $filename);
        } else {
            $image_url = '';
            $msg = 'error : unable to upload file';
        }
        echo $output = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $callback . ', "' . $image_url . '","' . $msg . '");</script>';
    }

    /**
     * upload audio
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Controllers\Request $request
     * @return Response
     */
    public function uploadAudio(Request $request) {
        $inputs = $request->all();
        if (isset($inputs['selectedAudio']) && !empty($inputs['selectedAudio'])) {
            $this->questionBuilderRepo->questionDeleteFile($inputs['selectedAudio']);
        }

        if (isset($inputs['file']) && !empty($inputs['file'])) {
            $filename = $this->questionBuilderRepo->questionAudioUpload($inputs['file']);
            return response()->json(array('filename' => $filename));
        }
    }

    /**
     * publish/unpublish the question
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function updateStatus(Request $request) {

        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->questionBuilderRepo->updateQuestionStatus($inputs, $id);
        return response()->json();
    }

    public function validateQuestion(Request $request) {
        $inputs = $request->all();
        $inputs['data']['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $inputs['data']['id'];
        $this->questionBuilderRepo->validateQuestion($inputs['data'], $inputs['data']['id']);
        return response()->json();
        /* $id = decryptParam($id);
          $inputs = $request->all();
          $inputs['updated_by'] = session()->get('user')['id'];
          $inputs['id'] = $id;
          $this->questionBuilderRepo->validateQuestion($inputs['data'], $id);
         * */
    }

    /**
     * Delete a question
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
        $this->questionBuilderRepo->destroyQuestion($inputs, $id);
        return response()->json();
    }

    /**
     * this is used to get the auto number for next question
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function questionAutoNumberumber(Request $request) {
        $inputs = $request->all();
        //$params['ks_id'] = str_replace('string:', '', $inputs['ks_id']);
        //$params['yg_id'] = str_replace('string:', '', $inputs['yg_id']);
        //$params['subject'] = str_replace('string:', '', $inputs['subject']);
        $params['difficulty_id'] = str_replace('string:', '', $inputs['difficulty_id']);
        $params['strands_id'] = str_replace('number:', '', $inputs['strands_id']);
        $params['substrands_id'] = str_replace('number:', '', $inputs['substrands_id']);
        //$params['question_type_id'] = str_replace('number:', '', $inputs['question_type_id']);
        //$params['group'] = str_replace('string:', '', $inputs['group']);
        $resultarray = $this->questionBuilderRepo->getQuestionCount($params);
        echo $resultarray[0]['cnt'];
    }
    
    public function questionStrandsForCreateRevision(Request $request){
        $inputs = $request->all();
        $inputs['revisionMinQusLimit'] = REVISION_MIN_QUS_LIMIT;
        $validStrands = $this->questionBuilderRepo->getQuestionStrandsForCreateRevision($inputs);
        $jsonReturn = array();
        if(count($validStrands)){
            foreach($validStrands as $validStrand){
                $strand[$validStrand['strands_id']] = $validStrand['strand'];
                $substrand[$validStrand['strands_id']][$validStrand['substrands_id']] = $validStrand['substrand'];
            }
            $jsonReturn = array('strand' => $strand, 'substrand' => $substrand);
        }
        return response()->json($jsonReturn);
    }
    
    public function update12(){ die;
        //$ids = '1024,1025,1026,16898,1027,1028,1029,1030,16909,17941,17194,19499,19500,19501,19503,19506,19507,19508,19509,583,585,19532,19535,19539,19543,19544,19545,3434,3435,3438,3439,3442,3443,3445,3449,3453,3454,3457,3458,4739,3460,4740,3461,4229,4741,3462,4230,4742,3463,4231,4743,4232,4744,3465,4233,4745,4746,4747,3468,4237,3470,4238,4239,4240,4241,4244,4756,4245,4757,4246,4758,4247,4759,4248,4760,4249,4761,4250,4251,4252,4253,4254,8606,4255,8607,4256,8608,4257,8609,4258,8610,4259,8611,4260,8612,4261,8613,4262,8614,4263,8615,4264,4265,16302,10936,10937,10938,10939,10940,10942,447,10943,10944,449,450,451,17091,452,454,455,16583,715,1486,729,19420,19421,19422,19423,19424,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1015,1016,1018,1019,1020,1021,1022,1023';
        $ids = '140,142,143,144,145,146,147,148,149,150,151,152,154,155,465,467,471,896,898,899,901,904,906,907,908,909,5626,12466,14544,14547,15897';
        $ids = explode(",",$ids);
        $this->questionBuilderRepo->getQuestiontList12(array('ids' => $ids));
    }

}
