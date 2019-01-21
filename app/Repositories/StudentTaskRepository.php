<?php

/**
 * This is used for student assigned task 
 * @package    Student task
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;

use App\Models\Taskstudents;
use App\Models\Task;
use App\Models\Studenttestattempt;
use App\Models\Studenttest;
use App\Models\Studentrevision;
use App\Models\Question;
use App\Models\Questionanswer;
use App\Models\Studentstrandmeta;
use App\Models\StudentRevisionResult;
use App\Models\StudentTestResult;
use App\Models\StudentTestStrandResult;
use App\Models\Taskassignment;
use App\Models\Studentrating;
use Carbon\Carbon;
use DB;

/**
 * This is used for student assigned task 
 * @package    Student task
 * @author     Icreon Tech  - dev2.
 */
class StudentTaskRepository extends BaseRepository {

    /**
     * The Task instance.
     *
     * @var App\Models\Task
     */
    protected $task;

    /**
     * The Studenttestattempt instance.
     *
     * @var App\Models\Studenttestattempt
     */
    protected $studenttestattempt;

    /**
     * The Studenttestattempt instance.
     *
     * @var App\Models\Studentrating
     */
    protected $studentrating;

    /**
     * The Studenttest instance.
     *
     * @var App\Models\Studenttest
     */
    protected $studenttest;

    /**
     * The Question instance.
     *
     * @var App\Models\Studentrevision
     */
    protected $studentrevision;

    /**
     * The Questionanswer instance.
     *
     * @var App\Models\Questionanswer
     */
    protected $questionanswer;

    /**
     * The Question instance.
     *
     * @var App\Models\Question
     */
    protected $question;
    public $currentDate;
    public $currentDateTime;
    public $allowedQuestionType;

    /**
     * Create a new StudentTaskRepository instance.
     * @author     Icreon Tech - dev1.
     * @param \App\Models\Task $model
     */
    public function __construct(Taskstudents $model, Task $task, Studenttestattempt $studenttestattempt, Studenttest $studenttest, Studentrevision $studentrevision, Question $question, Questionanswer $questionanswer, Studentrating $studentrating) {
        $this->model = $model;
        $this->task = $task;
        $this->studenttestattempt = $studenttestattempt;
        $this->studenttest = $studenttest;
        $this->studentrevision = $studentrevision;
        $this->question = $question;
        $this->questionanswer = $questionanswer;
        $this->studentrating = $studentrating;
        $this->currentDate = Carbon::now()->toDateString();
        $this->currentDateTime = Carbon::now()->toDateTimeString();
        $this->allowedQuestionType = ['13', '11', '3', '7', '4', '22', '16', '2', '1', '12', '6', '14', '8', '9', '19', '15', '20', '18', '10', '17', '21', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32'];
    }

    /**
     * return student assigned active task
     * @param type $params
     * @return type
     */
    public function getStudentAssignedTask($params) {
        $query = $this->model
                ->select(['task.id', 'task.key_stage',
                    'task.year_group', 'task.subject', 'task.subject AS tasksubject', 'task.task_type',
                    'taskassignments.assign_date', 'taskassignments.completion_date', 'task.substrand AS substrand_id',
                    'questionsets.set_name', 'strands.strand AS strand', '.substrands.strand AS substrand',
                    'student_test_attempt.id AS student_test_attempt_id', 'student_test_attempt.attempt AS student_test_attempt_attempt',
                    'student_test_attempt.status AS student_test_attempt_status', 'student_test_attempt.last_assessment_date AS student_test_attempt_last_assessment_date',
                    DB::raw('IFNULL(TIMESTAMPDIFF(SECOND, `student_test_attempt`.`attempt_at`,  "' . $this->currentDateTime . '"),"") AS student_test_attempt_attempttime'),
                    'studentrevision.id AS studentrevision_id', 'studentrevision.attempt AS studentrevision_attempt',
                    'studentrevision.status AS studentrevision_status', 'studentrevision.completed_at AS studentrevision_completed_at',
                    DB::raw('IFNULL(TIMESTAMPDIFF(SECOND, `studentrevision`.`attempt_at`,  "' . $this->currentDateTime . '"),"") AS studentrevision_attempttime')
                ])
                ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                ->join('task', 'taskassignments.task_id', '=', 'task.id')
                ->join('strands AS strands', 'task.strand', '=', 'strands.id', 'left')
                ->join('strands AS substrands', 'task.substrand', '=', 'substrands.id', 'left')
                ->leftJoin('questionsets', function($join) {
                    $join->on('task.question_set', '=', 'questionsets.id');
                    $join->on('questionsets.status', '=', DB::raw("'Published'"));
                })
                ->where([
            'task.status' => ACTIVE,
            'task.key_stage' => $params['key_stage'],
            'task.year_group' => $params['year_group'],
            'taskstudents.student_id' => $params['student_id']
        ]);
        if ($params['status'] == 'pending') {
            $query->leftJoin('student_test_attempt', function($join) use ($params) {
                        $join->on('task.id', '=', 'student_test_attempt.task_id');
                        $join->on('student_test_attempt.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                        $join->on('student_test_attempt.attempt_completed', '!=', DB::raw("'3'"));
                    })
                    ->leftJoin('studentrevision', function($join) use ($params) {
                        $join->on('task.id', '=', 'studentrevision.task_id');
                        $join->on('studentrevision.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                        $join->on('studentrevision.status', '!=', DB::raw("'" . COMPLETED . "'"));
                    });
            $query->where('taskassignments.assign_date', '<=', $this->currentDate)
                    ->where('taskstudents.attempt_status', '!=', COMPLETED)
                    ->where('taskassignments.completion_date', '>=', $this->currentDate)
                    //->havingRaw(DB::raw("IF(student_test_attempt_attempttime != '', student_test_attempt_status = '" . PENDING . "',1)"))
                    ->havingRaw(DB::raw("IF(studentrevision_attempttime != '', studentrevision_status = '" . PENDING . "',1)"));
            $query->groupBy('task.id');
        }
        if ($params['status'] == 'overdue') {
            $query->leftJoin('student_test_attempt', function($join) use ($params) {
                        $join->on('task.id', '=', 'student_test_attempt.task_id');
                        $join->on('student_test_attempt.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                    })
                    ->leftJoin('studentrevision', function($join) use ($params) {
                        $join->on('task.id', '=', 'studentrevision.task_id');
                        $join->on('studentrevision.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                    });
            $query->where('taskassignments.completion_date', '<', $this->currentDate)
                    ->havingRaw(DB::raw("IF(student_test_attempt_attempttime != '', (student_test_attempt_status = '" . PENDING . "'),1)"))
                    ->havingRaw(DB::raw("IF(studentrevision_attempttime != '', (studentrevision_status = '" . PENDING . "'),1)"));
            $query->groupBy('task.id');
        }
        if ($params['status'] == 'completed') {
            $query->leftJoin('student_test_attempt', function($join) use ($params) {
                        $join->on('task.id', '=', 'student_test_attempt.task_id');
                        $join->on('student_test_attempt.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                    })
                    ->leftJoin('studentrevision', function($join) use ($params) {
                        $join->on('task.id', '=', 'studentrevision.task_id');
                        $join->on('studentrevision.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                    });
            //$query->whereRaw(DB::raw("taskstudents.attempt_status = '" . COMPLETED . "' OR taskstudents.attempt_status = '" . INPROGRESS . "'"));
            //$query->where('taskstudents.attempt_status', '=', COMPLETED)
                    //$query->havingRaw(DB::raw("studentrevision_status = '" . COMPLETED . "'"));
            $query->where('taskstudents.attempt_status', '!=', PENDING);
            $query->groupBy(['id', 'student_test_attempt_id', 'studentrevision_id']);
        }
        return $query->get()->toArray();
    }

    public function studentNoTaskMsg($status) {
        $noRecordMsg = '';
        switch ($status):
            case 'pending':
                $noRecordMsg = trans('front/front.no_pending_task');
                break;
            case 'overdue':
                $noRecordMsg = trans('front/front.no_overdue_task');
                break;
            case 'completed':
                $noRecordMsg = trans('front/front.no_completed_task');
                break;
        endswitch;
        return $noRecordMsg;
    }

    /**
     * return student assigned active tests
     * @param type $params
     * @return type
     */
    public function getStudentAssignedActiveTest($params) {
        if ($params['tutor_id']) {
            return $this->getTutorStudentAssignedActiveTest($params);
        } else {
            unset($params['tutor_id']);
            return $this->getSchoolStudentAssignedActiveTest($params);
        }
    }

    /**
     * this is used to get the assigned topic for student for current date
     * @param type $topicSearchParam
     * @return type
     */
    public function getSchoolStudentAssignedActiveTest($params) {
        return $this->model
                        ->select(['task.id', 'task.key_stage',
                            'task.year_group', 'task.subject',
                            'taskassignments.assign_date', 'taskassignments.completion_date',
                            'questionsets.set_name', 'student_test_attempt.id AS student_test_attempt_id'
                        ])
                        ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                        ->join('task', 'taskassignments.task_id', '=', 'task.id')
                        ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                        ->leftJoin('student_test_attempt', function($join) use($params) {
                            $join->on('task.id', '=', 'student_test_attempt.task_id');
                            $join->on('student_test_attempt.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                            $join->on('student_test_attempt.attempt_completed', '!=', DB::raw("'3'"));
                        })
                        ->where([
                            'task.status' => ACTIVE,
                            'task.subject' => $params['task_subject'],
                            'task.task_type' => TEST,
                            'taskstudents.student_id' => $params['student_id'],
                            'questionsets.status' => 'Published',
                            'task.key_stage' => $params['key_stage'],
                            'task.year_group' => $params['year_group'],
                        ])
                        ->where('taskstudents.attempt_status', '!=', COMPLETED)
                        ->where('taskassignments.assign_date', '<=', $this->currentDate)
                        //->where('taskassignments.completion_date', '>=', $this->currentDate)
                        ->groupBy('task.id')
                        ->get()->toArray();
    }

    /**
     * this is used to get the assigned topic for student for current date
     * @param type $topicSearchParam
     * @return type
     */
    public function getTutorStudentAssignedActiveTest($params) {
        return $this->task
                        ->select(['task.id', 'task.key_stage',
                            'task.year_group', 'task.subject',
                            'questionsets.set_name', 'student_test_attempt.id AS student_test_attempt_id'
                        ])
                        ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                        ->leftJoin('student_test_attempt', function($join) use($params) {
                            $join->on('task.id', '=', 'student_test_attempt.task_id');
                            $join->on('student_test_attempt.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                            //$join->on('student_test_attempt.attempt_completed', '!=', DB::raw("'3'"));
                        })
                        //->whereRaw('task.id NOT IN (SELECT task_id FROM student_test_attempt WHERE student_id = '.$params['student_id'].' AND attempt_completed = "3" )')
                        ->where([
                            'task.status' => ACTIVE,
                            'task.subject' => $params['task_subject'],
                            'task.task_type' => TEST,
                            'questionsets.status' => 'Published',
                            'task.key_stage' => $params['key_stage'],
                            'task.year_group' => $params['year_group'],
                        ])
                        ->get()->toArray();
    }

    public function addStudentTestAttempt($params) {
        $studentTest = $this->getStudentTest(array(
            'student_id' => $params['student_id'],
            'task_id' => $params['task_id']
        ));
        // check wether this test is already attempt, 
        // if no attempt made, make a new entry for this test
        // if attempt but not COMPLETED, allow continue for this attemped test (no new entry for this test)
        // if attempt and COMPTETED, make a entry for same test with +1 increased attempt.  
        $isStudentStoredTest = FALSE;
        $attempt = 1;
        if (count($studentTest)) {
            if ($studentTest['status'] == COMPLETED) {
                $attempt = $studentTest['attempt'] + 1;
            } else {
                $isStudentStoredTest = TRUE;
            }
        }

        if (!$isStudentStoredTest) {
            //insert student attempt test paper wise
            $studentTestPaper = $this->saveStudentTest(array(
                'student_id' => $params['student_id'],
                'task_id' => $params['task_id'],
                'assign_id' => $params['assign_id'],
                'attempt' => $attempt,
                'paperAttempts' => paperAttempts(),
                'subjectPapers' => subjectPapers()[$params['subject']]
            ));
        } else {
            $paperIds = array_keys(subjectPapers()[$params['subject']]);
            $studentTestPaper = $this->getStudentStoredTestPaper(array(
                'test_attempt_id' => $studentTest['id'],
                'paper_id' => $paperIds[0],
                'attempt' => 1
            ));
        }
        return $studentTestPaper['id'];
    }

    /**
     * return assigned test detail for a student
     * @param type $params
     * @return type array of strands assignment details
     */
    public function getStudentTestDetail($params) {
        if ($params['tutor_id']) {
            $testAssigned = $this->getTutorStudentTestDetail($params);
        } else {
            unset($params['tutor_id']);
            $testAssigned = $this->getSchoolStudentTestDetail($params);
        }
        return $testAssigned;
    }

    /**
     * 
     * @param type $params
     */
    public function getTutorStudentTestDetail($params) {
        return $this->task
                        ->select(['task.id', 'task.subject'])
                        ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                        ->leftJoin('student_test_attempt', function($join) use($params) {
                            $join->on('task.id', '=', 'student_test_attempt.task_id');
                            $join->on('student_test_attempt.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                        })
                        ->where([
                            'task.id' => $params['task_id'],
                            'task.status' => ACTIVE
                        ])
                        ->get()->first();
    }

    /**
     * 
     * @param type $params
     */
    public function getSchoolStudentTestDetail($params) {
        return $this->model
                        ->select(['task.id', 'task.subject', 'taskassignments.id AS assign_id',
                            'taskassignments.assign_date', 'taskassignments.completion_date',])
                        ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                        ->join('task', 'taskassignments.task_id', '=', 'task.id')
                        ->leftJoin('student_test_attempt', function($join) use($params) {
                            $join->on('task.id', '=', 'student_test_attempt.task_id');
                            $join->on('student_test_attempt.student_id', '=', DB::raw("'" . $params['student_id'] . "'"));
                        })
                        ->where([
                            'taskstudents.student_id' => $params['student_id'],
                            'taskstudents.attempt_status' => PENDING,
                            'task.id' => $params['task_id'],
                            'task.status' => ACTIVE
                        ])
                         ->where('taskassignments.assign_date', '<=', $this->currentDate)
                          //->where('taskassignments.completion_date', '>=', $this->currentDate)
                        ->orderby('taskassignments.created_at')
                        ->get()->first();
    }

    /**
     * this is used to get the student appempted test
     * @param type $params
     * @return type arry of strands
     */
    public function getStudentTest($params) {
        $studentAttempetedTest = $this->studenttestattempt
                        ->select(['student_test_attempt.id', 'student_test_attempt.attempt', 'student_test_attempt.status'])
                        ->where([
                            'student_test_attempt.task_id' => $params['task_id'],
                            'student_test_attempt.student_id' => $params['student_id']
                        ])
                        ->orderBy('student_test_attempt.attempt', 'DESC')
                        ->get()->first();
        return $studentAttempetedTest ? $studentAttempetedTest->toArray() : array();
    }

    /**
     * this function save student attempt test and its corresponding attempt paper, return first attempt first paper 
     * @param type $params
     */
    public function saveStudentTest($params) {
        $testApptemptId = $this->storeStudentTestMeta(array(
            'student_id' => $params['student_id'],
            'task_id' => $params['task_id'],
            'assign_id' => $params['assign_id'],
            'attempt' => $params['attempt']
        ));
        if ($testApptemptId) {
            //save test attemp paper
            $params['test_attempt_id'] = $testApptemptId;
            $this->saveStudentTestPaper($params);
            $paperIds = array_keys($params['subjectPapers']);
            //get first attemp first paper
            return $this->getStudentStoredTestPaper(array(
                        'test_attempt_id' => $testApptemptId,
                        'paper_id' => $paperIds[0],
                        'attempt' => 1,
            ));
        }
    }

    /**
     * this function save student test attempt meta 
     * @param type $params
     */
    public function saveStudentTestMeta($model, $input) {
        if (isset($input['student_id'])) {
            $model->student_id = $input['student_id'];
        }
        if (isset($input['task_id'])) {
            $model->task_id = $input['task_id'];
        }
        if (isset($input['attempt_at'])) {
            $model->attempt_at = $input['attempt_at'];
        }
        if (isset($input['status'])) {
            $model->status = $input['status'];
        }
        if (isset($input['attempt_completed'])) {
            $model->attempt_completed = $input['attempt_completed'];
        }
        if (isset($input['last_assessment_date'])) {
            $model->last_assessment_date = $input['last_assessment_date'];
        }
        if (isset($input['total_marks_obtain'])) {
            $model->total_marks_obtain = $model->total_marks_obtain + $input['total_marks_obtain'];
        }
        if (isset($input['total_time_spent'])) {
            $model->total_time_spent = $model->total_time_spent + $input['total_time_spent'];
        }
        if (isset($input['completed_at_attempt_1'])) {
            $model->completed_at_attempt_1 = $input['completed_at_attempt_1'];
        }
        if (isset($input['completed_at_attempt_2'])) {
            $model->completed_at_attempt_2 = $input['completed_at_attempt_2'];
        }
        if (isset($input['completed_at_attempt_3'])) {
            $model->completed_at_attempt_3 = $input['completed_at_attempt_3'];
        }
        if (isset($input['aggregate_attempt_1'])) {
            $model->aggregate_attempt_1 = $input['aggregate_attempt_1'];
        }
        if (isset($input['aggregate_attempt_2'])) {
            $model->aggregate_attempt_2 = $input['aggregate_attempt_2'];
        }
        if (isset($input['aggregate_attempt_3'])) {
            $model->aggregate_attempt_3 = $input['aggregate_attempt_3'];
        }
        if (isset($input['total_questions'])) {
            $model->total_questions = $input['total_questions'];
        }
        if (isset($input['total_marks'])) {
            $model->total_marks = $input['total_marks'];
        }
        if (isset($input['total_time'])) {
            $model->total_time = $input['total_time'];
        }
        if (isset($input['assign_id'])) {
            $model->assign_id = $input['assign_id'];
        }
        if (isset($input['attempt'])) {
            $model->attempt = $input['attempt'];
        }
        return $model->save() ? $model->id : FALSE;
    }

    /**
     * this function save student test attempt meta 
     * @param type $params
     */
    public function storeStudentTestMeta($params) {
        $model = new $this->studenttestattempt;
        return $this->saveStudentTestMeta($model, $params);
    }

    /**
     * this function save student test attempt meta 
     * @param type $params
     */
    public function updateStudentTestMeta($params) {
        $model = $this->studenttestattempt->findOrFail($params['id']);
        return $this->saveStudentTestMeta($model, $params);
    }
    /**
     * this function save the value of student baseline
     * @param type $params
     */
    public function updateStudentBaseLine($params) {
        if(!empty($params['student_id'])) {
            if($params['subject'] == MATH)
                    DB::update("update users set ks2_maths_baseline_value='".$params['percentageObtained']."',ks2_maths_baseline='2' where id='" . $params['student_id']."'");
            else if($params['subject'] == ENGLISH)
                DB::update("update users set ks2_english_baseline_value='".$params['percentageObtained']."',ks2_english_baseline='2' where id='" . $params['student_id']."'");
        }
    }
    
    /**
     * this function save student test paper attempt
     * @param type $params
     */
    public function saveStudentTestPaper($params) {
        foreach ($params['paperAttempts'] as $attemptKey => $attemptValue) {
            foreach ($params['subjectPapers'] as $paperKey => $paperValue) {
                $data[] = array(
                    'test_attempt_id' => $params['test_attempt_id'],
                    'paper_id' => $paperKey,
                    'attempt' => $attemptKey
                );
            }
        }
        DB::table('studenttest')->insert($data);
    }

    /**
     * this function save student test attempt meta 
     * @param type $params
     */
    public function updateStudentTestPaper($input) {
        $model = $this->studenttest->findOrFail($input['id']);
        if (isset($input['questionids'])) {
            $model->questionids = $input['questionids'];
        }
        if (isset($input['num_question'])) {
            $model->num_question = $input['num_question'];
        }
        if (isset($input['num_remaining'])) {
            $model->num_remaining = $input['num_remaining'];
        }
        if (isset($input['quesmaxtime'])) {
            $model->quesmaxtime = $input['quesmaxtime'];
        }
        if (isset($input['remainingtime'])) {
            $model->remainingtime = $input['remainingtime'];
        }
        if (isset($input['total_marks'])) {
            $model->total_marks = $input['total_marks'];
        }
        if (isset($input['num_skipped'])) {
            $model->num_skipped = $input['num_skipped'];
        }
        if (isset($input['num_answered'])) {
            $model->num_answered = $input['num_answered'];
        }
        if (isset($input['attempt_at'])) {
            $model->attempt_at = $input['attempt_at'];
        }
        if (isset($input['status'])) {
            $model->status = $input['status'];
        }
        if (isset($input['num_correct'])) {
            $model->num_correct = $input['num_correct'];
        }
        if (isset($input['mark_obtain'])) {
            $model->mark_obtain = $input['mark_obtain'];
        }
        if (isset($input['completed_at'])) {
            $model->completed_at = $input['completed_at'];
        }
        $model->save();
    }

    /**
     * return test appempt paper
     * @param type $params
     * @return type
     */
    public function getStudentAttemptTestPaperStatus($params) {
        return $this->studenttest
                        ->select([
                            'studenttest.id', 'studenttest.paper_id', 'studenttest.attempt', 'studenttest.num_question', 'studenttest.questionids',
                            'studenttest.num_remaining', 'studenttest.num_skipped', 'studenttest.quesmaxtime', 'studenttest.attempt_at', 'studenttest.status',
                            DB::raw('(studenttest.attempt_at + INTERVAL studenttest.quesmaxtime SECOND ) AS complete_time, TIMESTAMPDIFF(SECOND, attempt_at, "' . $this->currentDateTime . '") AS attempt_time'),
                        ])
                        ->where('studenttest.test_attempt_id', '=', $params['test_attempt_id'])
                        ->orderBy('studenttest.attempt', 'ASC')
                        ->orderBy('studenttest.paper_id', 'ASC')
                        ->get()->toArray();
    }

    public function getStudentStoredTestPaper($params) {
        $query = $this->studenttest
                ->select([
                    'studenttest.id', 'studenttest.questionids', 'studenttest.paper_id', 'studenttest.attempt',
                    'studenttest.num_question', 'studenttest.total_marks',
                    'studenttest.num_answered', 'studenttest.num_skipped',
                    'studenttest.num_remaining', 'studenttest.quesmaxtime', 'studenttest.remainingtime',
                    'studenttest.attempt_at', 'studenttest.status', 'studenttest.mark_obtain', 'studenttest.num_correct',
                    DB::raw('(studenttest.attempt_at + INTERVAL studenttest.quesmaxtime SECOND ) AS complete_time, TIMESTAMPDIFF(SECOND, studenttest.attempt_at, "' . $this->currentDateTime . '") AS attempt_time,TIMESTAMPDIFF(SECOND, studenttest.attempt_at, studenttest.completed_at) AS attempt_complete_time'),
                    'student_test_attempt.id AS student_test_attempt_id', 'student_test_attempt.task_id', 'student_test_attempt.assign_id','student_test_attempt.attempt AS assignment_num','student_test_attempt.attempt_at AS test_attempt_at','student_test_attempt.completed_at_attempt_1 AS completed_at_attempt_1'
                ])
                ->join('student_test_attempt', 'studenttest.test_attempt_id', '=', 'student_test_attempt.id');
        if (isset($params['student_id'])) {
            $query->where('student_test_attempt.student_id', '=', $params['student_id']);
        }
        if (isset($params['attempt_paperid'])) {
            $query->where('studenttest.id', '=', $params['attempt_paperid']);
        } if (isset($params['task_id'])) {
            $query->where('studenttest.task_id', '=', $params['task_id']);
        }
        if (isset($params['test_attempt_id'])) {
            $query->where('studenttest.test_attempt_id', '=', $params['test_attempt_id']);
        }
        if (isset($params['paper_id'])) {
            $query->where('studenttest.paper_id', '=', $params['paper_id']);
        }
        if (isset($params['attempt'])) {
            $query->where('studenttest.attempt', '=', $params['attempt']);
        }
        if (isset($params['listall'])) {
            return $query->get()->toArray();
        } else {
            $result = $query->get()->first();
            return $result ? $result->toArray() : array();
        }
    }

    /**
     * This function is used to check that attempt 2 or 3 has been started or not
     * @param type $params
     * @return int
     */
    public function checkAttemptStartedOrNot($params = array()) {
        $sqlTest = "select attempt_completed from student_test_attempt where id = '" . $params['student_test_attempt_id'] . "'";
        $questionResults = DB::select($sqlTest);
        if ($questionResults[0]->attempt_completed == 3) {
            return $showLinkFlag = 1;
        } else if ($questionResults[0]->attempt_completed == 0) {
            return $showLinkFlag = 1;
        } else {
            $showLinkFlag = 0;
            if ($questionResults[0]->attempt_completed == 1)
                $attempt = '2';
            if ($questionResults[0]->attempt_completed == 2)
                $attempt = '3';

            $sqlTest = "select status,paper_id,attempt,attempt_at from studenttest where test_attempt_id = '" . $params['student_test_attempt_id'] . "' and attempt in (" . $attempt . ")";
            $questionResults = DB::select($sqlTest);

            foreach ($questionResults as $key => $val) {
                if ($val->status == 'Completed') {
                    $questionResultsNew[] = $val->status;
                } else {
                    if ($val->attempt_at != NULL_DATETIME) {
                        $questionResultsNew[] = 'Inprogress';
                    } else {
                        $questionResultsNew[] = 'Pending';
                    }
                }
            }

            $questionResultsFinal = array();
            if (!empty($questionResultsNew)) {
                foreach ($questionResultsNew as $key => $val) {
                    if ($val != 'Inporgress')
                        $questionResultsFinal = array_unique($questionResultsNew);
                }
            }
            sort($questionResultsFinal);
            if (count($questionResultsFinal) == 1) {
                if ($questionResultsFinal[0] == 'Completed' || $questionResultsFinal[0] == 'Pending') {
                    $showLinkFlag = 1;
                }
            }
        }
        return $showLinkFlag;
    }

    /**
     * This is used to make the test attempt completed
     * @param type $params
     * @return type, percentage of the student
     */
    public function makeTestAttemptCompleted($params) {
        $attemptTestPaperAggregate = $this->getAttemptTestPaperAggregate($params);
        $attemptTestPaperAggregate = $attemptTestPaperAggregate[0];
        $attempt_num = $params['attempt'];
        $updateData = array(
            'id' => $params['test_attempt_id'],
            'status' => COMPLETED,
            'attempt_completed' => $attempt_num,
            'last_assessment_date' => $this->currentDateTime,
            'completed_at_attempt_' . $attempt_num => $this->currentDateTime,
            'total_marks_obtain' => $attemptTestPaperAggregate['total_mark_obtain'],
            'total_time_spent' => $attemptTestPaperAggregate['total_timespent'],
            'aggregate_attempt_' . $attempt_num => serialize(array(
                $attemptTestPaperAggregate['total_correct'],
                $attemptTestPaperAggregate['total_mark_obtain'],
                $attemptTestPaperAggregate['total_timespent']
            ))
        );
        if ($attempt_num == 1) {
            $updateData['total_questions'] = $attemptTestPaperAggregate['total_questions'];
            $updateData['total_marks'] = $attemptTestPaperAggregate['total_marks'];
            $updateData['total_time'] = $attemptTestPaperAggregate['total_time'];
        }
        $this->updateStudentTestMeta($updateData);

        //make revision assigned complete, if it is assigned (for teacher's student)
        if ($params['assign_id']) {
            $attempt_status = $attempt_num == 3 ? COMPLETED : INPROGRESS;
            $this->updateStudentTaskAssignCompleted(array(
                'assign_id' => $params['assign_id'],
                'student_id' => $params['student_id'],
                'status' => $attempt_status,
                'assignment_completed_num_update' => $attempt_num == 1 ? TRUE: FALSE
            ));
        }
    }

    public function getAttemptTestPaperAggregate($params) {
        $query = $this->studenttest
                ->select([
            DB::raw('SUM(studenttest.num_question) AS total_questions,SUM(studenttest.total_marks) AS total_marks,SUM(studenttest.quesmaxtime) AS total_time,SUM(studenttest.num_correct) AS total_correct,SUM(studenttest.mark_obtain) AS total_mark_obtain,SUM(TIMESTAMPDIFF(SECOND, studenttest.attempt_at,studenttest.completed_at)) AS total_timespent')
        ]);
        if (isset($params['test_attempt_id'])) {
            $query->where('studenttest.test_attempt_id', '=', $params['test_attempt_id']);
        }
        if (isset($params['attempt'])) {
            $query->where('studenttest.attempt', '=', $params['attempt']);
        }
        return $query->get()->toArray();
    }
    
    public function studentTestResultData($params){ 
        $testAttemptAverage = $this->testAttemptAverage(array(
            'test_attempt_id' => $params['test_attempt_id'],
            'attempt_num' => $params['attempt_num'],
            'cnt_papers' => $params['cnt_papers']
        ));
        if(!count($testAttemptAverage)){
            return FALSE;
        }
        $model = StudentTestResult::firstOrNew([
                    'student_id' => $params['student_id'],
                    'assignment_num' => $params['assignment_num'],
                    'question_set_id' => $params['question_set_id'],
                    'class_id' => $params['class_id']
        ]);
        $attempt_avg = round(array_sum($testAttemptAverage['mark_obtain_pr'])/$params['cnt_papers'],2);
        $att_avg_time = round(array_sum($testAttemptAverage['avg_time'])/$params['cnt_papers'],2);
        if ($model->getOriginal()) {//attemp2 or attempt 3
            $originalData = $model->getOriginal();
            $p1_avg_original = array($originalData['att1_p1'],$originalData['att2_p1'],$originalData['att3_p1']);
            $p2_avg_original = array($originalData['att1_p2'],$originalData['att2_p2'],$originalData['att3_p2']);
            $p3_avg_original = array($originalData['att1_p3'],$originalData['att2_p3'],$originalData['att3_p3']);
            $avg_p_original = array($p1_avg_original,$p2_avg_original,$p3_avg_original);
            
            $p1_avg_time_original = array($originalData['att1_p1_time'],$originalData['att2_p1_time'],$originalData['att3_p1_time']);
            $p2_avg_time_original = array($originalData['att1_p2_time'],$originalData['att2_p2_time'],$originalData['att3_p2_time']);
            $p3_avg_time_original = array($originalData['att1_p3_time'],$originalData['att2_p3_time'],$originalData['att3_p3_time']);
            $p_avg_time_original = array($p1_avg_time_original,$p2_avg_time_original,$p3_avg_time_original);
            
            $att1_avg_marks = $originalData['att1_avg_marks'];
            $att2_avg_marks = $originalData['att2_avg_marks'];
            
            $att1_avg_time = $originalData['att1_avg_time'];
            $att2_avg_time = $originalData['att2_avg_time'];
            
            //$test_attempt_avg_marks = $originalData['test_attempt_avg_marks'];
            //$test_attempt_avg_time = $originalData['test_attempt_avg_time'];
            
            if($params['attempt_num'] == 2){
                for($i = 1; $i <= $params['cnt_papers']; $i++){
                    $att2_p = 'att2_p'.$i;
                    $att2_p_time = 'att2_p'.$i.'_time';
                    $p_avg = 'p'.$i.'_avg';
                    $p_avg_time = 'p'.$i.'_avg_time';
                    $p_last_assessment_date = 'p'.$i.'_last_assessment_date';

                    $model->$att2_p = $testAttemptAverage['mark_obtain_pr'][$i-1];
                    $model->$att2_p_time = $testAttemptAverage['avg_time'][$i-1];
                    $model->$p_avg = round((($testAttemptAverage['mark_obtain_pr'][$i-1] + $avg_p_original[$i-1][0])/2),2);
                    $model->$p_avg_time = round((($testAttemptAverage['avg_time'][$i-1] + $p_avg_time_original[$i-1][0])/2),2);
                    $model->$p_last_assessment_date = $testAttemptAverage['complete_time'][$i-1];
                }
                $model->attempt_count = 2;
                $model->att2_avg_marks = $attempt_avg;
                $model->att2_avg_time = $att_avg_time;
                $model->test_attempt_avg_marks = round(($att1_avg_marks + $attempt_avg)/2,2);
                $model->test_attempt_avg_time = round(($att1_avg_time + $att_avg_time)/2,2);
                $model->att_last_assessment_date = $params['last_assessment_date'];
            }
            if($params['attempt_num'] == 3){
                for($i = 1; $i <= $params['cnt_papers']; $i++){
                    $att3_p = 'att3_p'.$i;
                    $att3_p_time = 'att3_p'.$i.'_time';
                    $p_avg = 'p'.$i.'_avg';
                    $p_avg_time = 'p'.$i.'_avg_time';
                    $p_last_assessment_date = 'p'.$i.'_last_assessment_date';

                    $model->$att3_p = $testAttemptAverage['mark_obtain_pr'][$i-1];
                    $model->$att3_p_time = $testAttemptAverage['avg_time'][$i-1];
                    $model->$p_avg = round((($testAttemptAverage['mark_obtain_pr'][$i-1] + $avg_p_original[$i-1][0] + $avg_p_original[$i-1][1])/3),2);
                    $model->$p_avg_time = round((($testAttemptAverage['avg_time'][$i-1] + $p_avg_time_original[$i-1][0] + $p_avg_time_original[$i-1][1])/3),2);
                    $model->$p_last_assessment_date = $testAttemptAverage['complete_time'][$i-1];
                }
                $model->attempt_count = 3;
                $model->att3_avg_marks = $attempt_avg;
                $model->att3_avg_time = $att_avg_time;
                $model->test_attempt_avg_marks = round(($att1_avg_marks + $att2_avg_marks + $attempt_avg)/3,2);
                $model->test_attempt_avg_time = round(($att1_avg_time + $att2_avg_time + $att_avg_time)/3,2);
                $model->att_last_assessment_date = $params['last_assessment_date'];
            }
        } else {//attempt 1
            for($i = 1; $i <= $params['cnt_papers']; $i++){
                $max_time = 'p'.$i.'_max_time';
                $att1_p = 'att1_p'.$i;
                $att1_p_time = 'att1_p'.$i.'_time';
                $p_avg = 'p'.$i.'_avg';
                $p_avg_time = 'p'.$i.'_avg_time';
                $p_last_assessment_date = 'p'.$i.'_last_assessment_date';
                
                $model->$max_time = $testAttemptAverage['max_time'][$i-1];
                $model->$att1_p = $testAttemptAverage['mark_obtain_pr'][$i-1];
                $model->$att1_p_time = $testAttemptAverage['avg_time'][$i-1];
                $model->$p_avg = $testAttemptAverage['mark_obtain_pr'][$i-1];
                $model->$p_avg_time = $testAttemptAverage['avg_time'][$i-1];
                $model->$p_last_assessment_date = $testAttemptAverage['complete_time'][$i-1];
            }
            $model->attempt_count = 1;
            $model->att1_avg_marks = $attempt_avg;
            $model->att1_avg_time = $att_avg_time;
            $model->test_attempt_avg_marks = $attempt_avg;
            $model->test_attempt_avg_time = $att_avg_time;
            $model->att_last_assessment_date = $params['last_assessment_date'];
            
            $model->test_attempt_id = $params['test_attempt_id'];
            $model->task_id = $params['task_id'];
            $model->student_name = $params['student_name'];
            $model->subject = $params['subject'];
            $model->school_id = $params['school_id'];
            $model->school_name = $params['school_name'];
            $model->teacher_id = $params['teacher_id'];
            $model->teacher_name = $params['teacher_name'];
            $model->tutor_id = $params['tutor_id'];
            $model->tutor_name = $params['tutor_name'];
            $model->class_name = $params['class_name'];
            $model->set_name = $params['set_name'];
            $model->created_at = $params['test_attempt_at'];
            $model->updated_at = $params['test_attempt_at'];
        }
        $model->save();
        return $attempt_avg;
    }
    
    public function testAttemptAverage($params) {
        $testAttemptCompletedPapers = $this->getTestAttemptCompletedPapers(array(
            'test_attempt_id' => $params['test_attempt_id'],
            'attempt' => $params['attempt_num']
        ));
        
        $cntAttemptedPapers = count($testAttemptCompletedPapers);
        if($cntAttemptedPapers && $params['cnt_papers'] == $cntAttemptedPapers){
            foreach($testAttemptCompletedPapers as $cPaper){
                $mark_obtain_pr[] = round(($cPaper['mark_obtain']/$cPaper['total_marks']*100),2);
                $avg_time[] = calculateTaskCompleteTime(array(
                    'quesmaxtime' => $cPaper['quesmaxtime'],
                    'remainingtime' => $cPaper['remainingtime'],
                    'completetime' => $cPaper['attempt_complete_time']
                ));
                $max_time[] = $cPaper['quesmaxtime'];
                $complete_time[] = $cPaper['completed_at'];
            }
            return array('mark_obtain_pr' => $mark_obtain_pr, 'avg_time' => $avg_time ,'max_time' => $max_time,'complete_time' => $complete_time);
        }else{
            return array();
        }
    }
    
    public function getTestAttemptCompletedPapers($params) {
        return $this->studenttest
                ->select(['studenttest.id', 'studenttest.paper_id','studenttest.questionids','studenttest.quesmaxtime', 'studenttest.remainingtime',
                     'studenttest.mark_obtain', 'studenttest.total_marks', 'studenttest.completed_at',DB::raw('TIMESTAMPDIFF(SECOND, studenttest.attempt_at, studenttest.completed_at) AS attempt_complete_time')
        ])
        ->where(['test_attempt_id' => $params['test_attempt_id'],'attempt' => $params['attempt'],'status'=>COMPLETED])
        ->orderBy('paper_id')
        ->get()->toArray();
    }

    /**
     * return assigned test detail for a student
     * @param type $params
     * @return type array of strands assignment details
     */
    public function getStudentTestPaperDetail($params) {
        if ($params['tutor_id']) {
            $testPaperDetail = $this->getTutorStudentTestPaperDetail($params);
        } else {
            unset($params['tutor_id']);
            $testPaperDetail = $this->getSchoolStudentTestPaperDetail($params);
        }
        return count($testPaperDetail) == 0 ? FALSE : $testPaperDetail[0];
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    function getTutorStudentTestPaperDetail($params) {
        return $this->studenttestattempt
                        ->select(['student_test_attempt.id', 'student_test_attempt.task_id', 'task.key_stage',
                            'task.year_group', 'task.subject', 'task.question_set',
                            'questionsets.set_name'
                        ])
                        ->join('task', 'student_test_attempt.task_id', '=', 'task.id')
                        ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                        ->where([
                            'student_test_attempt.id' => $params['id'],
                            'task.status' => ACTIVE
                        ])
                        ->get()->toArray();
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    function getSchoolStudentTestPaperDetail($params) {
        return $this->studenttestattempt
                        ->select(['student_test_attempt.id', 'student_test_attempt.task_id', 'task.key_stage',
                            'task.year_group', 'task.subject', 'task.question_set',
                            'questionsets.set_name', 'users.first_name', 'users.last_name'
                        ])
                        ->join('task', 'student_test_attempt.task_id', '=', 'task.id')
                        ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                        ->join('taskstudents', 'student_test_attempt.student_id', '=', 'taskstudents.student_id')
                        ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                        ->join('users', 'taskassignments.created_by', '=', 'users.id')
                        ->where([
                            'taskstudents.student_id' => $params['student_id'],
                            'student_test_attempt.id' => $params['id'],
                            'task.status' => ACTIVE
                        ])
                        /* ->where('taskassignments.assign_date', '<=', $this->currentDate)
                          ->where('taskassignments.completion_date', '>=', $this->currentDate) */
                        ->get()->toArray();
    }

    public function addQuestionTestPaper($attemptid, $params) {
        $testPaperQuestions = $this->getTestPaperQuestions($params);
        $this->updateStudentTestPaper(array(
            'id' => $attemptid,
            'questionids' => implode(",", $testPaperQuestions['questionids']),
            'status' => 'Inprogress',
            'num_question' => $testPaperQuestions['num_question'],
            'num_remaining' => $testPaperQuestions['num_question'],
            'quesmaxtime' => $testPaperQuestions['max_time'],
            'remainingtime' => $testPaperQuestions['max_time'],
            'total_marks' => $testPaperQuestions['total_marks'],
        ));
        return array(
            'num_question' => $testPaperQuestions['num_question']
        );
    }

    /**
     * get question by ids
     * @param type $ids
     */
    public function getTestPaperQuestions($params) {
        $data = array(
            'num_question' => 0,
            'total_marks' => 0,
            'max_time' => 0,
            'questionids' => array()
        );
        $questionPaper = subjectPapers();
        $data['max_time'] = $questionPaper[$params['subject']][$params['question_paper_id']]['time'];
        $questions = $this->getExaminationQuestions($params);
        if (count($questions)) {
            foreach ($questions as $question) {
                $data['questionids'][] = $question['id'];
                $data['num_question'] ++;
                $data['total_marks'] += $question['total_marks'];
            }
        }
        return $data;
    }

    /**
     * get question by ids
     * @param type $ids
     */
    public function isCompteteTestAttempt($params) {
        $subjectPaper = subjectPapers()[$params['subject']];
        $endPaper = array_slice($subjectPaper, -1, 1, TRUE);
        return $params['question_paper_id'] == key($endPaper) ? TRUE : FALSE;
    }

    /**
     * get question by ids
     * @param type $ids
     */
    public function getExaminationQuestions($params) {
        $query = $this->question
                ->select(['questions.id', 'questions.total_marks', 'questions.strands_id', 'questions.substrands_id']);
        if (isset($params['questionids'])) {
            $query->whereIn('questions.id', $params['questionids']);
        }
        if (isset($params['question_set_id'])) {
            $query->where('questions.question_set_id', $params['question_set_id']);
        }
        if (isset($params['question_paper_id'])) {
            $query->where('questions.paper_id', $params['question_paper_id']);
        }
        if (isset($params['key_stage'])) {
            $query->where('questions.key_stage', $params['key_stage']);
        }
        if (isset($params['year_group'])) {
            $query->where('questions.year_group', $params['year_group']);
        }
        $query->where('questions.status', PUBLISH);
        $query->whereIn('questions.question_type_id', $this->allowedQuestionType);
        return $query->get()->toArray();
    }

    /**
     * this is used to get the assigned topic for student for current date
     * @param type $topicSearchParam
     * @return type
     */
    public function studentActiveRevisionStrands($params) {
        $result = array();
        if ($params['tutor_id']) {
            $activeStrands = $this->getTutorStudentActiveRevisionStrands(array(
                'task_subject' => $params['task_subject'],
                'key_stage' => $params['key_stage'],
                'year_group' => $params['year_group'],
            ));
        } else {
            unset($params['tutor_id']);
            $activeStrands = $this->getSchoolStudentActiveRevisionStrands($params);
        }
        if (count($activeStrands)) {
            foreach ($activeStrands as $strand) {
                $result[] = $strand['strand'];
            }
        }
        return $result;
    }

    /**
     * return active strand for a task for a tutor's student
     * @param type $params
     * @return type arry of strands
     */
    public function getTutorStudentActiveRevisionStrands($params) {
        return $this->task
                        ->select(['task.strand'])
                        ->where([
                            'task.status' => ACTIVE,
                            'task.subject' => $params['task_subject'],
                            'task.task_type' => REVISION,
                            'task.key_stage' => $params['key_stage'],
                            'task.year_group' => $params['year_group'],
                        ])
                        ->groupBy('strand')
                        ->get()->toArray();
    }

    /**
     * return active strand for a task for a school's student
     * @param type $params
     * @return type arry of strands
     */
    public function getSchoolStudentActiveRevisionStrands($params) {
        return $this->model
                        ->select(['task.strand'])
                        ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                        ->join('task', 'taskassignments.task_id', '=', 'task.id')
                        ->where([
                            'task.status' => ACTIVE,
                            'task.subject' => $params['task_subject'],
                            'task.task_type' => REVISION,
                            'task.key_stage' => $params['key_stage'],
                            'task.year_group' => $params['year_group'],
                            'taskstudents.student_id' => $params['student_id'],
                            'taskstudents.attempt_status' => PENDING
                        ])
                        ->where('taskassignments.assign_date', '<=', $this->currentDate)
                        ->where('taskassignments.completion_date', '>=', $this->currentDate)
                        ->groupBy('strand')
                        ->get()->toArray();
    }

    /**
     * return assigned revisions for a student
     * @param type $params
     * @return type array
     */
    public function getStudentAssignedRevisions($params) {
        if ($params['tutor_id']) {
            unset($params['student_id']);
            return $this->getTutorStudentAssignedRevisions($params);
        } else {
            unset($params['tutor_id']);
            return $this->getSchoolStudentAssignedRevisions($params);
        }
    }

    /**
     * return assigned revision for tutor's student
     * @param type $params
     * @return type arry of strands
     */
    public function getTutorStudentAssignedRevisions($params) {
        return $this->task
                        ->select(['task.id', 'task.strand', 'task.substrand AS substrand_id', 'task.key_stage',
                            'task.year_group', 'task.subject',
                            '.strands.strand AS substrand',
                        ])
                        ->join('strands AS strands', 'task.substrand', '=', 'strands.id')
                        ->where([
                            'task.status' => ACTIVE,
                            'task.subject' => $params['task_subject'],
                            'task.strand' => $params['task_strand'],
                            'task.task_type' => REVISION,
                            'task.key_stage' => $params['key_stage'],
                            'task.year_group' => $params['year_group'],
                        ])
                        ->get()->toArray();
    }

    /**
     * return assigned revision for school's student
     * @param type $params
     * @return type arry of strands
     */
    public function getSchoolStudentAssignedRevisions($params) {
        return $this->model
                        ->select(['task.id', 'task.strand', 'task.substrand AS substrand_id', 'task.key_stage',
                            'task.year_group', 'task.subject',
                            '.strands.strand AS substrand',
                        ])
                        ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                        ->join('task', 'taskassignments.task_id', '=', 'task.id')
                        ->join('strands AS strands', 'task.substrand', '=', 'strands.id')
                        ->where([
                            'task.status' => ACTIVE,
                            'task.subject' => $params['task_subject'],
                            'task.strand' => $params['task_strand'],
                            'task.task_type' => REVISION,
                            'task.key_stage' => $params['key_stage'],
                            'task.year_group' => $params['year_group'],
                            'taskstudents.student_id' => $params['student_id'],
                            'taskstudents.attempt_status' => PENDING
                        ])
                        ->where('taskassignments.assign_date', '<=', $this->currentDate)
                        ->where('taskassignments.completion_date', '>=', $this->currentDate)
                        ->groupBy('task.id')
                        ->get()->toArray();
    }

    /**
     * return assigned revision detail for a student
     * @param type $params
     * @return type array
     */
    public function getStudentRevisionDetail($params) {
        if ($params['tutor_id']) {
            unset($params['student_id']);
            $revisionDetail = $this->getTutorStudentRevisionDetail($params);
        } else {
            unset($params['tutor_id']);
            $revisionDetail = $this->getSchoolStudentRevisionDetail($params);
        }
        return $revisionDetail;
    }

    /**
     * return assigned revision detail for a tutor's student
     * @param type $params
     * @return type array of strands assignment details
     */
    public function getTutorStudentRevisionDetail($params) {
        return $this->task
                        ->select(['task.id', 'task.strand', 'task.key_stage',
                            'task.year_group', 'task.subject',
                            '.strands.id AS strand_id', '.strands.strand AS strand',
                            '.substrands.id AS substrand_id', '.substrands.strand AS substrand'
                        ])
                        ->join('strands AS strands', 'task.strand', '=', 'strands.id')
                        ->join('strands AS substrands', 'task.substrand', '=', 'substrands.id')
                        ->where([
                            'task.id' => $params['task_id'],
                            'task.status' => ACTIVE
                        ])
                        ->get()->first();
    }

    /**
     * return assigned revision detail for a school's student
     * @param type $params
     * @return type array of strands assignment details
     */
    public function getSchoolStudentRevisionDetail($params) {
        return $this->model
                        ->select(['task.id', 'task.strand', 'task.key_stage',
                            'task.year_group', 'task.subject', 'taskassignments.id AS assign_id', 'taskassignments.difficulty',
                            'taskassignments.assign_date', 'taskassignments.completion_date',
                            '.strands.id AS strand_id', '.strands.strand AS strand',
                            '.substrands.id AS substrand_id', '.substrands.strand AS substrand',
                            'users.first_name', 'users.last_name'
                        ])
                        ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                        ->join('task', 'taskassignments.task_id', '=', 'task.id')
                        ->join('strands AS strands', 'task.strand', '=', 'strands.id')
                        ->join('strands AS substrands', 'task.substrand', '=', 'substrands.id')
                        ->join('users', 'taskassignments.created_by', '=', 'users.id')
                        ->where([
                            'taskstudents.student_id' => $params['student_id'],
                            'taskstudents.attempt_status' => PENDING,
                            'task.id' => $params['task_id'],
                            'task.status' => ACTIVE
                        ])
                        /* ->where('taskassignments.assign_date', '<=', $this->currentDate)
                          ->where('taskassignments.completion_date', '>=', $this->currentDate) */
                        ->orderby('taskassignments.created_at')
                        ->get()->first();
    }

    public function strandAttributesList() {
        try {
            return array(
                "Math" => array(
                    98 => array('id' => '98', 'class' => 'colAlgebra', 'icon_l' => 'algebra_icon.png', 'icon_s' => 'algebra_icon.png'),
                    14 => array('id' => '14', 'class' => 'colNumber', 'icon_l' => 'number_icon.png', 'icon_s' => 'number_icon.png'),
                    122 => array('id' => '122', 'class' => 'colStats', 'icon_l' => 'stats_icon.png', 'icon_s' => 'stats_icon.png'),
                    113 => array('id' => '113', 'class' => 'colGeo', 'icon_l' => 'geometry_icon.png', 'icon_s' => 'geometry_icon.png'),
                    78 => array('id' => '78', 'class' => 'colFract', 'icon_l' => 'fraction_icon.png', 'icon_s' => 'fraction_icon.png'),
                    93 => array('id' => '93', 'class' => 'colRatio', 'icon_l' => 'ratio_icon.png', 'icon_s' => 'ratio_icon.png'),
                    100 => array('id' => '100', 'class' => 'colMeasure', 'icon_l' => 'measure_icon.png', 'icon_s' => 'measure_icon.png'),
                    48 => array('id' => '48', 'class' => 'colAdd', 'icon_l' => 'addition_icon.png', 'icon_s' => 'addition_icon.png')
                ),
                "English" => array(
                    176 => array('id' => '176', 'class' => 'colSpell', 'icon_l' => 'spelling_icon.png', 'icon_s' => 'spelling_icon.png'),
                    1 => array('id' => '1', 'class' => 'colGrammer', 'icon_l' => 'grammer_icon.png', 'icon_s' => 'grammer_icon.png'),
                    128 => array('id' => '128', 'class' => 'colFunction', 'icon_l' => 'function_sent_icon.png', 'icon_s' => 'function_sent_icon.png'),
                    133 => array('id' => '133', 'class' => 'colLink', 'icon_l' => 'link_icon.png', 'icon_s' => 'link_icon.png'),
                    141 => array('id' => '141', 'class' => 'colVerb', 'icon_l' => 'verb_icon.png', 'icon_s' => 'verb_icon.png'),
                    150 => array('id' => '150', 'class' => 'colPunctuation', 'icon_l' => 'punctuation_icon.png', 'icon_s' => 'punctuation_icon.png'),
                    166 => array('id' => '166', 'class' => 'colVocab', 'icon_l' => 'vocab_icon.png', 'icon_s' => 'vocab_icon.png'),
                    171 => array('id' => '171', 'class' => 'colStandEng', 'icon_l' => 'stand_eng_icon.png', 'icon_s' => 'stand_eng_icon.png')
                )
            );
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    /**
     * return student random stored question against a task
     * @param type $params
     * @return type
     */
    public function getStudentStoredRevision($params) {
        $query = $this->studentrevision
                ->select([
            'studentrevision.id', 'studentrevision.task_id', 'studentrevision.assign_id', 'studentrevision.attempt', 'studentrevision.questionids',
            'studentrevision.num_question', 'studentrevision.total_marks',
            'studentrevision.num_answered', 'studentrevision.num_skipped',
            'studentrevision.num_remaining', 'studentrevision.quesmaxtime', 'studentrevision.remainingtime',
            'studentrevision.attempt_at', 'studentrevision.status', 'studentrevision.num_correct', 'studentrevision.mark_obtain',
            DB::raw('(studentrevision.attempt_at + INTERVAL studentrevision.quesmaxtime SECOND ) AS complete_time, TIMESTAMPDIFF(SECOND, attempt_at, "' . $this->currentDateTime . '") AS attempt_time,TIMESTAMPDIFF(SECOND, studentrevision.attempt_at, studentrevision.completed_at) AS attempt_complete_time')
        ]);
        if (isset($params['student_id'])) {
            $query->where('studentrevision.student_id', '=', $params['student_id']);
        }
        if (isset($params['student_revisionid'])) {
            $query->where('studentrevision.id', '=', $params['student_revisionid']);
        } if (isset($params['task_id'])) {
            $query->where('studentrevision.task_id', '=', $params['task_id']);
        }
        $query->orderBy('studentrevision.attempt', 'DESC');
        $result = $query->get()->first();
        return $result ? $result->toArray() : array();
    }

    /**
     * prepare student revision question against a task
     * @param type $params
     */
    public function saveStudentStoredRevision($params) {
        //get random question from question table against strand and substrand
        $randomRevisionQuestions = $this->getRandomRevisionQuestions(array(
            'subject' => $params['subject'],
            'strand_id' => $params['strand_id'],
            'substrand_id' => $params['substrand_id'],
            'key_stage' => $params['key_stage'],
            'year_group' => $params['year_group'],
            'difficulty' => isset($params['difficulty']) ? $params['difficulty'] : array(),
            'questionIdNotIn' => $params['questionIdNotIn'],
            'limit' => $params['limit']
        ));

        array_rand($randomRevisionQuestions, count($randomRevisionQuestions)); // added for random questions on 8 mar 
        //save random question into revision student for next time retrival
        if (count($randomRevisionQuestions)) {
            $total_mark = 0;
            foreach ($randomRevisionQuestions as $rqus) {
                $questionids[] = $rqus['id'];
                $total_mark = $total_mark + $rqus['total_marks'];
            }
            $num_question = count($questionids);
            //save randam revision question against a student and task
            $studentrevisionId = $this->saveStudentRevision(array(
                'task_id' => $params['task_id'],
                'assign_id' => isset($params['assign_id']) ? $params['assign_id'] : 0,
                'student_id' => $params['student_id'],
                'attempt' => $params['attempt'],
                'questionids' => implode(",", $questionids),
                'num_question' => $num_question,
                'total_marks' => $total_mark,
                'max_time' => REVISION_TIME
            ));
            return array(
                'id' => $studentrevisionId,
                'num_question' => $num_question,
                'quesmaxtime' => REVISION_TIME,
                'total_marks' => $total_mark,
                'attempt_time' => 0,
                'status' => '".PENDING."',
                'questionids' => '',
                'complete_time' => ''
            );
        } else {
            return FALSE;
        }
    }

    /**
     * Get revision questions .
     *
     * @param  integer  $id
     * @return array
     */
    public function getRandomRevisionQuestions($params) {
        $query = $this->question
                ->select(['questions.id', 'questions.total_marks'])
                ->where([
            'questions.status' => PUBLISH,
            'questions.set_group' => REVISION,
            'questions.subject' => $params['subject'],
            'questions.strands_id' => $params['strand_id'],
            'questions.substrands_id' => $params['substrand_id'],
            'questions.key_stage' => $params['key_stage'],
            'questions.year_group' => $params['year_group']]);
        if (count($params['difficulty'])) {
            $query->whereIn('questions.difficulty', $params['difficulty']);
        }
        if (count($params['questionIdNotIn'])) {
            $query->whereNotIn('questions.id', $params['questionIdNotIn']);
        }
        $query->whereIn('questions.question_type_id', $this->allowedQuestionType)
                ->orderBy(DB::raw('RAND()'))
                ->take($params['limit']);
        return $query->get()->toArray();
    }

    /**
     * save the random revision question against a student and task_id
     * @param type $input
     */
    public function saveStudentRevision($input) {
        $studentrevision = new $this->studentrevision;
        $studentrevision->task_id = $input['task_id'];
        $studentrevision->student_id = $input['student_id'];
        $studentrevision->assign_id = $input['assign_id'];
        $studentrevision->attempt = $input['attempt'];
        $studentrevision->questionids = $input['questionids'];
        $studentrevision->total_marks = $input['total_marks'];
        $studentrevision->num_question = $input['num_question'];
        $studentrevision->num_remaining = $input['num_question'];
        $studentrevision->quesmaxtime = $input['max_time'];
        $studentrevision->remainingtime = $input['max_time'];
        return $studentrevision->save() ? $studentrevision->id : FALSE;
    }

    /**
     * save the random revision question against a student and task_id
     * @param type $input
     */
    public function updateStudentRevision($input) {
        $model = $this->studentrevision->findOrFail($input['id']);
        if (isset($input['num_question'])) {
            $model->num_question = $input['num_question'];
        }
        if (isset($input['num_remaining'])) {
            $model->num_remaining = $input['num_remaining'];
        }
        if (isset($input['quesmaxtime'])) {
            $model->quesmaxtime = $input['quesmaxtime'];
        }
        if (isset($input['remainingtime'])) {
            $model->remainingtime = $input['remainingtime'];
        }
        if (isset($input['total_mark'])) {
            $model->total_mark = $input['total_mark'];
        }
        if (isset($input['num_skipped'])) {
            $model->num_skipped = $input['num_skipped'];
        }
        if (isset($input['num_answered'])) {
            $model->num_answered = $input['num_answered'];
        }
        if (isset($input['attempt_at'])) {
            $model->attempt_at = $input['attempt_at'];
        }
        if (isset($input['status'])) {
            $model->status = $input['status'];
        }
        if (isset($input['num_correct'])) {
            $model->num_correct = $input['num_correct'];
        }
        if (isset($input['mark_obtain'])) {
            $model->mark_obtain = $input['mark_obtain'];
        }
        if (isset($input['completed_at'])) {
            $model->completed_at = $input['completed_at'];
        }
        return $model->save() ? $model->id : FALSE;
    }

    public function saveDefaultQuestionAnswer($params) {
        if (count($params['question_ids'])) {
            foreach ($params['question_ids'] as $questionid) {
                if($params['task_type'] == TEST){
                    $dataAnsTemp = array(
                        'attempt_id' => $params['attempt_id'],
                        'task_type' => $params['task_type'],
                        'question_id' => $questionid,
                        'student_id' => $params['student_id'],
                        'set_id' => $params['set_id'],
                        'test_attempt_id' => $params['test_attempt_id'],
                        'paper_id' => $params['paper_id'],
                        'attempt_num' => $params['attempt_num'],
                        'created_at' => $this->currentDateTime,
                        'updated_at' => $this->currentDateTime,
                    );
                }else{
                    $dataAnsTemp = array(
                        'attempt_id' => $params['attempt_id'],
                        'task_type' => $params['task_type'],
                        'question_id' => $questionid,
                        'student_id' => $params['student_id'],
                        'created_at' => $this->currentDateTime,
                        'updated_at' => $this->currentDateTime,
                    );
                }
                
                $dataAns[] = $dataAnsTemp;
            }
            $this->questionanswer->insert($dataAns);
        }
    }

    public function updateStudentTaskAssignCompleted($params) {
        $this->model
                ->where(['assign_id' => $params['assign_id'], 'student_id' => $params['student_id']])
                ->limit(1)
                ->update(array('attempt_status' => $params['status']));
        if($params['assignment_completed_num_update']){
            $model = Taskassignment::findOrFail($params['assign_id']);
            $model->student_attempt_completed_num = $model->student_attempt_completed_num + 1;
            $model->save();
        }
    }

    public function isTestPaperAttemptCompleted($params) {
        if ($params['status'] == COMPLETED) {
            return true;
        } elseif ($params['status'] == 'Inprogress' && $params['attempt_time'] > $params['quesmaxtime']) {
            //make revision attempt completed (status = completed)
            $this->makeTestPaperAttemptCompleted(array(
                'attempt_id' => $params['attemptid'],
                'questionids' => $params['questionids'],
                'complete_time' => $params['complete_time'],
                'student_id' => $params['student_id']
            ));
            return true;
        } else {
            return false;
        }
    }

    public function makeTestPaperAttemptCompleted($params) {
        $attemptAnswerAggregate = $this->getAttemptAnswerAggregate(array(
            'attempt_id' => $params['attempt_id'],
            'task_type' => TEST
        ));
        //update testpaper attempt, make it completed (update studenttest table)
        $this->updateStudentTestPaper(array(
            'id' => $params['attempt_id'],
            'status' => COMPLETED,
            'num_correct' => $attemptAnswerAggregate['num_correct'],
            'mark_obtain' => $attemptAnswerAggregate['mark_obtained'],
            'completed_at' => $params['complete_time'],
        ));

        //store strand mark record for this completed paper
        $this->studentStrandMeta(array(
            'attempt_id' => $params['attempt_id'],
            'questionids' => $params['questionids'],
            'task_type' => TEST,
            'student_id' => $params['student_id']
        ));

        return $attemptAnswerAggregate;
    }

    public function makeRevisionAttemptCompleted($params) {
        $attemptAnswerAggregate = $this->getAttemptAnswerAggregate(array(
            'attempt_id' => $params['attempt_id'],
            'task_type' => REVISION
        ));
        //make attempt revision completed (update studentrevision table)
        $this->updateStudentRevision(array(
            'id' => $params['attempt_id'],
            'status' => COMPLETED,
            'num_correct' => $attemptAnswerAggregate['num_correct'],
            'mark_obtain' => $attemptAnswerAggregate['mark_obtained'],
            'completed_at' => $params['complete_time'],
        ));
        //make revision assigned complete, if it is assigned (for teacher's student)
        if ($params['assign_id']) {
            $this->updateStudentTaskAssignCompleted(array(
                'assign_id' => $params['assign_id'],
                'student_id' => $params['student_id'],
                'status' => COMPLETED,
                'assignment_completed_num_update' => TRUE
            ));
        }

        //store strand mark record for this completed revision
        $this->studentStrandMeta(array(
            'attempt_id' => $params['attempt_id'],
            'questionids' => $params['questionids'],
            'task_type' => REVISION,
            'student_id' => $params['student_id']
        ));
        return $attemptAnswerAggregate;
    }

    public function getStudentQuestionAnswer($params) {
        $query = $this->questionanswer
                ->select(['questionanswer.id', 'questionanswer.question_id', 'questionanswer.iscorrect', 'questionanswer.mark_obtain'
        ]);
        if (isset($params['task_type'])) {
            $query->where('questionanswer.task_type', $params['task_type']);
        }
        if (isset($params['attempt_id'])) {
            $query->where('questionanswer.attempt_id', $params['attempt_id']);
        }
        if (isset($params['task_type']) && $params['task_type'] == REVISION) {
            $query->orderBy('questionanswer.id');
        } else {
            $query->orderBy('questionanswer.question_id');
        }
        return $query->get()->toArray();
    }

    /**
     * return student stored test question
     * @param type $params
     */
    public function getStudentTestQuestions($params) {
        $questionIds = explode(",", $params['questionids']);
        $questions = $this->getExaminationQuestionAnswer(array(
            'questionids' => $questionIds,
            'attempt_id' => $params['id'],
            'task_type' => TEST
        ));
        $isCorrectAnsData = isset($params['isCorrectAnsData']) ? TRUE : FALSE;
        $questions = $this->prepareQuestionData($questions, $isCorrectAnsData, TEST);
        $remainingtime = isset($params['remainingtime']) ? $params['remainingtime'] : ($params['quesmaxtime'] - $params['attempt_time']);
        //$remainingtime = $remainingtime < 0 ? 0 : $remainingtime;
        $jsonData = array(
            'questionsummary' => array(
                'is_complete_attempt' => isset($params['is_complete_attempt']) ? $params['is_complete_attempt'] : 0,
                'attemptid' => $params['id'],
                'task_type' => TEST,
                'totalques' => $params['num_question'],
                'answered' => $params['num_answered'],
                'skipped' => $params['num_skipped'],
                'remaining' => $params['num_remaining'],
                'remainingtime' => $remainingtime,
                'quesmaxtime' => $params['quesmaxtime'],
                'totalmarks' => $params['total_marks']
            ),
            'questions' => $questions
        );
        return $jsonData;
    }

    /**
     * return student test paper question
     * @param type $params
     */
    public function getStudentRevisionQuestions($params) {
        $questionIds = explode(",", $params['questionids']);
        $questions = $this->getExaminationQuestionAnswer(array(
            'questionids' => $questionIds,
            'attempt_id' => $params['id'],
            'task_type' => REVISION
        ));
        //  asd($questions);
        $isCorrectAnsData = isset($params['isCorrectAnsData']) ? TRUE : FALSE;
        $questions = $this->prepareQuestionData($questions, $isCorrectAnsData, REVISION);
        $remainingtime = isset($params['remainingtime']) ? $params['remainingtime'] : ($params['quesmaxtime'] - $params['attempt_time']);
        //$remainingtime = $remainingtime < 0 ? 0 : $remainingtime;
        $jsonData = array(
            'questionsummary' => array(
                'attemptid' => $params['id'],
                'task_type' => REVISION,
                'totalques' => $params['num_question'],
                'answered' => $params['num_answered'],
                'skipped' => $params['num_skipped'],
                'remaining' => $params['num_remaining'],
                'remainingtime' => $remainingtime,
                'quesmaxtime' => $params['quesmaxtime'],
                'totalmarks' => $params['total_marks']
            ),
            'questions' => $questions
        );
        return $jsonData;
    }

    /**
     * get question by ids
     * @param type $ids
     */
    public function getExaminationQuestionAnswer($params) {
        if ($params['task_type'] == REVISION)
            $orderBy = 'questionanswer.id';
        else
            $orderBy = 'questions.id';
        return $this->question
                        ->select(['questions.id', 'questions.question_type_id', 'questions.description',
                            'questions.descvisible', 'questions.sub_questions', 'questions.sub_questions_ans', 'questionanswer.id AS attempt_id', 'questionanswer.attempt_status', 'questionanswer.iscorrect'
                            , 'questionanswer.question_answer'
                        ])
                        ->leftJoin('questionanswer', function($join) use ($params) {
                            $join->on('questions.id', '=', 'questionanswer.question_id');
                            $join->on('questionanswer.attempt_id', '=', DB::raw($params['attempt_id']));
                            $join->on('questionanswer.task_type', '=', DB::raw("'" . $params['task_type'] . "'"));
                        })
                        ->whereIn('questions.id', $params['questionids'])
                        ->orderBy($orderBy)
                        ->get()->toArray();
    }

    /**
     * prepare examination question data
     * @param type $questions
     * @return type array $questionData
     */
    public function prepareQuestionDataDemo($questions, $isCorrectAnsData) {
        $questionData = array();
        if (count($questions)) {
            $cnt = 1;
            foreach ($questions as $row) {
                $questionData[$row['id']] = array(
                    'id' => $row['id'],
                    'questiontype' => (string) $row['question_type_id'],
                    'userresponse' => unserialize($row['question_answer'])
                );
                if ($isCorrectAnsData) {
                    $questionData[$row['id']]['correctAns'] = $this->addCorrectAnsData(array(
                        'question_type' => $row['question_type_id'],
                        'sub_questions' => $row['sub_questions'],
                        'sub_questions_ans' => $row['sub_questions_ans']
                    ));
                }
                $cnt++;
            }
            ksort($questionData);
            $questionData = array_values($questionData);
        }
        return $questionData;
    }

    /**
     * prepare examination question data
     * @param type $questions
     * @return type array $questionData
     */
    public function prepareQuestionData($questions, $isCorrectAnsData, $task_type) {
        $questionData = array();
        if (count($questions)) {
            $cnt = 1;
            foreach ($questions as $row) {
                $questionData[$row['id']] = array(
                    'id' => $row['id'],
                    'attempt_id' => $row['attempt_id'],
                    'attempstatus' => $row['attempt_status'],
                    'questiontype' => (string) $row['question_type_id'],
                    'description' => $row['description'],
                    'descvisible' => $row['descvisible'],
                    'questions' => $this->prepareSubQuestion(array(
                        'question_type' => $row['question_type_id'],
                        'sub_questions' => $row['sub_questions'],
                        'sub_questions_ans' => $row['sub_questions_ans']
                    )),
                    'userresponse' => unserialize($row['question_answer']),
                    'num_question' => $cnt,
                );
                if ($isCorrectAnsData) {
                    $questionData[$row['id']]['attempiscorrect'] = $row['iscorrect'];
                    $questionData[$row['id']]['correctAns'] = $this->addCorrectAnsData(array(
                        'question_type' => $row['question_type_id'],
                        'sub_questions' => $row['sub_questions'],
                        'sub_questions_ans' => $row['sub_questions_ans']
                    ));
                }
                $cnt++;
            }
            if ($task_type == TEST)
                ksort($questionData);
            $questionData = array_values($questionData);
        }
        return $questionData;
    }

    public function addCorrectAnsData($params) {

        $correctAns = array();
        if (!empty($params['sub_questions']) && !empty($params['sub_questions_ans'])) {
            $questionDetailRow = unserialize($params['sub_questions']);
            $sub_questions_ans = unserialize($params['sub_questions_ans']);
            foreach ($questionDetailRow as $key => $value) {
                switch ($params['question_type']):
                    case 6;
                        $correctAns[] = $sub_questions_ans[$key][0];
                        //$correctAns[] = $sub_questions_ans[$key];
                        break;
                    case 9;
                        $tempCorrectAns = array('value' => $sub_questions_ans[$key]);
                        $correctAns[] = $tempCorrectAns;
                        break;
                    case 32;
                        $sub_questions_ans = $sub_questions_ans[0];
                        foreach ($value['option'] as $kOpt => $option) {
                            if ($kOpt == 0) {
                                $sub_questions_ans[0] = $option;
                            } else {
                                $sub_questions_ans[$kOpt][0] = $option[0];
                            }
                        }
                        $tempCorrectAns[0] = array('option' => $sub_questions_ans);
                        $correctAns = $tempCorrectAns;
                        break;
                    case 31;
                        $tempCorrectAns = array();
                        foreach ($value['option'] as $kOpt => $option) {
                            $tempCorrectAnsOuter = '';
                            if (isset($sub_questions_ans[$key][$kOpt])) {
                                $k = 0;
                                foreach ($sub_questions_ans[$key][$kOpt] as $k => $value) {
                                    //if (!empty($value['value'])) {
                                    if ($value['value']!='') {
                                        $tempCorrectAnsOuter .= $k != 0 ? "," . trim($value['value']) : trim($value['value']);
                                        $k++;
                                    }
                                }
                            }
                            $tempCorrectAns[$kOpt] = array('value' => $tempCorrectAnsOuter);
                        }
                        $correctAns[] = $tempCorrectAns;
                        break;
                    case 13;
                        $justifypart = $value['showsmultiple'] ? $value['elseques'] : array();
                        $tempCorrectAns = array(0 => array('main' => $sub_questions_ans[$key], 'justifypart' => $justifypart));
                        $correctAns[] = $tempCorrectAns;
                        break;
                    case 4;
                        $tempCorrectAns = array();
                        foreach ($value['option'] as $kOpt => $option) {
                            $optionTemp[] = html_entity_decode($option['right']);
                        }
                        foreach ($value['option'] as $kOpt => $option) {
                            $tempCorrectAns[] = array('source' => (string) ($kOpt), 'target' => (string) array_search(html_entity_decode($sub_questions_ans[$key][$kOpt]), $optionTemp));
                        }
                        $correctAns[] = $tempCorrectAns;
                        break;

                    case 12;
                        $tempCorrectAns = array();
                        foreach ($value['option']['optionvalue'] as $kOpt => $option) {
                            if (isset($sub_questions_ans[$key][$kOpt]['ischeck']) && $sub_questions_ans[$key][$kOpt]['ischeck']) {
                                $tempCorrectAns[] = $option['value'];
                            }
                        }
                        $correctAns[] = $tempCorrectAns;
                        break;
                    case 10;
                        $tempCorrectAns = array('option' => $value['option']);
                        $correctAns[] = $tempCorrectAns;
                        break;

                    default:
                        $correctAns[] = $sub_questions_ans[$key];
                endswitch;
            }
        }
        return $correctAns;
    }

    public function prepareSubQuestion($params) {
        $questions = !empty($params['sub_questions']) ? unserialize($params['sub_questions']) : array();
        $questionsData = array();
        switch ($params['question_type']):
            case 16:
                $question_ans = !empty($params['sub_questions_ans']) ? unserialize($params['sub_questions_ans']) : array();
                foreach ($questions as $question_key => $question_value) {
                    foreach ($question_ans[$question_key] as $question_ans_key => $question_ans_value) {
                        $question_ans_output[$question_ans_key]['label'] = $question_ans_value['label'];
                    }
                    $question_value['correctAns'] = $question_ans_output;
                    unset($question_ans_output);
                    $questionsData[$question_key] = $question_value;
                }
                break;
            case 28:
                $question_ans = !empty($params['sub_questions_ans']) ? unserialize($params['sub_questions_ans']) : array();
                foreach ($questions as $question_key => $question_value) {
                    foreach ($question_ans[$question_key] as $question_ans_key => $question_ans_value) {
                        $question_ans_output[$question_ans_key]['label'] = $question_ans_value['label'];
                    }
                    $question_value['correctAns'] = $question_ans_output;
                    unset($question_ans_output);
                    $questionsData[$question_key] = $question_value;
                }
                break;
            default :
                $questionsData = $questions;
        endswitch;
        return $questionsData;
    }

    public function saveQuestionAnswer($input) {
        $questionsummary = $input['questionsummary'];
        $questions = $input['questions'];
        $userResponseCutomData = array(
            'iscorrect' => FALSE,
            'mark_obtain' => 0,
            'question_answer_status' => array()
        );
        if ($questions['attempstatus'] != 'skipped') {
            $userResponseCutomData = $this->userResponseCutomData(array(
                'question_id' => $questions['id'],
                'userresponse' => $questions['userresponse']
            ));
        }
        $questionanswer = $this->questionanswer->firstOrNew([
            'attempt_id' => $questionsummary['attemptid'],
            'question_id' => $questions['id'],
            'task_type' => $questionsummary['task_type']
        ]);
        $questionanswer->question_answer = serialize($questions['userresponse']);
        $questionanswer->attempt_status = $questions['attempstatus'];
        $questionanswer->iscorrect = $userResponseCutomData['iscorrect'];
        $questionanswer->mark_obtain = $userResponseCutomData['mark_obtain'];
        $questionanswer->question_answer_status = serialize($userResponseCutomData['question_answer_status']);
        $questionanswer->save();
    }

    public function userResponseCutomData($params) {
        $question = $this->getQuestion($params['question_id']);
        $sub_questions = !empty($question['sub_questions']) ? unserialize($question['sub_questions']) : array();
        $sub_questions_ans = !empty($question['sub_questions_ans']) ? unserialize($question['sub_questions_ans']) : array();
        $userResponse = $params['userresponse'];
        $isQusAnsCorrect = FALSE;
        $markObtained = 0;
        $answerStatus = array();
        foreach ($sub_questions as $key => $sub_question) {

            $correctAnswer = 0;
            $correctAnswerAdditional = 0;
            if ($question['question_type_id'] == 10) {
                $correctAnswer = isset($sub_questions[$key]['option']) ? $sub_questions[$key]['option'] : 0;
            } else if ($question['question_type_id'] == 12) {
                $tempCorrectAns = array();
                foreach ($sub_question['option']['optionvalue'] as $kOpt => $option) {
                    if (isset($sub_questions_ans[$key][$kOpt]['ischeck']) && $sub_questions_ans[$key][$kOpt]['ischeck']) {
                        $tempCorrectAns[] = strip_tags(@$option['value']);
                    }
                }
                $correctAnswer = $tempCorrectAns;
            } elseif ($question['question_type_id'] == 4) {
                $tempCorrectAns = array();
                foreach ($sub_question['option'] as $kOpt => $option) {
                    $optionTemp[] = html_entity_decode(@$option['right']);
                }
                foreach ($sub_question['option'] as $kOpt => $option) {
                    $tempCorrectAns[] = array('source' => (string) ($kOpt), 'target' => (string) array_search(html_entity_decode(@$sub_questions_ans[$key][$kOpt]), $optionTemp));
                }

                $correctAnswer = $tempCorrectAns;
            } elseif ($question['question_type_id'] == 13) {
                $justifypart = isset($sub_question['showsmultiple']) && isset($sub_question['elseques']) && $sub_question['showsmultiple'] ? $sub_question['elseques'] : array();
                $tempCorrectAns[] = array('main' => @$sub_questions_ans[$key], 'justifypart' => $justifypart);
                $correctAnswer = $tempCorrectAns;
            } else
                $correctAnswer = $sub_questions_ans[$key];

            $params = array(
                'question_type' => $question['question_type_id'],
                'correctAnswer' => $correctAnswer,
                'userResponse' => $userResponse[$key],
                'total_mark' => $sub_question['mark'],
                'step_mark' => $sub_question['correctmark'],
                'correctAnswerAdditional' => $correctAnswerAdditional
            );
            $responseData = $this->compareUserResponse($params);
            if ($responseData['iscorrect']) {
                $isQusAnsCorrect = TRUE;
            }
            $markObtained += $responseData['mark'];
            $answerStatus[$key] = array($responseData['iscorrect'], $responseData['mark']);
        }
        $return = array('iscorrect' => $isQusAnsCorrect, 'mark_obtain' => $markObtained, 'question_answer_status' => $answerStatus);
        return $return;
    }

    public function getQuestion($questionId) {
        return $this->question->findOrFail($questionId, ['id', 'sub_questions', 'sub_questions_ans', 'question_type_id'])->toArray();
    }

    public function compareUserResponse($params) {
        $questionType = $params['question_type'];
        unset($params['question_type']);
        switch ($questionType):
            case 1: // Multiple Choice with Single Answer
                $compareData = $this->compareMultipleChoiceSingleAnswer($params);
                break;
            case 2: //Multiple Choice with Multiple Answers
                $compareData = $this->compareMultipleChoiceMultipleAnswer($params);
                break;
            case 3: //Fill in the blanks
                $compareData = $this->compareFillInTheBlanksAnswer($params);
                break;
            case 8: //Spelling with Audio
                $compareData = $this->compareFillInTheBlanksAnswer($params);
                break;
            case 11: // Drag Drop & Re-ordering
                $compareData = $this->compareDragdropReordering($params);
                break;
            case 6: // Numerical/Text Box Single or Multiple Question (image) with Keywords
                $compareData = $this->compareNumericTextboxSingle($params);
                break;
            case 7: // Insert Literacy Feature
                $compareData = $this->compareMultipleInsertLiteracyFeature($params);
                break;
            case 15: // Label Literacy Feature
                $compareData = $this->compareLabelLiteracyFeature($params);
                break;
            case 20: // Measure Line and Angle with text box (image)
                $compareData = $this->compareMeasureLineAndAngle($params);
                break;
            case 22: // Missing Number on Image
                $compareData = $this->compareMissingNumberOnImage($params);
                break;
            case 16: // Missing Words on Image
                $compareData = $this->compareMissingWordOnImage($params);
                break;
            case 14: // Select Literacy Feature
                $compareData = $this->compareLiteracyFeature($params);
                break;
            case 9: // Underline Literacy Feature
                $compareData = $this->compareUnderlineLiteracyFeature($params);
                break;
            case 12: //  Number /Word Selection (Circle)
                $compareData = $this->compareWordSelection($params);
                break;
            case 21: //  Shading Shapes
                $compareData = $this->compareShadingShapes($params);
                break;
            case 10: //  Table - Single/Multiple entry
                $compareData = $this->compareTableSingleMultipleEntry($params);
                break;
            case 13: //  Boolean type Question
                $compareData = $this->compareBooleanTypeQuestion($params);
                break;
            case 31: //  Input on Image
                $compareData = $this->compareInputOnImage($params);
                break;
            case 30: //  Line of Symmetry Question
                $compareData = $this->compareLineofSymmetryQuestion($params);
                break;
            case 17: //  Reflection
                $compareData = $this->compareReflectionBottomTop($params);
                break;
            case 23: //  Reflection (Left - Right)
                $compareData = $this->compareReflectionBottomTop($params);
                break;
            case 24: // Reflection (Bottom - Top)
                $compareData = $this->compareReflectionBottomTop($params);
                break;
            case 25: // Reflection (Top - Bottom)
                $compareData = $this->compareReflectionBottomTop($params);
                break;
            case 26: // Reflection (Left - Diagonal)
                $compareData = $this->compareReflectionBottomTop($params);
                break;
            case 27: // Reflection (Right - Diagonal)
                $compareData = $this->compareReflectionBottomTop($params);
                break;
            case 18: // Joining Dots on Diagram/Grid
                $compareData = $this->compareJoiningDotsOnDiagram($params);
                break;
            case 28: // Measure Line and Angle with text box (image)
                $compareData = $this->compareMeasureLineAndAngleImage($params);
                break;
            case 29: // Pie Chart
                $compareData = $this->comparePieChart($params);
                break;
            case 4: // Matching with drag & drop question
                $compareData = $this->compareMatchingDragDropQuestion($params);
                break;
            case 19: // Draw line on Image
                $compareData = $this->compareDrawLineOnImage($params);
                break;
            case 32: // Table - Fill Blanks
                $compareData = $this->compareTableFillBlanks($params);
                break;
            /*
              1,2,3,6,7,8,9,10,11,12,13,14,15,16,17,18,20,21,22,23,24,25,26,27,30,31 (Total=26)
             * 4, 19, 28, 29, 32
             *              */
            //case 4: 
            //$compareData = $this->compareMatchingDragDrop($params);
            //break;
            default:
                $compareData = $this->compareDefault($params);
                break;
        endswitch;
        return $compareData;
    }

    public function calculateStepMark($step_mark, $cntCorrect) {
        foreach ($step_mark as $row) {
            if (isset($row['val']) && $cntCorrect == $row['val']) {
                return isset($row['marks']) ? $row['marks'] : 1;
            }
            if (!isset($row['val'])) {
                return 1;
            }
        }
        return 0;
    }

    public function compareDefault($params) {
        $array[] = array('iscorrect' => 0, 'mark' => 0);
        $array[] = array('iscorrect' => 1, 'mark' => 1);
        return $array[rand(0, 1)];
        //return array('iscorrect' => FALSE, 'mark' => 0);
    }

    public function compareMultipleChoiceSingleAnswer($params) {
        if ((int) $params['correctAnswer'] === (int) $params['userResponse']) {
            $iscorrect = TRUE;
            $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
        } else {
            $iscorrect = FALSE;
            $mark = 0;
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMultipleChoiceMultipleAnswer($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        $correctAnsCount = 0;
        $responceAnsCount = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($correctAnswer['ischeck']) && $correctAnswer['ischeck']) {
                    $correctAnsCount++;
                }
            }
            foreach ($params['userResponse'] as $key => $userResponse) {
                if (isset($userResponse['ischeck']) && $userResponse['ischeck']) {
                    $responceAnsCount++;
                }
            }
            if ($correctAnsCount >= $responceAnsCount) {
                $iscorrect = TRUE;
                foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                    if (isset($params['userResponse'][$key])) {
                        if (isset($correctAnswer['ischeck']) && $correctAnswer['ischeck']) {
                            if (isset($params['userResponse'][$key]['ischeck']) && $params['userResponse'][$key]['ischeck']) {
                                $cntCorrect++;
                            } else {
                                $iscorrect = FALSE;
                            }
                        } else {
                            if (isset($params['userResponse'][$key]['ischeck']) && $params['userResponse'][$key]['ischeck']) {
                                $iscorrect = FALSE;
                            }
                        }
                    } else {
                        $iscorrect = FALSE;
                    }
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareFillInTheBlanksAnswer($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if(isset($params['userResponse'][$key]) && isset($params['correctAnswer'][$key]['seq']) && isset($params['userResponse'][$key]['seq']) && isset($params['correctAnswer'][$key]['value']) && isset($params['userResponse'][$key]['value'])){
                    if ($params['correctAnswer'][$key]['seq'] == $params['userResponse'][$key]['seq'] && strtolower(trim($params['correctAnswer'][$key]['value'])) == strtolower(trim($params['userResponse'][$key]['value']))) {
                        $cntCorrect++;
                    } else {
                        $iscorrect = FALSE;
                    }
                }else{
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMultipleInsertLiteracyFeature($params) {

        $iscorrect = TRUE;
        $cntCorrect = 0;
        $cntUserResponse = count($params['userResponse']);
        $cntCorrectAns = count($params['correctAnswer']);
        if ($cntUserResponse) {
            if($cntCorrectAns > $cntUserResponse){
                $cntR = $cntCorrectAns/$cntUserResponse;
                $stratIndex = $cntUserResponse*($cntR-1);
                $params['correctAnswer'] = array_slice($params['correctAnswer'], $stratIndex);
            }

            foreach ($params['userResponse'] as $key => $userResponse) {
                if (isset($params['userResponse'][$key]) && trim($params['correctAnswer'][$key]) == trim($params['userResponse'][$key])) {
                    if(!empty($params['userResponse'][$key])  && !empty($params['correctAnswer'][$key]))
                        $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareNumericTextboxSingle($params) {
        $iscorrect = FALSE;
        $mark = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = FALSE;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($params['correctAnswer'][$key]['value']) && isset($params['userResponse']['value']) && $params['correctAnswer'][$key]['value'] === $params['userResponse']['value']) {
                    $iscorrect = TRUE;
                    $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
                }
            }
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMeasureLineAndAngle($params) {
        $correctAns = round($params['correctAnswer']);
        $userResponse = round($params['userResponse']);
        $iscorrect = FALSE;
        $mark = 0;
        if (!empty($params['userResponse'])) {
            if (($correctAns > $userResponse - 10) && $correctAns < $userResponse + 10) {
                $iscorrect = TRUE;
                $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
            }
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareLabelLiteracyFeature($params) {
        
        $iscorrect = TRUE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            $userAns = 0;
            $correctAns = 0;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if(isset($params['userResponse'][$key]['value']) && empty($params['userResponse'][$key]['value'])) 
                    $params['userResponse'][$key] = '';
                if (isset($params['userResponse'][$key]) && !empty($params['userResponse'][$key])) 
                    $userAns++;
                if (isset($correctAnswer) && !empty($correctAnswer)) 
                    $correctAns++;
                
            }
            if($userAns>$correctAns)
            {
                $iscorrect = FALSE;
                $cntCorrect = 0;
            }
            else {
                    foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                        if(isset($params['userResponse'][$key]['value']) && empty($params['userResponse'][$key]['value'])) 
                               $params['userResponse'][$key] = '';

                        if (isset($params['userResponse'][$key]) && !empty($params['userResponse'][$key]) && $correctAnswer == $params['userResponse'][$key]) {
                            $cntCorrect++;
                        } elseif (empty($correctAnswer) && empty($params['userResponse'][$key])) {
                            //$iscorrect = TRUE;
                        }else{
                            $iscorrect = FALSE;
                        }
                    }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMissingNumberOnImage($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($params['userResponse'][$key]) && isset($params['correctAnswer'][$key]['value']) && isset($params['userResponse'][$key]['value']) && $params['correctAnswer'][$key]['value'] === $params['userResponse'][$key]['value']) {
                    $cntCorrect++;
                }
            }
        }
        $iscorrect = count($params['correctAnswer']) == $cntCorrect ? TRUE : FALSE;
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMissingWordOnImage($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if(isset($params['correctAnswer'][$key]['label']) && isset($params['userResponse'][$key]['label']) && isset($params['correctAnswer'][$key]['value']) && isset($params['userResponse'][$key]['value'])){
                    if ($params['correctAnswer'][$key]['label'] == $params['userResponse'][$key]['label'] && $params['correctAnswer'][$key]['value'] == $params['userResponse'][$key]['value']) {
                        $cntCorrect++;
                    } else {
                        $iscorrect = FALSE;
                    }
                }else{
                    $iscorrect = FALSE;
                }
                
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareUnderlineLiteracyFeature($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            preg_match_all("/\<span class\=\"underline\"\>(.*?)\<\/span\>/", $params['correctAnswer'], $correctAns);
            $correctAnsRaw = isset($correctAns[1]) ? array_map('trim', $correctAns[1]) : array();
            $userResponceRaw = array();
            if(isset($params['userResponse']['value'])){
                preg_match_all("/\<span class\=\"underline\"\>(.*?)\<\/span\>/", $params['userResponse']['value'], $userResponce);
                $userResponceRaw = isset($userResponce[1]) ? array_map('trim', $userResponce[1]) : array();
            }
            foreach ($userResponceRaw as $value) {
                if (in_array($value, $correctAnsRaw)) {
                    $cntCorrect++;
                }
            }
            $iscorrect = count($correctAnsRaw) == $cntCorrect ? TRUE : FALSE;
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareReflection($params) {
        $iscorrect = FALSE;
        $mark = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {

            $userResponceArray = isset($userResponce[1]) ? $userResponce[1] : array();

            $correctAnsArray = isset($correctAns[1]) ? $correctAns[1] : array();
            if (count($params['userResponse']) == count($params['correctAnswer'])) {
                foreach ($correctAnsArray as $key => $val) {
                    if (isset($userResponceArray[$key]) && $correctAnsArray[$key] == $userResponceArray[$key]) {
                        $iscorrect = true;
                    } else {
                        $iscorrect = FALSE;
                    }
                }
            } else {
                $iscorrect = FALSE;
            }
        }
        if ($iscorrect == TRUE)
            $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
        else {
            $iscorrect = FALSE;
            $mark = 0;
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareReflectionBottomTop($params) {
        $iscorrect = FALSE;
        $diffArray = array();
        $mark = 0;
        if (!empty($params['userResponse'])) {
            $userResponce = array();
            foreach ($params['userResponse'] as $key => $val) {
                foreach ($val as $v2 => $k2)
                    $userResponce[] = $k2;
            }
            $userResponce = array_unique($userResponce);
        }
        if (!empty($params['correctAnswer'])) {
            $correctAnswer = array();
            foreach ($params['correctAnswer'] as $key => $val) {
                foreach ($val as $v2 => $k2)
                    $correctAnswer[] = $k2;
            }
            $correctAnswer = array_unique($correctAnswer);
        }

        if (!empty($params['userResponse'])) {
            if (count($correctAnswer) > count($userResponce))
                $diffArray = array_diff($correctAnswer, $userResponce);
            else if (count($userResponce) > count($correctAnswer))
                $diffArray = array_diff($userResponce, $correctAnswer);
        }
        /* open for exact match */

        if (count($params['userResponse'])) {
            if (count($diffArray) == 0) {
                $iscorrect = true;
            }
        }
        /* if (count($params['userResponse']) > 10) {
          if (count($diffArray) <= 5) {
          $iscorrect = true;
          }
          } */
        if ($iscorrect == TRUE)
            $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
        else {
            $iscorrect = FALSE;
            $mark = 0;
        }
        //echo $mark;
       // die;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareJoiningDotsOnDiagram($params) {
        return $this->compareReflectionBottomTop($params);
    }

    public function compareLiteracyFeature($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($params['userResponse'][$key]) && $params['correctAnswer'][$key] === $params['userResponse'][$key]) {
                    $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        if ($iscorrect == TRUE) {
            $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
        } else {
            $iscorrect = FALSE;
            $mark = 0;
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMatchingDragDrop($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($params['userResponse'][$key]) && $params['correctAnswer'][$key] === $params['userResponse'][$key]) {
                    $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareWordSelection($params) {
        $iscorrect = TRUE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($params['userResponse'][$key]) && trim($correctAnswer) == strip_tags(trim($params['userResponse'][$key]))) {
                    $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareShadingShapes($params) {
        $iscorrect = TRUE;
        if (count($params['userResponse'])) {
            if ($params['correctAnswer'] === $params['userResponse']) {
                $iscorrect = TRUE;
            } else {
                $iscorrect = FALSE;
            }
        }
        if ($iscorrect == TRUE) {
            $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
        } else {
            $mark = 0;
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareTableSingleMultipleEntry($params) {
        $iscorrect = TRUE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if ($key != 0) {
                    $rowCntCorrect = 0;
                    foreach ($params['correctAnswer'][$key] as $subkey => $subval) {
                        if(isset($params['userResponse']['option'][$key][$subkey]['checkvalue']) && isset($params['userResponse']['option'][$key][$subkey]['title'])){
                            if ((trim($subval['checkvalue']) == trim($params['userResponse']['option'][$key][$subkey]['checkvalue'])) && (trim($subval['title']) == trim($params['userResponse']['option'][$key][$subkey]['title']))) {
                                $rowCntCorrect++;
                            } else {
                                $iscorrect = FALSE;
                            }
                        }else{
                            $iscorrect = FALSE;
                        }
                    }
                    if ($rowCntCorrect == count($params['correctAnswer'][$key])) {
                        $cntCorrect++;
                    }
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareTableFillBlanks($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if ($key != 0) {
                    $rowCntCorrect = 0;
                    foreach ($params['correctAnswer'][$key] as $subkey => $subval) {
                        if ($subkey != 0) { 
                            //if ((trim($subval['checkvalue']) == trim($params['userResponse']['option'][$key][$subkey]['checkvalue'])) && (trim($subval['title']) == trim($params['userResponse']['option'][$key][$subkey]['title'])) && (trim($subval['value']) == trim($params['userResponse']['option'][$key][$subkey]['value']))) {
                            if ($subval['checkvalue'] != 1 && isset($params['userResponse']['option'][$key][$subkey]['checkvalue']) && isset($params['userResponse']['option'][$key][$subkey]['title'])) {
                                if ((trim($subval['checkvalue']) == trim($params['userResponse']['option'][$key][$subkey]['checkvalue'])) && (trim($subval['title']) == trim($params['userResponse']['option'][$key][$subkey]['title'])) && (trim($subval['value']) == trim($params['userResponse']['option'][$key][$subkey]['value']))) {
                                    $iscorrect = TRUE;
                                    $cntCorrect++;
                                    $rowCntCorrect++;
                                } else {//echo "yyyy";
                                    //$iscorrect = FALSE;
                                }
                            }
                        }
                    }
                    //if ($rowCntCorrect == count($params['correctAnswer'][$key]) ) {
                    //    $cntCorrect++;
                    // }
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    function compareBooleanTypeQuestion($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        $correctAnsCount = 0;
        $responceAnsCount = 0;
        $mark = 0;
        if (isset($params['correctAnswer'][0]['main']) && isset($params['userResponse'][0]['main']) && $params['correctAnswer'][0]['main'] == $params['userResponse'][0]['main']) {
            if (isset($params['correctAnswer'][0]['justifypart']) && !count($params['correctAnswer'][0]['justifypart'])) {
                return array('iscorrect' => TRUE, 'mark' => $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1);
            } else {
                if(isset($params['correctAnswer'][0]['justifypart'])){
                    foreach ($params['correctAnswer'][0]['justifypart'] as $key => $correctAnswer) {
                        if (isset($correctAnswer['ischeck']) && $correctAnswer['ischeck']) {
                            $correctAnsCount++;
                        }
                    }
                }
                if(isset($params['userResponse'][0]['justifypart'])){
                    foreach ($params['userResponse'][0]['justifypart'] as $key => $userResponse) {
                        if (isset($userResponse['ischeck']) && $userResponse['ischeck']) {
                            $responceAnsCount++;
                        }
                    }
                }
                if ($correctAnsCount > 0 && $responceAnsCount > 0 && $correctAnsCount >= $responceAnsCount) {
                    foreach ($params['correctAnswer'][0]['justifypart'] as $key => $correctAnswer) {
                        if (isset($correctAnswer['ischeck']) && $correctAnswer['ischeck']) {
                            if (isset($params['userResponse'][0]['justifypart'][$key]['ischeck']) && $params['userResponse'][0]['justifypart'][$key]['ischeck']) {
                                $cntCorrect++;
                            }
                        }
                    }
                    $iscorrect = $cntCorrect == $correctAnsCount ? TRUE : FALSE;
                }
            }
            $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareDragdropReordering($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($params['userResponse'][$key]) && trim(@html_entity_decode(@$correctAnswer)) == trim(@html_entity_decode(@$params['userResponse'][$key]))) {
                    $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareInputOnImage($params) {
        $iscorrect = TRUE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $val) {
                $ansArray = array();
                foreach ($params['correctAnswer'][$key] as $cKey => $cVal) {
                    $ansArray[] = $cVal['value'];
                }
                $userOptions = array();
                if(isset($params['userResponse'][$key]['value'])){
                    $userOptions = explode(",", trim($params['userResponse'][$key]['value']));
                }
                $cntCorrectTemp = 0;
                foreach ($userOptions as $userOption) {
                    if (in_array($userOption, $ansArray)) {
                        $cntCorrectTemp++;
                    }
                }
                if ($cntCorrectTemp!=0) {
                    $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareInputOnImage_Old($params) {
        $iscorrect = TRUE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            $iscorrect = TRUE;
            foreach ($params['correctAnswer'] as $key => $val) {
                $ansArray = array();
                foreach ($params['correctAnswer'][$key] as $cKey => $cVal) {
                    $ansArray[] = $cVal['value'];
                }
                if (isset($params['userResponse'][$key]['value']) && in_array($params['userResponse'][$key]['value'], $ansArray)) {
                    $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareLineofSymmetryQuestion($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        $correctCheckedCnt = 0;
        $userResponceCheckedCnt = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            foreach ($params['correctAnswer'] as $key => $val) {
                if (isset($params['correctAnswer'][$key]['ischeck']) && $params['correctAnswer'][$key]['ischeck'] == 1)
                    $correctCheckedCnt++;
                if (isset($params['userResponse'][$key]['ischeck']) && $params['userResponse'][$key]['ischeck'] == 1)
                    $userResponceCheckedCnt++;
            }
            if ($correctCheckedCnt > 0 && $userResponceCheckedCnt > 0 && $correctCheckedCnt >= $userResponceCheckedCnt) {
                foreach ($params['correctAnswer'] as $key => $val) {
                    if ($params['correctAnswer'][$key]['ischeck'] == $params['userResponse'][$key]['ischeck'] && $params['correctAnswer'][$key]['ischeck'] == 1) {
                        $iscorrect = TRUE;
                        $cntCorrect++;
                    }
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMeasureLineAndAngleImage($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            foreach ($params['correctAnswer'] as $key => $correctAnswer) {
                if (isset($params['correctAnswer'][$key]['value']) && isset($params['userResponse'][$key]['value']) && $params['correctAnswer'][$key]['value'] === $params['userResponse'][$key]['value']) {
                    $cntCorrect++;
                }
            }
        }
        $iscorrect = count($params['correctAnswer']) == $cntCorrect ? TRUE : FALSE;
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function comparePieChart($params) {
        $iscorrect = FALSE;
        $mark = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            if (isset($params['userResponse'][0]['value']) && isset($params['correctAnswer'][0]['value']) && $params['userResponse'][0]['value'] == $params['correctAnswer'][0]['value']) {
                $iscorrect = TRUE;
                $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
            }
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareMatchingDragDropQuestion($params) {
        $iscorrect = TRUE;
        $cntCorrect = 0;
        if (is_array($params['userResponse']) && count($params['userResponse'])) {
            foreach ($params['userResponse'] as $value) {
                if (in_array($value, $params['correctAnswer'])) {
                    $cntCorrect++;
                } else {
                    $iscorrect = FALSE;
                }
            }
        }
        $mark = $cntCorrect ? $this->calculateStepMark($params['step_mark'], $cntCorrect) : 0;
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function compareDrawLineOnImage($params) {
        $iscorrect = FALSE;
        $cntCorrect = 0;
        $mark = 0;
        $tempCorrectAns = array();
        $tempUserResponse = array();
        if (count($params['correctAnswer']) == count($params['userResponse'])) {
            $cntLines = count($params['correctAnswer']);
            foreach ($params['correctAnswer'] as $key => $row) {
                $set = array_values($row['set']);
                $points = array_unique($row['points']);
                $tempCorrectAns[$key] = array('set1' => $set, 'set2' => $points);
            }
            foreach ($params['userResponse'] as $key => $row) {
                $setU = array_values($row['set']);
                $pointsU = array_unique($row['points']);
                $tempUserResponse[$key] = array('set1' => $setU, 'set2' => $pointsU);
            }
            foreach ($tempUserResponse as $keyR => $rowR) {
                $rangeSetX1 = range($rowR['set1'][0] - 5, $rowR['set1'][0] + 5);
                $rangeSetY1 = range($rowR['set1'][1] - 5, $rowR['set1'][1] + 5);
                $rangeSetX2 = range($rowR['set2'][0] - 5, $rowR['set2'][0] + 5);
                $rangeSetY2 = range($rowR['set2'][1] - 5, $rowR['set2'][1] + 5);
                foreach ($tempCorrectAns as $keyC => $rowC) {
                    if (in_array($rowC['set1'][0], $rangeSetX1) && in_array($rowC['set1'][1], $rangeSetY1) && in_array($rowC['set2'][0], $rangeSetX2) && in_array($rowC['set2'][1], $rangeSetY2)) {
                        $cntCorrect++;
                        break;
                    } elseif (in_array($rowC['set2'][0], $rangeSetX1) && in_array($rowC['set2'][1], $rangeSetY1) && in_array($rowC['set1'][0], $rangeSetX2) && in_array($rowC['set1'][1], $rangeSetY2)) {
                        $cntCorrect++;
                        break;
                    }
                }
            }
            if ($cntLines == $cntCorrect) {
                $iscorrect = TRUE;
                $mark = !empty($params['total_mark']) ? (int) $params['total_mark'] : 1;
            }
        }
        return array('iscorrect' => $iscorrect, 'mark' => $mark);
    }

    public function getAttemptAnswerAggregate($params) {
        $studentQuestionAnswer = $this->getStudentQuestionAnswer($params);
        $attempt_qus_data = array();
        $mark_obtained = 0;
        $num_correct = 0;
        if (count($studentQuestionAnswer)) {
            foreach ($studentQuestionAnswer as $row) {
                $attempt_qus_data[$row['question_id']] = $row;
                $mark_obtained += $row['mark_obtain'];
                $num_correct = $row['iscorrect'] ? $num_correct + 1 : $num_correct;
            }
        }
        return array(
            'attempt_qus_data' => $attempt_qus_data,
            'mark_obtained' => $mark_obtained,
            'num_correct' => $num_correct
        );
    }

    /**
     * This function is used to get the question sequence number having the details link for correct answer page having correct/incorrect status
     * @param type $params
     * @return string question sequence number having the details link for correct answer page
     */
    public function getQuestionAttemptBreakdown($params) {
        if ($params['task_type'] == TEST) {
            $showResult = 0;
            $validateParam['student_test_attempt_id'] = $params['student_test_attempt_id'];
            $validateParam['attempt'] = $params['attempt'];
            $validateParam['paper_id'] = $params['paper_id'];
            $showResult = $this->checkAttemptStartedOrNot($validateParam);
        }

        $attemptAnswerAggregate = $this->getAttemptAnswerAggregate(array(
            'attempt_id' => $params['attempt_id'],
            'task_type' => $params['task_type']
        ));
        $html = '';
        if (count($attemptAnswerAggregate['attempt_qus_data'])) {
            $key = 0;
            foreach ($attemptAnswerAggregate['attempt_qus_data'] as $row) {
                if ($row['iscorrect']) {
                    $cls = '';
                } else {
                    $cls = 'active';
                }
                if ($params['task_type'] == REVISION) {
                    $html .= '<a href="' . route('revision.examinationresult', encryptParam($params['attempt_id'])) . '"><span class="' . $cls . '"  style="cursor:pointer">' . ($key + 1) . '</span></a>';
                } else {
                    //$html .= '<span class="' . $cls . '"  style="cursor:pointer">' . ($key + 1) . '</span>';
                    if ($showResult == 1)
                        $html .= '<a href="' . route('test.examinationresult', encryptParam($params['attempt_id'])) . '"><span class="' . $cls . '"  style="cursor:pointer">' . ($key + 1) . '</span></a>';
                    else
                        $html .= '<span class="' . $cls . '"  style="cursor:pointer">' . ($key + 1) . '</span>';
                }
                $key++;
            }
        }
        return $html;
    }

    /**
     * This is used to get the test percentage
     * @param type $params array()
     * @return type result of the percentage
     */
    public function getStudentTestResultReport($params) { 
        return StudentTestResult::
                select(['test_attempt_avg_marks'])
                ->where(['student_id' => $params['student_id'], 'subject' => $params['subject'], 'assignment_num' => 1])
                ->orderBy('created_at')
                ->get()->toArray();
    }

    public function studentTestResultReportGraphData($params) {
        $studentResultReport = $this->getStudentTestResultReport($params);
        $cntTest = count($studentResultReport);
        $gData = array();
        $firstLast = array('first' => array(), 'last' => array());
        if($cntTest){
            foreach($studentResultReport as $vCtest){
                $gData[] = round($vCtest['test_attempt_avg_marks']);
            }
            if($cntTest >= 2){
                $percentF = $gData[0];
                $firstLast['first'] = array('percent' => $percentF,'cls' => $this->getPercentCls($percentF));
                
                $percentL = $gData[$cntTest-1];
                $firstLast['last'] = array('percent' => $percentL,'cls' => $this->getPercentCls($percentL));
            }else{
                $percentF = $gData[0];
                $firstLast['first'] = array('percent' => $percentF,'cls' => $this->getPercentCls($percentF));
                
                $percentL = $percentF;
                $firstLast['last'] = array('percent' => $percentL,'cls' => $this->getPercentCls($percentL));
            }
        }
        return array('gData' => $gData, 'firstLast' => $firstLast);
    }

    public function getPercentCls($percent) {
        $percent = round($percent);
        $cls = '';
        if ($percent <= 20) {
            $cls = 'sRed';
        } elseif ($percent > 20 && $percent <= 50) {
            $cls = 'sOrange';
        } elseif ($percent > 50 && $percent <= 69) {
            $cls = 'sGreen';
        } elseif ($percent >= 70) {
            $cls = 'sBlue';
        }
        return $cls;
    }

    public function getStudentRevisionPercentQuery($params) {
        return $this->studentrevision
                        ->select([DB::raw('ROUND((mark_obtain/total_marks)*100,1) AS mark_obtain_fraction')])
                        ->where([
                            'student_id' => $params['student_id'], 'status' => COMPLETED
        ]);
    }

    public function getStudentRevisionPercentage($params) {
        $query = $this->getStudentRevisionPercentQuery($params);
        $query->orderBy('created_at', 'ASC');
        return $query->get()->toArray();
    }

    public function getStudentFirstLastRevisionAttempt($params) {
        $query = $this->getStudentRevisionPercentQuery($params);
        $queryFirst = $query;
        $queryFirst->orderBy('created_at', 'ASC');
        $first = $queryFirst->get()->first()->toArray();
        $return['first'] = round($first['mark_obtain_fraction'], 1);
        $queryLast = $query;
        $queryLast->orderBy('created_at', 'ASC');
        $last = $queryLast->get()->last()->toArray();
        $return['last'] = round($last['mark_obtain_fraction'], 1);
        return $return;
    }

    public function studentCompletedTestList($params) {
        $studentCompletedTest = $this->getStudentCompletedTest($params);
        $list = array();
        if (count($studentCompletedTest)) {
            foreach ($studentCompletedTest as $row) {
                $list = $this->getTestAttemptList($list, array(
                    'id' => $row['id'],
                    'attempt_completed' => $row['attempt_completed'],
                    'set_name' => $row['set_name'],
                    'last_assessment_date' => $row['last_assessment_date'],
                    'completed_at_attempt_1' => $row['completed_at_attempt_1'],
                    'completed_at_attempt_2' => $row['completed_at_attempt_2'],
                    'completed_at_attempt_3' => $row['completed_at_attempt_3'],
                ));
            }
        }
        return $list;
    }
    
    public function getTestAttemptList($list, $params) {
        for ($i = 1; $i <= $params['attempt_completed']; $i++) {
            $completed_at = 'completed_at_attempt_'.$i;
            $list[$params['id'] . '-' . $i] = $params['set_name'] . ' Attempt ' . $i . ' At: ' . outputDateFormat($params[$completed_at]);
        }
        return $list;
    }

    public function getStudentCompletedTest($params) {
        $query = $this->studenttestattempt
                ->select(['student_test_attempt.id', 'task.id AS task_id', 'task.subject', 'task.subject AS tasksubject',
                    'student_test_attempt.attempt_completed', 'student_test_attempt.last_assessment_date','student_test_attempt.completed_at_attempt_1',
                    'student_test_attempt.completed_at_attempt_2','student_test_attempt.completed_at_attempt_3',
                    'questionsets.set_name'
                ])
                ->join('task', 'student_test_attempt.task_id', '=', 'task.id')
                ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                ->where([
                    'task.status' => ACTIVE,
                    'task.subject' => $params['subject'],
                    'questionsets.status' => 'Published',
                    'student_test_attempt.student_id' => $params['student_id'],
                    'student_test_attempt.status' => COMPLETED,
                ])
                ->orderBy('student_test_attempt.completed_at_attempt_1', 'DESC');
        return $query->get()->toArray();
    }

    public function getStudentCompletedTopics($params) {
        $query = $this->studentrevision
                ->select([
                    'studentrevision.id', 'studentrevision.task_id', 'studentrevision.total_marks',
                    'studentrevision.mark_obtain', 'studentrevision.quesmaxtime', 'studentrevision.remainingtime'
                    ,DB::raw('TIMESTAMPDIFF(SECOND, studentrevision.attempt_at, studentrevision.completed_at) AS attempt_complete_time')
                ])
                ->join('task', 'studentrevision.task_id', '=', 'task.id')
                ->where([
            'task.strand' => $params['strand_id'],
            'task.substrand' => $params['substrand_id'],
            'studentrevision.student_id' => $params['student_id'],
            'studentrevision.status' => COMPLETED
        ]);
        return $query->get()->toArray();
    }

    public function studentStrandMeta($params) {
        //get questions for attempt papaer/revision from (questions table)
        $questionIds = !empty($params['questionids']) ? explode(",", $params['questionids']) : array();
        $questions = $this->getExaminationQuestions(array(
            'questionids' => $questionIds
        ));

        //get attempt question answer for attempt papaer/revision from ("questionsanswer" table)
        $attemptAnswerAggregate = $this->getAttemptAnswerAggregate(array(
            'attempt_id' => $params['attempt_id'],
            'task_type' => $params['task_type']
        ));

        //get strand wise total mark and total mark obtained for this attempt papaer/revision
        if (count($questions)) {
            foreach ($questions as $row) {
                $strands_id = $row['strands_id'];
                $substrands_id = $row['substrands_id'];
                $question_id = $row['id'];
                $total_mark = $row['total_marks'];

                $strand[$strands_id]['strand'][0] = isset($strand[$strands_id]['strand'][0]) ? $strand[$strands_id]['strand'][0] + $total_mark : $total_mark;
                $strand[$strands_id]['substrand'][$substrands_id][0] = isset($strand[$strands_id]['substrand'][$substrands_id][0]) ? $strand[$strands_id]['substrand'][$substrands_id][0] + $total_mark : $total_mark;
                $mark_obtain = 0;
                if (isset($attemptAnswerAggregate['attempt_qus_data'][$question_id])) {
                    $mark_obtain = $attemptAnswerAggregate['attempt_qus_data'][$question_id]['mark_obtain'];
                }
                $strand[$strands_id]['substrand'][$substrands_id][1] = isset($strand[$strands_id]['substrand'][$substrands_id][1]) ? $strand[$strands_id]['substrand'][$substrands_id][1] + $mark_obtain : $mark_obtain;
                $strand[$strands_id]['strand'][1] = isset($strand[$strands_id]['strand'][1]) ? $strand[$strands_id]['strand'][1] + $mark_obtain : $mark_obtain;
            }

            //store (add new record or update old recoed ) strand into "student_strand_meta" table.
            foreach ($strand as $strand_id => $value) {
                $model = Studentstrandmeta::firstOrNew([
                            'student_id' => $params['student_id'],
                            'strand_id' => $strand_id,
                            'task_type' => $params['task_type']
                ]);
                if ($model->getOriginal()) {
                    $originalData = $model->getOriginal();
                    $strand_total_mark = $originalData['strand_total_mark'];
                    $strand_mark_obtain = $originalData['strand_mark_obtain'];
                    $substrand_aggrigate = unserialize($originalData['substrand_aggrigate']);
                    foreach ($value['substrand'] as $nwSubkey => $nwSubValue) {
                        if (isset($substrand_aggrigate[$nwSubkey])) {
                            $oldSubValue = $substrand_aggrigate[$nwSubkey];
                            $nwSubValue = array($nwSubValue[0] + $oldSubValue[0], $nwSubValue[1] + $oldSubValue[1]);
                            unset($oldSubValue);
                        }
                        $nw_substrand_aggrigate[$nwSubkey] = $nwSubValue;
                    }
                    $final_substrand_aggrigate = $nw_substrand_aggrigate + $substrand_aggrigate;
                    $model->strand_total_mark = $originalData['strand_total_mark'] + $value['strand'][0];
                    $model->strand_mark_obtain = $originalData['strand_mark_obtain'] + $value['strand'][1];
                    $model->substrand_aggrigate = serialize($final_substrand_aggrigate);
                } else {
                    $model->strand_total_mark = $value['strand'][0];
                    $model->strand_mark_obtain = $value['strand'][1];
                    $model->substrand_aggrigate = serialize($value['substrand']);
                }
                $model->save();
            }
        }
    }

    public function studentRevisionResultData($params) {
        $model = StudentRevisionResult::firstOrNew([
                    'student_id' => $params['student_id'],
                    'strand_id' => $params['strand_id'],
                    'substrand_id' => $params['substrand_id'],
                    'class_id' => $params['class_id']
        ]);
        $model->student_name = $params['student_name'];
        $model->subject = $params['subject'];
        $model->school_id = $params['school_id'];
        $model->school_name = $params['school_name'];
        $model->teacher_id = $params['teacher_id'];
        $model->teacher_name = $params['teacher_name'];
        $model->tutor_id = $params['tutor_id'];
        $model->tutor_name = $params['tutor_name'];
        $model->class_name = $params['class_name'];
        $model->strand_name = $params['strand_name'];
        $model->substrand_name = $params['substrand_name'];
        if ($model->getOriginal()) {
            $completedRevisions = $this->getStudentCompletedTopics(array(
                'strand_id' => $params['strand_id'],
                'substrand_id' => $params['substrand_id'],
                'student_id' => $params['student_id'],
            ));
            $cntAttempt = count($completedRevisions);
            foreach ($completedRevisions as $cRevision) {
                $mark_obtain_fraction[] = $cRevision['mark_obtain'] / $cRevision['total_marks'];
                $avg_total_time[] = $cRevision['quesmaxtime'];
                $avg_time[] = calculateTaskCompleteTime(array(
                    'quesmaxtime' => $cRevision['quesmaxtime'],
                    'remainingtime' => $cRevision['remainingtime'],
                    'completetime' => $cRevision['attempt_complete_time']
                ));
            }
            $avg_marks      = round(((array_sum($mark_obtain_fraction) / $cntAttempt)*100),2);
            $avg_total_time = round((array_sum($avg_total_time)/$cntAttempt),2);
            $avg_time       = round((array_sum($avg_time)/$cntAttempt),2);
        } else {
            $avg_marks = $params['percentage_obtained'];
            $avg_total_time = $params['total_time'];
            $avg_time = $params['time_take'];
        }
        $model->avg_marks = $avg_marks;
        $model->avg_total_time = $avg_total_time;
        $model->avg_time = $avg_time;
        $model->save();
    }

    public function updateQuestionIds() {
        $query = $this->studenttest
                ->select(['id', 'questionids']);
        $result = $query->get()->toArray();
        foreach ($result as $row) {
            $model = $this->studenttest->findOrFail($row['id']);
            if (!empty($model->questionids)) {
                $model->questionids = implode(",", unserialize($model->questionids));
                $model->save();
            }
        }
        asd($result);
    }

    /**
     * This is used to get the topicwise aggerate result 
     * @param type $params array()
     * @return type array() result of aggerate
     */
    public function getRevisionTopicwiseAggegrateResult($params) {
        $query = $this->studentrevision
                ->select([
                    'task.strand', 'task.substrand', DB::raw('COUNT(studentrevision.id) topicAttempt'),
                    DB::raw('SUM(total_marks) markAttempt'), DB::raw('SUM(mark_obtain) markObtain'),
                    DB::raw('SUM(mark_obtain)/SUM(total_marks) markObtainFra1'), DB::raw('SUM(num_question) questionAttempt'),
                    'temp.markobtain_on_each_attempt', 'temp.markattempt_on_each_attempt','temp.topichighestmark AS markObtainFra'
                ])
                ->join('task', 'studentrevision.task_id', '=', 'task.id')
                ->join(DB::raw("(SELECT task.substrand, MAX(mark_obtain/total_marks) topichighestmark,SUBSTRING_INDEX(GROUP_CONCAT(mark_obtain ORDER BY studentrevision.id DESC SEPARATOR ','), ',', 3) markobtain_on_each_attempt
                    ,SUBSTRING_INDEX(GROUP_CONCAT(total_marks ORDER BY studentrevision.id DESC SEPARATOR ','), ',', 3) markattempt_on_each_attempt    
                    FROM studentrevision 
                    JOIN task ON studentrevision.task_id = task.id WHERE task.subject = '" . $params['subject'] . "' AND studentrevision.student_id = " . $params['student_id'] . " AND studentrevision.status = '" . COMPLETED . "'
                    GROUP BY task.substrand) AS temp"), function($join) {
                    $join->on('task.substrand', '=', 'temp.substrand');
                })
                ->where(['task.subject' => $params['subject'], 'studentrevision.student_id' => $params['student_id'], 'studentrevision.status' => COMPLETED]);
                $query->groupBy('task.substrand');
                return $query->get()->toArray();
        
        /*$query = $this->studentrevision
                ->select([
                    'task.strand', 'task.substrand', DB::raw('COUNT(studentrevision.id) topicAttempt'),
                    DB::raw('SUM(total_marks) markAttempt'), DB::raw('SUM(mark_obtain) markObtain'),
                    DB::raw('SUM(mark_obtain)/SUM(total_marks) markObtainFra'), DB::raw('SUM(num_question) questionAttempt'),
                    'temp.markobtain_on_each_attempt', 'temp.markattempt_on_each_attempt'
                ])
                ->join('task', 'studentrevision.task_id', '=', 'task.id')
                ->join(DB::raw("(SELECT task.substrand, MAX(mark_obtain/total_marks) topichighestmark,SUBSTRING_INDEX(GROUP_CONCAT(mark_obtain ORDER BY studentrevision.id DESC SEPARATOR ','), ',', 3) markobtain_on_each_attempt
		,SUBSTRING_INDEX(GROUP_CONCAT(total_marks ORDER BY studentrevision.id DESC SEPARATOR ','), ',', 3) markattempt_on_each_attempt    
		FROM studentrevision 
		JOIN task ON studentrevision.task_id = task.id WHERE task.subject = '" . $params['subject'] . "' AND studentrevision.student_id = " . $params['student_id'] . " AND studentrevision.status = '" . COMPLETED . "'
		GROUP BY task.substrand) AS temp"), function($join) {
                    $join->on('task.substrand', '=', 'temp.substrand');
                })
                ->where(['task.subject' => $params['subject'], 'studentrevision.student_id' => $params['student_id'], 'studentrevision.status' => COMPLETED]);
        $query->groupBy('task.substrand');
        return $query->get()->toArray();*/
    }

    /**
     * This is used to stored the result values for starnd in meta table
     * @param type $params array()
     * @return type strands arry with marks received
     */
    public function studentStrandMetaForMetaDetails($params) {
        //get questions for attempt papaer/revision from (questions table)
        $questionIds = !empty($params['questionids']) ? explode(",", $params['questionids']) : array();
        $questions = $this->getExaminationQuestionsForMetaDetails(array(
            'questionids' => $questionIds
        ));
        //get attempt question answer for attempt papaer/revision from ("questionsanswer" table)
        $attemptAnswerAggregate = $this->getAttemptAnswerAggregate(array(
            'attempt_id' => $params['attempt_id'],
            'task_type' => $params['task_type']
        ));
        //get strand wise total mark and total mark obtained for this attempt papaer/revision
        $strand = array();
        if (count($questions)) {
           // asd($questions);
            foreach ($questions as $row) {
                $strands_id = $row['strands_id'];
                $substrands_id = $row['substrands_id'];
                $question_id = $row['id'];
                $total_mark = $row['total_marks'];

                $strand[$substrands_id]['total_marks'] = isset($strand[$strands_id]['total_marks']) ? $strand[$strands_id]['total_marks'] + $total_mark : $total_mark;
                $strand[$substrands_id]['strand'] = $row['strand'];
                $strand[$substrands_id]['substrand'] = $row['substrand'];
                $strand[$substrands_id]['strands_id'] = $strands_id;
                $strand[$substrands_id]['substrands_id'] = $substrands_id;
                // $strand[$strands_id]['substrand'][$substrands_id][0] = isset($strand[$strands_id]['substrand'][$substrands_id][0]) ? $strand[$strands_id]['substrand'][$substrands_id][0] + $total_mark : $total_mark;
                $mark_obtain = 0;
                if (isset($attemptAnswerAggregate['attempt_qus_data'][$question_id])) {
                    $mark_obtain = $attemptAnswerAggregate['attempt_qus_data'][$question_id]['mark_obtain'];
                }
                //$strand[$strands_id]['substrand'][$substrands_id][1] = isset($strand[$strands_id]['substrand'][$substrands_id][1]) ? $strand[$strands_id]['substrand'][$substrands_id][1] + $mark_obtain : $mark_obtain;
                $strand[$substrands_id]['marks_received'] = isset($strand[$substrands_id]['marks_received']) ? $strand[$substrands_id]['marks_received'] + $mark_obtain : $mark_obtain;
            }
        }
        //asd($strand);
        return $strand;
    }

    /**
     * This is used to get aggregate of the paper attempt completed
     * @param type $params
     * @return type aggregate result of the paper
     */
    public function makeTestPaperAttemptCompletedForMetaDetails($params) {
        //store strand mark record for this completed paper
        $attemptAnswerAggregate = $this->studentStrandMetaForMetaDetails(array(
            'attempt_id' => $params['attempt_id'],
            'questionids' => $params['questionids'],
            'task_type' => TEST,
            'student_id' => $params['student_id']
        ));
        return $attemptAnswerAggregate;
    }

    /**
     * get question by ids
     * @param type $ids
     */
    public function getExaminationQuestionsForMetaDetails($params) {

        $query = $this->question
                ->select(['questions.id', 'questions.total_marks', 'questions.strands_id', 'questions.substrands_id', 'strands.strand','substrands.strand as substrand'])
                ->join('strands AS strands', 'strands.id', '=', 'questions.strands_id')
                ->join('strands AS substrands', 'substrands.id', '=', 'questions.substrands_id');
        if (isset($params['questionids'])) {
            $query->whereIn('questions.id', $params['questionids']);
        }
        $query->where('questions.status', 'Published');
        $query->whereIn('questions.question_type_id', $this->allowedQuestionType);
        return $query->get()->toArray();
    }

    /**
     * this is used to save the self rating of the student
     * @param type $params array type
     * return true/false status
     */
    public function updateStudentRating($params = array()) {
        $query = $this->studentrating->select(['id', 'strand_id', 'substrand_id', 'rating']);
        if (isset($params['student_id'])) {
            $query->where('student_id', $params['student_id']);
        }
        if (isset($params['strand_id'])) {
            $query->where('strand_id', $params['strand_id']);
        }
        if (isset($params['substrand_id'])) {
            $query->where('substrand_id', $params['substrand_id']);
        }
        $result = $query->get()->toArray();
        if (!empty($result)) {
            $studentrating = $this->studentrating->where('id', '=', $result[0]['id'])->first();
            $studentrating->updated_by = $params['student_id'];
            $this->save($studentrating, $params);
        } else {
            $studentrating = new $this->studentrating;
            $this->save($studentrating, $params);
        }
    }

    public function save($studentrating, $params) {
        $studentrating->student_id = $params['student_id'];
        $studentrating->strand_id = $params['strand_id'];
        $studentrating->substrand_id = $params['substrand_id'];
        $studentrating->rating = $params['rating'];
        $studentrating->created_by = $params['student_id'];
        $studentrating->save();
    }

    public function getStudentRating($params) {
        $query = $this->studentrating->select(['strand_id', 'substrand_id', 'rating']);
        $query->where('student_id', $params['student_id']);
        return $query->get()->toArray();
    }
    
    public function getAllCompletedTest() {
        $query = $this->studenttestattempt
                ->join('users','student_test_attempt.student_id' ,'=' ,'users.id')
                ->select(['student_test_attempt.id','student_test_attempt.task_id', 'student_test_attempt.attempt_completed',
                    'student_test_attempt.attempt AS assignment_num','student_test_attempt.attempt_at AS test_attempt_at',
                    'student_test_attempt.completed_at_attempt_1 AS completed_at_attempt_1','student_test_attempt.last_assessment_date',
                    'users.id AS student_id','users.first_name','users.last_name','users.school_id','users.teacher_id','users.tutor_id'
                ])
                ->where([
                    'student_test_attempt.status' => COMPLETED,
                ])
                ->orderBy('student_test_attempt.id','DESC');
        return $query->get()->toArray();
    }
    
    public function getAllCompletedRevision() {
        $query = $this->studentrevision
                ->join('users','studentrevision.student_id' ,'=' ,'users.id')
                ->select(['studentrevision.id','studentrevision.task_id', 'studentrevision.mark_obtain', 'studentrevision.total_marks',
                    'studentrevision.quesmaxtime', 'studentrevision.remainingtime', 'studentrevision.attempt'
                    ,DB::raw('TIMESTAMPDIFF(SECOND, studentrevision.attempt_at, studentrevision.completed_at) AS attempt_complete_time'),
                    'users.id AS student_id','users.first_name','users.last_name','users.school_id','users.teacher_id','users.tutor_id'
                ])
                ->where([
                    'studentrevision.status' => COMPLETED,
                ])
                ->orderBy('studentrevision.id','DESC');
        return $query->get()->toArray();
    }
    
    public function studentTestStrandReoprt($params){ 
        $testAttemptCompletedPapers = $this->getTestAttemptCompletedPapers(array(
            'test_attempt_id' => $params['test_attempt_id'],
            'attempt' => $params['attempt_num']
        ));
        $cntAttemptedPapers = count($testAttemptCompletedPapers);
        if($cntAttemptedPapers && $params['cnt_papers'] == $cntAttemptedPapers){
            $paper_id = 1;
            foreach($testAttemptCompletedPapers as $cPaper){
                //get questions for attempt papaer/revision from (questions table)
                $questionIds = explode(",", $cPaper['questionids']);
                $questions = $this->getExaminationQuestions(array(
                    'questionids' => $questionIds
                ));

                //get attempt question answer for attempt papaer/revision from ("questionsanswer" table)
                $attemptAnswerAggregate = $this->getAttemptAnswerAggregate(array(
                    'attempt_id' => $cPaper['id'],
                    'task_type' => TEST
                ));
                //get strand wise total mark and total mark obtained for this attempt papaer/revision
                if (count($questions)) {
                    $substrandAgg = array();
                    foreach ($questions as $row) {
                        $substrands_id = $row['substrands_id'];
                        $question_id = $row['id'];
                        $total_marks = $row['total_marks'];
                        $mark_obtain = isset($attemptAnswerAggregate['attempt_qus_data'][$question_id]) ? $attemptAnswerAggregate['attempt_qus_data'][$question_id]['mark_obtain'] : 0;
                        if(isset($substrandAgg[$substrands_id])){
                            array_push($substrandAgg[$substrands_id]['total_marks'],$total_marks);
                            array_push($substrandAgg[$substrands_id]['mark_obtain'],$mark_obtain);
                        }else{
                            $substrandAgg[$substrands_id]['total_marks'][] = $total_marks;
                            $substrandAgg[$substrands_id]['mark_obtain'][] = $mark_obtain;
                            $substrandAgg[$substrands_id]['strand_id'] = $row['strands_id'];
                        }
                    }
                    foreach($substrandAgg as $subSId => $subSValue){
                        $subS_total_marks = array_sum($subSValue['total_marks']);
                        $subS_mark_obtain = array_sum($subSValue['mark_obtain']);
                        $attempt_per = round((($subS_mark_obtain/$subS_total_marks)*100),2);
                        
                        $model = StudentTestStrandResult::firstOrNew([
                                    'student_id' => $params['student_id'],
                                    'set_id' => $params['set_id'],
                                    'strand_id' => $subSValue['strand_id'],
                                    'substrand_id' => $subSId,
                                    'paper_id' => $paper_id
                        ]);
                        if ($model->getOriginal()) {//attemp2 or attempt 3
                            $originalData = $model->getOriginal();
                            $attempt1_marks = $originalData['attempt1_marks'];
                            $attempt1_per = $originalData['attempt1_per'];
                            if($params['attempt_num'] == 2){
                                $model->attempt2_marks = $subS_mark_obtain;
                                $model->attempt2_per = $attempt_per;
                                $model->attempt_marks_avg = ($subS_mark_obtain+$attempt1_marks)/2;
                                $model->attempt_per_avg = ($attempt_per+$attempt1_per)/2;
                            }
                            if($params['attempt_num'] == 3){
                                $attempt2_marks = $originalData['attempt2_marks'];
                                $attempt2_per = $originalData['attempt2_per'];
                                $model->attempt3_marks = $subS_mark_obtain;
                                $model->attempt3_per = $attempt_per;
                                $model->attempt_marks_avg = ($subS_mark_obtain+$attempt1_marks+$attempt2_marks)/3;
                                $model->attempt_per_avg = ($attempt_per+$attempt1_per+$attempt2_per)/3;
                            }
                        }else{//attempt 1
                            
                            $model->subject = $params['subject'];
                            $model->total_marks = $subS_total_marks;
                            $model->attempt1_marks = $subS_mark_obtain;
                            $model->attempt1_per = $attempt_per;
                            $model->attempt_marks_avg = $subS_mark_obtain;
                            $model->attempt_per_avg = $attempt_per;
                        }
                        $model->save();
                    }
                }
                $paper_id++;
            }
        }
    }
    public function updateTestAttemptCompletedate($params){
        $result = $this->studenttest->select(['student_test_attempt.id','studenttest.attempt','studenttest.completed_at'])
                ->join('student_test_attempt','student_test_attempt.id','=','studenttest.test_attempt_id')
                ->where(['student_test_attempt.status' => COMPLETED,'studenttest.status' => COMPLETED,'studenttest.attempt' => $params['attempt'],'studenttest.paper_id' => $params['paper_id']])
                ->groupBy(['attempt','test_attempt_id'])
                ->get()->toArray();
        if(count($result)){
            $compltedAt = 'completed_at_attempt_'.$params['attempt'];
            foreach($result as $row){
                $model = $this->studenttestattempt->findOrFail($row['id']);
                $model->$compltedAt = $row['completed_at'];
                $model->save();
            }
        }
        asd($result);
                
    }
    public function getAllStudentBaseLine($params){
        $select = "SELECT student_id,att1_avg_marks FROM student_test_result_data AS tem JOIN (SELECT MIN(created_at) AS min_c FROM student_test_result_data WHERE SUBJECT = '".$params['subject']."' GROUP BY student_id) AS temp1 ON tem.created_at = temp1.min_c";
        return DB::select($select);
    }
     public function updateAllStudentBaseLine($params) {
        if(!empty($params['student_id'])) {
            if($params['subject'] == MATH) {
               DB::update("update users set ks2_maths_baseline_value='".$params['percentageObtained']."',ks2_maths_baseline='2' where id='" . $params['student_id']."' and (ks2_maths_baseline_value='' or ks2_maths_baseline_value='0' or ks2_maths_baseline_value='0.00' or ks2_maths_baseline_value is NULL )");
            }
            else if($params['subject'] == ENGLISH) {
                DB::update("update users set ks2_english_baseline_value='".$params['percentageObtained']."',ks2_english_baseline='2' where id='" . $params['student_id']."' and (ks2_english_baseline_value='' or ks2_english_baseline_value='0' or ks2_english_baseline_value='0.00' or ks2_english_baseline_value is NULL )");
            }
        }
    }
}
