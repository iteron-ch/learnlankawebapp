<?php

/**
 * This controller is used for Task.
 * @package    task
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use App\Repositories\GroupClassRepository;
use App\Http\Requests\Test\TestRequest;
use App\Models\Questionset;
use Illuminate\Http\Request;
use Datatables;
use App\Models\User;

/**
 * This controller is used for task centre.
 * @author     Icreon Tech - dev1.
 */
class TestController extends Controller {

    /**
     * The TaskRepository instance.
     * @var App\Repositories\TaskRepository
     */
    protected $taskRepo;

    /**
     * Create a new TaskRepository instance.
     * @param  App\Repositories\TaskRepository $taskRepo
     * @return void
     */
    public function __construct(TaskRepository $taskRepo, GroupClassRepository $groupClassRepo) {
        $this->taskRepo = $taskRepo;
        $this->groupClassRepo = $groupClassRepo;
    }

    /**
     * Display test listing page.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function index() {
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + questionKeyStage();
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        $questionSets = Questionset::getQuestionsetList(TRUE);
        $data['questionSets'] = json_encode($questionSets);
        $data['status'] = array('' => trans('admin/admin.select_option')) + statusArray();
        return view('admin.test.testlist', $data);
    }

    /**
     * Get record for test list
     * @author     Icreon Tech - dev2.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request, GroupClassRepository $groupClassRepo) {
        $params['task_type'] = TEST;
        $params['created_by'] = session()->get('user')['id'];
        //get school's class and group
        $schoolClass = $groupClassRepo->getClasses(array('created_by' => session()->get('user')['school_id']));
        $schoolGroup = $groupClassRepo->getGroups(array('created_by' => session()->get('user')['school_id']));
        $test = $this->taskRepo->getTestList($params);
        $keyStage = questionKeyStage();
        $yearGroup = questionYearGroup();
        return Datatables::of($test)
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
                            if ($request->has('question_set')) {
                                $query->where('task.question_set', '=', "{$request->get('question_set')}");
                            }
                            if ($request->has('status')) {
                                $query->where('task.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->editColumn('key_stage', function ($test) use ($keyStage) {
                            return isset($keyStage[$test->key_stage]) ? $keyStage[$test->key_stage] : '';
                        })
                        ->editColumn('year_group', function ($test) use ($yearGroup) {
                            return isset($yearGroup[$test->key_stage][$test->year_group]) ? $yearGroup[$test->key_stage][$test->year_group] : '';
                        })
                        ->editColumn('question_set', function ($test) {
                            return $test->set_name;
                        })
                        ->editColumn('class', function ($test) use ($schoolClass) {
                            return $test->student_source == 'Class' ? $this->taskRepo->selectedSelectionList($schoolClass, $test->student_source_ids, $test->assign_id) : '';
                        })
                        ->editColumn('group', function ($test) use ($schoolGroup) {
                            return $test->student_source == 'Group' ? $this->taskRepo->selectedSelectionList($schoolGroup, $test->student_source_ids, $test->assign_id) : '';
                        })
                        ->editColumn('assign_date', function ($test) {
                            return !empty($test->assign_id) ? outputDateFormat($test->assign_date) : '';
                        })
                        ->editColumn('completion_date', function ($test) {
                            if (!empty($test->assign_id)) {
                                if ($test->complete_remain) {
                                    return '<span style="color:red">' . outputDateFormat($test->completion_date) . '</span>';
                                } else {
                                    return outputDateFormat($test->completion_date);
                                }
                            }
                        })
                        ->editColumn('completed', function ($test) {
                            if (!empty($test->assign_id)) {
                                if (($test->student_num - $test->student_attempt_completed_num)) {
                                    return '<span class="glyphicon glyphicon-remove" style="color:red;"></span>';
                                } else {
                                    return '<span class="glyphicon glyphicon-ok" style="color:green;"></span>';
                                }
                            }
                        })
                        ->addColumn('action', function ($test) {
                            $action = '<div class="btn-group">';
                            if (empty($test->assign_id) || $test->start_date_remain > 0) {
                                $action .='&nbsp;<a alt="' . trans("admin/taskassignment.assign_students") . '" title="' . trans("admin/taskassignment.assign_students") . '" href="' . route('managetest.edit', encryptParam($test->id)) . '"><i class="glyphicon glyphicon-plus"></i></a>';
                            } else {
                                $action .='&nbsp;<i class="glyphicon glyphicon-plus" style="color:#ccc"></i>';
                            }
                            if ($test->is_print) {

                               // $action .='&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('question')]) . '"  class="view_row" title="Print Question"><i class="glyphicon glyphicon-print"></i></a>';

                                //$action .='&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('answer')]) . '"  class="view_row" title="Print Answer"><i class="glyphicon glyphicon-text-background"></i></a>';

                                if ($test->subject == MATH) {
                                    $action .='<br><a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('question'), '1']) . '"  class="view_row" title="Print Paper 1"><i class="glyphicon glyphicon-print"></i></a>';
                                    $action .='&nbsp;&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('question'), '2']) . '"  class="view_row" title="Print Paper 2"><i class="glyphicon glyphicon-print"></i></a>';
                                    $action .='&nbsp;&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('question'), '3']) . '"  class="view_row" title="Print Paper 3"><i class="glyphicon glyphicon-print"></i></a>';
                                    $action .='<br><a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('answer'), '1']) . '"  class="view_row" title="Print Answer 1"><i class="glyphicon glyphicon-text-background"></i></a>';
                                    $action .='&nbsp;&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('answer'), '2']) . '"  class="view_row" title="Print Answer 2"><i class="glyphicon glyphicon-text-background"></i></a>';
                                    $action .='&nbsp;&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('answer'), '3']) . '"  class="view_row" title="Print Answer 3"><i class="glyphicon glyphicon-text-background"></i></a>';
                                } else if ($test->subject == ENGLISH) {
                                    $action .='<br><a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('question'), '4']) . '"  class="view_row" title="Print Paper 1"><i class="glyphicon glyphicon-print"></i></a>';
                                    $action .='&nbsp;&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('question'), '5']) . '"  class="view_row" title="Print Paper 2"><i class="glyphicon glyphicon-print"></i></a>';
                                    $action .='<br><a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('answer'), '4']) . '"  class="view_row" title="Print Answer 1"><i class="glyphicon glyphicon-text-background"></i></a>';
                                    $action .='&nbsp;&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', [encryptParam($test->id), encryptParam('answer'), '5']) . '"  class="view_row" title="Print Answer 2"><i class="glyphicon glyphicon-text-background"></i></a>';
                                }
                                //$action .='&nbsp;<a href="javascript:void(0);" data-remote="' . route('managetest.print', encryptParam($test->id)) . '"  class="view_row"><i class="fa fa-print"  style="color:blue;"></i>';
                            }
                            $action .='</div>';
                            return $action;
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new test.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function create() {
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $data['keyStage'] = array('' => trans('admin/admin.select_option')) + questionKeyStage();
        $yearKeys = questionYearGroup();
        $data['yearKeysJson'] = json_encode($yearKeys);
        $questionSets = Questionset::getQuestionsetList(TRUE);
        $data['questionSets'] = json_encode($questionSets);
        $data['status'] = statusArray();
        $data['page_heading'] = trans('admin/task.manage_test');
        $data['page_title'] = trans('admin/task.add_test');
        $data['trait'] = array('trait_1' => trans('admin/task.test'), 'trait_1_link' => route('managetest.index'), 'trait_2' => trans('admin/task.add_test'));
        $data['JsValidator'] = 'App\Http\Requests\Test\TestRequest';
        return view('admin.test.create', $data);
    }

    /**
     * Insert a new Test
     * @author     Icreon Tech - dev2.
     * @param App\Http\Requests\Task\TasktestRequest $request
     * @return Response
     */
    public function store(TestRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['task_type'] = TEST;
        $this->taskRepo->storeTest($inputs);
        return redirect(route('managetest.index'))->with('ok', trans('admin/task.test_added_successfully'));
    }

    /**
     * Show the form for edit test.
     * @author     Icreon Tech - dev2.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {

        // get students from the database
        $authUser = session()->get('user');
        $studentArray = array();

        $studentRecord = User::where(['user_type' => STUDENT, 'teacher_id' => $authUser['id']])->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();

        if (isset($studentRecord) && count($studentRecord) > 0) {
            foreach ($studentRecord as $key => $val) {
                $studentArray[$val['id']] = trim($val['first_name'] . ' ' . $val['last_name']);
            }
        }

        $id = decryptParam($id);
        $test = $this->taskRepo->getTestList(array(
                    'task_id' => $id,
                    'task_type' => TEST,
                    'created_by' => session()->get('user')['id']
                ))->get()->first();
        if (!$test) {
            return redirect('error404');
        }
        $selectedStudent = array();
        $test = $test->toArray();
        $keyStage = questionKeyStage();
        $yearGroup = questionYearGroup();
        $test['key_stage_name'] = isset($test['key_stage']) ? $keyStage[$test['key_stage']] : '';
        $test['year_group_name'] = isset($yearGroup[$test['key_stage']][$test['year_group']]) ? $yearGroup[$test['key_stage']][$test['year_group']] : '';
        if (!empty($test['assign_id'])) {

            $test['assign_date'] = outputDateFormat($test['assign_date']);
            $test['completion_date'] = outputDateFormat($test['completion_date']);
            $taskAssignedStudent = $this->taskRepo->getTaskStudents(array(
                'assign_id' => $test['assign_id'],
            ));
            if ($taskAssignedStudent) {
                foreach ($taskAssignedStudent as $row) {
                    $selectedStudent[] = $row['student_id'];
                }
            }
        }
        $data['test'] = $test;
        $data['studentArray'] = $studentArray;
        $data['selectedStudent'] = $selectedStudent;
        $data['page_heading'] = trans('admin/task.manage_test');
        $data['page_title'] = trans('admin/task.assign_students');
        $data['trait'] = array('trait_1' => trans('admin/task.test'), 'trait_1_link' => route('managetest.index'), 'trait_2' => trans('admin/task.assign_students'));
        $data['JsValidator'] = 'App\Http\Requests\Test\TestRequest';
        return view('admin.test.create', $data);
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
        $sessionData = session()->get('user');
        $created_by = $sessionData['id'];
        $inputs['updated_by'] = $inputs['created_by'] = $created_by;
        $inputs['id'] = $id;
        $params = array(
            'task_id' => $id,
            'task_type' => TEST,
            'created_by' => $created_by
        );
        $model = $this->taskRepo->getTestList($params)->get()->first();

        if (!$model) {
            return redirect('error404');
        }

        $students = array();

        if($inputs['selection_type'] == 'Group'){
            foreach($inputs['students'] as $value){
                $studentGroup = $this->groupClassRepo->studentGroupIds($value);
                array_push($students, array_shift($studentGroup) . '-' . $value);
            }
        }

        if($inputs['selection_type'] == 'Class'){
            foreach($inputs['students'] as $value){
                $studentClass = $this->groupClassRepo->classStudentIds($value);
                array_push($students, array_shift($studentClass) . '-' . $value);
            }
        }

        $inputs['students'] = $students;
        
        $inputs['teacher_name'] = $sessionData['first_name'] . ' ' . $sessionData['last_name'];
        $this->taskRepo->assignTest($inputs, $model);
        return redirect(route('managetest.index'))->with('ok', trans('admin/task.assigned_successfully'));
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
     * print test preview
     * @param type $id
     * @param type $mode
     * @return type
     */
    public function printTest($id, $mode, $paper) {
        $id = decryptParam($id);
        $mode = decryptParam($mode);
        $task = $this->taskRepo->getById($id)->toArray();
        
        $questionsData = $this->taskRepo->getPrintTestQuestions(array(
            'subject' => $task['subject'],
            'question_set' => $task['question_set'],
            'key_stage' => $task['key_stage'],
            'year_group' => $task['year_group'],
            'paper_id' => $paper,
        ));
        // asd($questionsData);
        //$questionsData = $this->taskRepo->testgetPrintTestQuestions();
       // $questionsData['questions'] = $questionsData['questions'][0];
        $questionsData['mode'] = $mode;
        if (!empty($questionsData['questions'])) {
            $questionsData['questions'] = $questionsData['questions']['questionsData'];
        }
       //asd($questionsData);
        $data['questionsData'] = htmlentities(json_encode($questionsData));

        $data['subject'] = $task['subject'];
        $data['key_stage'] = $task['key_stage'];
        $data['year_group'] = $task['year_group'];
        $data['paper'] = $paper;
        $paperSubject = subjectPapers();
        $data['subjectPaperArray'] = $paperSubject[$task['subject']];
        if($mode == "question")
            return view('admin.test.preview', $data);
        else
            return view('admin.test.preview_answer', $data);
    }

}
