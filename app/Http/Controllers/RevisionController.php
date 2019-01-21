<?php

/**
 * This controller is used for Task.
 * @package    task
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Repositories\GroupClassRepository;
use App\Repositories\StrandRepository;
use App\Http\Requests\Revision\RevisionRequest;
use Illuminate\Http\Request;
use Datatables;

/**
 * This controller is used for task centre.
 * @author     Icreon Tech - dev1.
 */
class RevisionController extends Controller {

    /**
     * The TaskRepository instance.
     * @var App\Repositories\TaskRepository
     */
    protected $taskRepo;

    /**
     * The StrandRepository instance.
     *
     * @var App\Repositories\StrandRepository
     */
    protected $strandRepo;

    /**
     * Create a new RevisionController instance.
     * @param  App\Repositories\TaskRepository $taskRepo
     * @param  App\Repositories\StrandRepository $strandRepo
     * @return void
     */
    public function __construct(TaskRepository $taskRepo, StrandRepository $strandRepo) {
        $this->taskRepo = $taskRepo;
        $this->strandRepo = $strandRepo;
    }

    /**
     * Display revision listing page.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function index() {
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + questionKeyStage();
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        $data['strands'] = json_encode($arrStrands['strands']);
        $data['substrands'] = json_encode($arrStrands['substrands']);
        $data['status'] = array('' => trans('admin/admin.select_option')) + statusArray();
        return view('admin.revision.revisionlist', $data);
    }

    

    public function listRecord(Request $request, GroupClassRepository $groupClassRepo) {
        $params['task_type'] = REVISION;
        $params['created_by'] = session()->get('user')['id'];
        //get school's class and group
        $schoolClass = $groupClassRepo->getClasses(array('created_by' => session()->get('user')['school_id']));
        $schoolGroup = $groupClassRepo->getGroups(array('created_by' => session()->get('user')['school_id']));
        $revision = $this->taskRepo->getRevisionList($params);
        $keyStage = questionKeyStage();
        $yearGroup = questionYearGroup();
        return Datatables::of($revision)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('key_stage')) {
                                $query->where('task.key_stage', '=', "{$request->get('key_stage')}");
                            }
                            if ($request->has('year_group')) {
                                $query->where('task.year_group', '=', "{$request->get('year_group')}");
                            }
                            if ($request->has('subject')) {
                                $query->where('task.subject', '=', "{$request->get('subject')}");
                            }
                            if ($request->has('strand')) {
                                $query->where('task.strand', '=', "{$request->get('strand')}");
                            }
                            if ($request->has('substrand')) {
                                $query->where('task.substrand', '=', "{$request->get('substrand')}");
                            }
                            if ($request->has('status')) {
                                $query->where('task.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($test) { 
                            $action = '<div class="btn-group">
                                    <a alt="' . trans("admin/admin.edit") . '" title="' . trans("admin/admin.edit") . '" href="' . route('managerevision.edit', encryptParam($test->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a alt="' . trans("admin/admin.delete") . '" title="' . trans("admin/admin.delete") . '" href="javascript:void(0);" data-id="' . encryptParam($test->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i> </a>
                                    ';
                            return $action ='<a href="javascript:void(0);" data-remote="' . route('managerevision.print', [encryptParam($test->id),encryptParam('revision_question')]) . '"  class="view_row" title="Print Revision"><i class="glyphicon glyphicon-question-sign"></i></a></div>';
                            

                        })
                        ->editColumn('key_stage', function ($test) use ($keyStage) {
                            return isset($keyStage[$test->key_stage]) ? $keyStage[$test->key_stage] : '';
                        })
                        ->editColumn('year_group', function ($test) use ($yearGroup) {
                            return isset($yearGroup[$test->key_stage][$test->year_group]) ? $yearGroup[$test->key_stage][$test->year_group] : '';
                        })
                        ->editColumn('class', function ($test) use ($schoolClass) {
                            return $test->student_source == 'Class' ? $this->taskRepo->selectedSelectionList($schoolClass, $test->student_source_ids,$test->assign_id) : '';
                        })
                        ->editColumn('group', function ($test) use ($schoolGroup) {
                            return $test->student_source == 'Group' ? $this->taskRepo->selectedSelectionList($schoolGroup, $test->student_source_ids,$test->assign_id) : '';
                        })
                        ->editColumn('assign_date', function ($test) {
                            return outputDateFormat($test->assign_date);
                        })
                        ->editColumn('completion_date', function ($test) {
                            if($test->complete_remain){
                                return '<span style="color:red">'.outputDateFormat($test->completion_date).'</span>';
                            }else{
                                return outputDateFormat($test->completion_date);
                            }
                        })
                        ->editColumn('completed', function ($test) {
                            //$action ='<a href="javascript:void(0);" data-remote="' . route('managerevision.print', [encryptParam($test->assign_id),encryptParam('revision_question')]) . '"  class="view_row" title="Print Revision"><i class="glyphicon glyphicon-print"></i></a>&nbsp;';
                            $action ='';
                            if(($test->student_num - $test->student_attempt_completed_num)){
                                return $action.='<span class="glyphicon glyphicon-remove" style="color:red;"></span>';
                            }else{
                                return $action.='<span class="glyphicon glyphicon-ok" style="color:green;"></span>';
                            }
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new revision.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function create() {
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $keyStage = questionKeyStage();
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + $keyStage;
        $data['keyStageJson'] = json_encode($keyStage);
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        $data['strands'] = json_encode($arrStrands['strands']);
        $data['substrands'] = json_encode($arrStrands['substrands']);
        $data['status'] = statusArray();
        $diffculty = questionDifficulty(); 
        $data['difficulty'] = $diffculty;
        $data['selectedStudent'] = array();
        $data['page_heading'] = trans('admin/task.manage_revision');
        $data['page_title'] = trans('admin/task.add_revision');
        $data['trait'] = array('trait_1' => trans('admin/task.revision'), 'trait_1_link' => route('managerevision.index'), 'trait_2' => trans('admin/task.add_revision'));
        $data['JsValidator'] = 'App\Http\Requests\Revision\RevisionRequest';
        return view('admin.revision.create', $data);
    }

    /**
     * Insert a new Test
     * @author     Icreon Tech - dev2.
     * @param App\Http\Requests\Task\TasktestRequest $request
     * @return Response
     */
    public function store(RevisionRequest $request) {
        $inputs = $request->all();
        $sessionData = session()->get('user');
        $inputs['created_by'] = $sessionData['id'];
        $inputs['task_type'] = REVISION;
        $inputs['teacher_name'] = $sessionData['first_name'].' '.$sessionData['last_name'];
        if(!empty($inputs['student'])) {
            $this->taskRepo->storeRevision($inputs);
            return redirect(route('managerevision.index'))->with('ok', trans('admin/task.revision_added_successfully'));
        }
        else {
            return redirect(route('managerevision.create'))->with('error', 'Please select atleast one student');
        }
    }

    /**
     * Show the form for edit test.
     * @author     Icreon Tech - dev2.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $task = $this->taskRepo->getById($id)->toArray();
        $task['assign_date'] = outputDateFormat($task['assign_date']);
        $task['completion_date'] = outputDateFormat($task['completion_date']);
        $data['task'] = $task;
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + questionKeyStage();
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        $data['questionSets'] = json_encode($questionSets);
        $data['status'] = statusArray();
        $data['page_heading'] = trans('admin/task.manage_test');
        $data['page_title'] = trans('admin/task.edit_test');
        $data['trait'] = array('trait_1' => trans('admin/task.test'), 'trait_1_link' => route('test.index'), 'trait_2' => trans('admin/task.edit_test'));
        $data['JsValidator'] = 'App\Http\Requests\Test\TestRequest';
        return view('admin.test.edit')->with($data);
    }

    /**
     * Update the test.
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Requests\Task\TasktestRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(TestRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['task_type'] = TEST;
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->taskRepo->update($inputs, $id);
        return redirect(route('test.index'))->with('ok', trans('admin/task.test_updated_successfully'));
    }

    /**
     * Delete a test 
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
        $this->taskRepo->destroyRow($inputs);
        return response()->json();
    }
/**
     * print revison preview
     * @param type $id
     * @param type $mode
     * @return type
     */
    public function printRevision($id, $mode) {
        $id = decryptParam($id);
        $mode = decryptParam($mode);
        
        $task = $this->taskRepo->getTaskAssignmentDetails(array('id'=>$id))->get()->first()->toArray();

        $questionsData = $this->taskRepo->getPrintRevisionQuestions(array(
            'subject' => $task['subject'],
            'key_stage' => $task['key_stage'],
            'year_group' => $task['year_group'],
            'strand' => $task['strand'],
            'substrand' => $task['substrand'],
            'difficulty' => $task['difficulty'],
            'revision' => true,
            
        ));
       // $questionsData = $this->taskRepo->testgetPrintTestQuestions();
       // $questionsData['questions'] = $questionsData['questions'][0];
        if(!empty($questionsData['questions'])) {
            $questionsData['questions'] = $questionsData['questions']['questionsData'];
        }
        $data['questionsData'] = htmlentities(json_encode($questionsData));
        $data['subject'] = $task['subject'];
        $data['key_stage'] = $task['key_stage'];
        $data['year_group'] = $task['year_group'];
        $data['strand_name'] = $task['strand_name'];
        $data['sub_strand_name'] = $task['sub_strand_name'];
        $paperSubject = subjectPapers();
        $data['subjectPaperArray'] = $paperSubject[$task['subject']];

        return view('admin.revision.preview', $data);
    }
    
    public function printRevisionForm(Request $request) {
        $inputs = $request->all();
        $task = $this->taskRepo->getRevisionPrintDetail(array(
            'subject' => $inputs['subject'],
            'key_stage' => $inputs['key_stage'],
            'year_group' => $inputs['year_group'],
            'strand' => $inputs['strand'],
            'substrand' => $inputs['substrand']
        ));
        $questionsData = $this->taskRepo->getPrintRevisionQuestions(array(
            'subject' => $task['subject'],
            'key_stage' => $task['key_stage'],
            'year_group' => $task['year_group'],
            'strand' => $task['strand_id'],
            'substrand' => $task['substrand_id'],
            'difficulty' => $inputs['difficulty'],
            'revision' => true,
            
        ));
        
       // $questionsData = $this->taskRepo->testgetPrintTestQuestions();
       // $questionsData['questions'] = $questionsData['questions'][0];
        if(!empty($questionsData['questions'])) {
            $questionsData['questions'] = $questionsData['questions']['questionsData'];
        }
        $data['questionsData'] = htmlentities(json_encode($questionsData));
        $data['subject'] = $task['subject'];
        $data['key_stage'] = $task['key_stage'];
        $data['year_group'] = $task['year_group'];
        $data['strand_name'] = $task['strand'];
        $data['sub_strand_name'] = $task['substrand'];
        $paperSubject = subjectPapers();
        $data['subjectPaperArray'] = $paperSubject[$task['subject']];

        return view('admin.revision.preview', $data);
    }
}
