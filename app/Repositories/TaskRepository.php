<?php

/**
 * This is used for task save/edit.
 * @package    task
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;

use App\Models\Task;
use App\Models\Question;
use App\Models\Questionset;
use App\Models\Taskassignment;
use App\Models\Taskstudents;
use App\Models\User;
use App\Repositories\EmailRepository;
use DB;

/**
 * This is used for task save/edit.
 * @package    task
 * @author     Icreon Tech  - dev2.
 */
class TaskRepository extends BaseRepository {
    /**
     * Create a new TaskRepository instance.
     *
     * @param  App\Models\Task $task
     * @return void
     */

    /**
     * The Task instance.
     *
     * @var App\Models\Task
     */
    protected $model;
    protected $emailRepo;
    protected $question;
    protected $taskassignment;

    public function __construct(Task $task, Question $question, Taskassignment $taskassignment, EmailRepository $emailRepo) {
        $this->model = $task;
        $this->question = $question;
        $this->taskassignment = $taskassignment;
        $this->emailRepo = $emailRepo;
    }

    /**
     * create new test/revision.
     * @author     Icreon Tech - dev2.
     * @param  array  $inputs
     */
    public function assignTest($inputs, $model) {
        if ($model->assign_id) {
            $modelTaskassignment = $this->taskassignment->findOrFail($model->assign_id);
            DB::table('taskstudents')->where('assign_id', '=', $modelTaskassignment->id)->delete();
        } else {
            $modelTaskassignment = new $this->taskassignment;
        }
        $studentSourceFromInput = $this->getStudentSourceFromInput($inputs['students']);
        $studentIdsWithSource = $studentSourceFromInput['studentIdsWithSource'];
        $inputs['numStudents'] = $studentSourceFromInput['numStudents'];
        $inputs['students'] = $studentIdsWithSource;
        $studentIds = array_unique($studentSourceFromInput['studentIds']);
        $this->saveAssignement($model->id, $inputs, $modelTaskassignment);
        
        //send email notification
        $this->sendStudentAssignEmailNotif(array(
            'studentIds' => $studentIds,
            'task_type' => TEST,
            'task_id' => $model->id,
            'teacher_name' => $inputs['teacher_name'],
        ));  
        //end
    }

    /**
     * create new test/revision.
     * @author     Icreon Tech - dev2.
     * @param  array  $inputs
     */
    public function storeRevision($inputs) {
        $studentSourceFromInput = $this->getStudentSourceFromInput($inputs['students']);
        $inputs['numStudents'] = $studentSourceFromInput['numStudents'];
        $inputs['students'] = $studentSourceFromInput['studentIdsWithSource'];
        $studentIds = array_unique($studentSourceFromInput['studentIds']);
        foreach ($inputs['substrand'] as $substrand) {
            $taskId = Task::addTask([
                        'task_type' => $inputs['task_type'],
                        'subject' => $inputs['subject'],
                        'key_stage' => $inputs['key_stage'],
                        'year_group' => $inputs['year_group'],
                        'strand' => $inputs['strand'],
                        'substrand' => $substrand,
            ]);
            if ($taskId) {
                $taskassignment = new $this->taskassignment;
                $this->saveAssignement($taskId, $inputs, $taskassignment);
                
                //send email notification
                $this->sendStudentAssignEmailNotif(array(
                    'studentIds' => $studentIds,
                    'task_type' => REVISION,
                    'task_id' => $taskId,
                    'teacher_name' => $inputs['teacher_name'],
                ));
                //end
            }
        }
    }

    public function getStudentSourceFromInput($students) {
        $numStudents = count($students);
        $studentIdsWithSource = array();
        $studentIds = array();
        if ($numStudents) {
            foreach ($students as $value) {
                list($cgid, $studentid) = explode("-", $value);
                $studentIdsWithSource[$cgid][] = $studentid;
                $studentIds[] = $studentid;

                //$studentIdsWithSource[$value][] = $value;
                //$studentIds[] = $value;
            }
        }
        return array('numStudents' => $numStudents, 'studentIdsWithSource' => $studentIdsWithSource, 'studentIds' => $studentIds);
    }

    public function saveAssignement($taskId, $inputs, $taskassignment) {
        $taskassignment->task_id = $taskId;
        $taskassignment->created_by = $inputs['created_by'];
        $taskassignment->updated_by = $inputs['created_by'];
        $taskassignment->student_source = $inputs['selection_type'];
        $taskassignment->assign_date = inputDateFormat($inputs['assign_date']);
        $taskassignment->completion_date = inputDateFormat($inputs['completion_date']);
        $taskassignment->difficulty = !empty($inputs['difficulty']) ? implode(",", $inputs['difficulty']) : '';
        $taskassignment->save();

        if ($taskassignment->id && $inputs['numStudents']) {
            $assignedStudentData = $this->saveAssignedStudent($taskId, $taskassignment->id, $inputs['students']);
            if ($assignedStudentData['numStudents']) {
                $taskassignment->student_num = $assignedStudentData['numStudents'];
                $taskassignment->student_source_ids = implode(",", $assignedStudentData['student_source_ids']);
                $taskassignment->save();
            }
        }
    }

    public function saveAssignedStudent($taskId, $assignId, $students) {
        $numStudents = 0;
        $student_source_ids = array();
        foreach ($students as $student_source_id => $student_ids) {
            foreach ($student_ids as $student_id) {
                $saveStatus = Taskstudents::saveStudent($student_source_id, [
                            'assign_id' => $assignId,
                            'task_id' => $taskId,
                            'student_id' => $student_id
                ]);
                if ($saveStatus) {

                    $numStudents++;
                    array_push($student_source_ids, $student_source_id);
                }
            }
        }
        return array('numStudents' => $numStudents, 'student_source_ids' => array_unique($student_source_ids));
    }

    /**
     * Get record for test list
     * @author     Icreon Tech - dev2.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function selectedSelectionList($masterData, $selected, $assign_id) {
        $html = '';
        if (count($masterData) && !empty($selected)) {
            $selectedArr = explode(",", $selected);
            foreach ($masterData as $key => $value) {
                if (in_array($key, $selectedArr)) {
                    $html .= '<a class="view_row" data-remote="' . route('managetask.assignedstudent', [$assign_id, $key]) . '" href="javascript:void();">' . $value . '</a>&nbsp;&nbsp;';
                }
            }
        }
        return $html;
    }

    /**
     * get test list grid query.
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getTestList($params) {
        $query = $this->model
                ->select([
                    'task.id', 'task.key_stage', 'task.year_group', 'task.subject',
                    'taskassignments.id AS assign_id', 'taskassignments.assign_date', 'taskassignments.completion_date', 'taskassignments.created_at',
                    'taskassignments.updated_at', 'taskassignments.student_source', 'taskassignments.student_source_ids',
                    'taskassignments.student_num', 'taskassignments.student_attempt_completed_num', DB::raw('DATEDIFF(assign_date,NOW()) AS start_date_remain'), 'questionsets.set_name', 'questionsets.is_print','questionsets.subject'
                ])
                ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                ->leftJoin('taskassignments', function($join) use ($params) {
                    $join->on('task.id', '=', 'taskassignments.task_id');
                    $join->on('taskassignments.created_by', '=', DB::raw($params['created_by']));
                })
                ->where('task.status', '!=', DELETED)
                ->where('questionsets.status', '!=', DELETED);
        if (!empty($params['task_id'])) {
            $query->where('task.id', '=', $params['task_id']);
        }
        return $query;
    }

    /**
     * get a test .
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getTest($id) {
        return $this->model
                        ->select([
                            'task.id', 'task.key_stage',
                            'task.year_group', 'task.subject', 'task.question_set',
                            'questionsets.set_name'
                        ])
                        ->join('questionsets', 'task.question_set', '=', 'questionsets.id')
                        ->where('task.id', '=', $id)
                        ->get()->first();
    }

    /**
     * get revision list grid query.
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getRevisionList($params) {
        return $this->model
                        ->select([
                            'task.id', 'task.key_stage', 'task.year_group', 'task.subject', 'task.status', 'taskassignments.id AS assign_id',
                            'taskassignments.assign_date', 'taskassignments.completion_date', 'taskassignments.created_at',
                            'taskassignments.updated_at', 'taskassignments.student_source', 'taskassignments.student_source_ids', 'taskassignments.student_num',
                            'taskassignments.student_attempt_completed_num', 'strands.strand AS strand', 'substrands.strand AS substrand'
                        ])
                        ->join('taskassignments', 'taskassignments.task_id', '=', 'task.id')
                        ->join('strands AS strands', 'task.strand', '=', 'strands.id')
                        ->join('strands AS substrands', 'task.substrand', '=', 'substrands.id')
                        ->where('taskassignments.created_by', '=', $params['created_by'])
                        ->where('task.status', '!=', DELETED)
                        ->where('task.task_type', '=', $params['task_type']);
    }

    /**
     * get a test .
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getRevision($id) {
        return $this->model
                        ->select([
                            'task.id', 'task.key_stage', 'task.year_group', 'task.subject',
                            'task.strand AS strand_id', 'task.substrand AS substrand_id',
                            'task.status', '.strands.strand AS strand',
                            '.substrands.strand AS substrand'
                        ])
                        ->join('strands AS strands', 'task.strand', '=', 'strands.id')
                        ->join('strands AS substrands', 'task.substrand', '=', 'substrands.id')
                        ->where('task.id', '=', $id)
                        ->get()->first();
    }
    
    /**
     * get a test .
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getRevisionPrintDetail($params) {
        $query = $this->model
                        ->select([
                            'task.id', 'task.key_stage', 'task.year_group', 'task.subject',
                            'task.strand AS strand_id', 'task.substrand AS substrand_id',
                            'task.status', '.strands.strand AS strand',
                            '.substrands.strand AS substrand'
                        ])
                        ->join('strands AS strands', 'task.strand', '=', 'strands.id')
                        ->join('strands AS substrands', 'task.substrand', '=', 'substrands.id');
        if(isset($params['id'])){
            $query->where('task.id', '=', $params['id']);
        }
        if(isset($params['subject'])){
            $query->where('task.subject', '=', $params['subject']);
        }
        if(isset($params['key_stage'])){
            $query->where('task.key_stage', '=', $params['key_stage']);
        }
        if(isset($params['year_group'])){
            $query->where('task.year_group', '=', $params['year_group']);
        }
        if(isset($params['strand'])){
            $query->where('task.strand', '=', $params['strand']);
        }
        if(isset($params['substrand'])){
            $query->where('task.substrand', '=', $params['substrand']);
        }
        return $query->get()->first()->toArray();
    }

    public function getPrintTestQuestions($params) {
        $questionset = Questionset::findOrFail($params['question_set']);
        $setName = $questionset->set_name;
        $subjectPaper = subjectPapers()[$params['subject']];
        $questions = $this->getTaskQuestions(array(
            'question_set_id' => $params['question_set'],
            'key_stage' => $params['key_stage'],
            'year_group' => $params['year_group'],
            'paper_id'=>$params['paper_id']
        ));

        $questionTemp = array();
        $totalQuestions = 0;
        $totalMarks = 0;
        if (count($questions)) {
            foreach ($questions as $question) {
                $questionTemp['subject'] = $params['subject'];
                $questionTemp['set_name'] = $setName;
                $questionTemp['paper'] = $subjectPaper[$question['paper_id']]['name'];
                $questionTemp['total_questions'] = isset($questionTemp[$question['paper_id']]['total_questions']) ? ($questionTemp[$question['paper_id']]['total_questions'] + 1) : 1;
                $questionTemp['total_marks'] = isset($questionTemp[$question['paper_id']]['total_marks']) ? ($questionTemp[$question['paper_id']]['total_marks'] + $question['total_marks']) : $question['total_marks'];
                $questionTemp['questionsData'][] = $this->prepareQuestionData($question);
                $totalMarks = $totalMarks + $question['total_marks'];
                $totalQuestions = $totalQuestions + 1;
            }
            /*foreach ($questions as $question) {
                $questionTemp[$question['paper_id']]['subject'] = $params['subject'];
                $questionTemp[$question['paper_id']]['set_name'] = $setName;
                $questionTemp[$question['paper_id']]['paper'] = $subjectPaper[$question['paper_id']]['name'];
                $questionTemp[$question['paper_id']]['total_questions'] = isset($questionTemp[$question['paper_id']]['total_questions']) ? ($questionTemp[$question['paper_id']]['total_questions'] + 1) : 1;
                $questionTemp[$question['paper_id']]['total_marks'] = isset($questionTemp[$question['paper_id']]['total_marks']) ? ($questionTemp[$question['paper_id']]['total_marks'] + $question['total_marks']) : $question['total_marks'];
                $questionTemp[$question['paper_id']]['questionsData'][] = $this->prepareQuestionData($question);
                $totalMarks = $totalMarks + $question['total_marks'];
                $totalQuestions = $totalQuestions + 1;
            }*/
        }
        return array(
            'subject' => $params['subject'],
            'set_name' => $setName,
            'key_stage' => $params['key_stage'],
            'year_group' => $params['year_group'],
            'total_questions' => $totalQuestions,
            'total_marks' => $totalMarks,
            'questions' => $questionTemp
            //'questions' => array_values($questionTemp)
        );
    }

    public function prepareQuestionData($question) {
        $questionDetailRow = array();
        if (!empty($question['sub_questions']) && !empty($question['sub_questions_ans'])) {
            $questionDetailRow = unserialize($question['sub_questions']);
            $sub_questions_ans = unserialize($question['sub_questions_ans']);
            foreach ($questionDetailRow as $key => $value) {
                switch ($question['question_type']):
                    case 6;
                        $correctAns = $sub_questions_ans[$key][0];
                        break;
                    case 9;
                        $tempCorrectAns = array('value' => $sub_questions_ans[$key]);
                        $correctAns = $tempCorrectAns;
                        break;
                    case 32;
                        $sub_questions_ans = $sub_questions_ans[0];
                        foreach ($value['option'] as $kOpt => $option) { 
                            if($kOpt == 0){
                                $sub_questions_ans[0] = $option;
                            }else{
                                $sub_questions_ans[$kOpt][0] = $option[0];
                            }
                        }
                        $tempCorrectAns = array('option' => $sub_questions_ans);
                        $correctAns = $tempCorrectAns;
                        break;
                    case 31;
                        $tempCorrectAns = array();
                        foreach ($value['option'] as $kOpt => $option) { 
                            $tempCorrectAnsOuter = '';
                            if(isset($sub_questions_ans[$key][$kOpt])){
                                $k = 0;
                                foreach($sub_questions_ans[$key][$kOpt] as $k => $value){ 
                                    //if(!empty($value['value'])){
                                    if ($value['value']!='') {
                                        $tempCorrectAnsOuter .= $k != 0 ? ",".trim($value['value']) : trim($value['value']);
                                        $k++;
                                    }
                                }
                            }
                            $tempCorrectAns[$kOpt] = array('value' => $tempCorrectAnsOuter);
                        }
                        $correctAns = $tempCorrectAns;
                        break;
                    case 13;
                        $justifypart = $value['showsmultiple'] ? $value['elseques'] : array();
                        $tempCorrectAns = array(0 => array('main' => $sub_questions_ans[$key], 'justifypart' => $justifypart));
                        $correctAns = $tempCorrectAns;
                        //asd($correctAns,0);
                        break;
                    case 4;
                        $tempCorrectAns = array();
                        foreach ($value['option'] as $kOpt => $option) {
                            $optionTemp[] = $option['right'];
                        }
                        foreach ($value['option'] as $kOpt => $option) {
                            $tempCorrectAns[] = array('source' => (string) ($kOpt), 'target' => (string) array_search($sub_questions_ans[$key][$kOpt], $optionTemp));
                        }
                        $correctAns = $tempCorrectAns;
                        break;
                    case 12;
                        $tempCorrectAns = array();
                        foreach ($value['option']['optionvalue'] as $kOpt => $option) {
                            if (isset($sub_questions_ans[$key][$kOpt]['ischeck'])) {
                                if ($sub_questions_ans[$key][$kOpt]['ischeck']) {
                                    $tempCorrectAns[] = $option['value'];
                                }
                            }
                        }
                        $correctAns = (array) $tempCorrectAns;
                        break;
                    case 10;
                        $tempCorrectAns = array('option' => $value['option']);
                        $correctAns = $tempCorrectAns;
                        break;

                    default:
                        $correctAns = $sub_questions_ans[$key];
                endswitch;
                $questionDetailRow[$key]['correctAns'] = $correctAns;
            }
        }
        unset($question['sub_questions']);
        unset($question['sub_questions_ans']);
        $question['questions'] = $questionDetailRow;
        return $question;
    }

    /**
     * get task question
     * @param type $params
     * @return type
     */
    public function getTaskQuestions($params) {

        $query = $this->question
                ->select([
            'id', 'total_marks', 'strands_id', 'substrands_id', 'paper_id',
            'question_type_id AS question_type', 'description',
            'sub_questions', 'sub_questions_ans'
        ]);
        if (isset($params['questionids'])) {
            $query->whereIn('id', $params['questionids']);
        }
        if (isset($params['question_set_id'])) {
            $query->where('question_set_id', $params['question_set_id']);
        }
        if (isset($params['question_paper_id'])) {
            $query->where('paper_id', $params['question_paper_id']);
        }
        if (isset($params['key_stage'])) {
            $query->where('key_stage', $params['key_stage']);
        }
        if (isset($params['year_group'])) {
            $query->where('year_group', $params['year_group']);
        }
        if (isset($params['paper_id'])) {
            $query->where('paper_id', $params['paper_id']);
        }
        if (isset($params['subject'])) {
            $query->where('subject', $params['subject']);
        }
        if (isset($params['strand'])) {
            $query->where('strands_id', $params['strand']);
        }
        if (isset($params['substrand'])) {
            $query->where('substrands_id', $params['substrand']);
        }
        if (isset($params['difficulty']) && !empty($params['difficulty'])) {
            $query->whereIn('difficulty', explode(',',$params['difficulty']));
        }
        
        $query->where('status', 'Published');
        $query->whereIn('question_type_id', ['13', '11', '3', '7', '4', '22', '16', '2', '1', '12', '6', '14', '8', '9', '19', '15', '20', '18', '10', '17', '21', '23', '24', '25', '26', '27', '28', '29', '30']);
        
        if (isset($params['revision']) && $params['revision'] == true) {        
            $query->orderBy(DB::raw('RAND()'))
                ->take(REVISION_QUS_LIMIT);
        }
        
        return $query->get()->toArray();
    }

    /**
     * get a assign test .
     * @author     Icreon Tech - dev2.
     * @param  array  $id
     * @return response
     */
    public function getTestassign($params) {
        $query = $this->taskassignment
                ->select([
            'taskassignments.id', 'taskassignments.task_id', 'taskassignments.assign_date', 'taskassignments.completion_date'
        ]);
        if ($params['task_id']) {
            $query->where('taskassignments.task_id', '=', $params['task_id']);
        }

        return $query->get()->first();
    }

    /**
     * get a assign test .
     * @author     Icreon Tech - dev2.
     * @param  array  $id
     * @return response
     */
    public function getTaskassign($params) {
        $query = $this->taskassignment
                ->select([
            'taskassignments.id', 'taskassignments.task_id', 'taskassignments.assign_date', 'taskassignments.completion_date'
        ]);
        if (isset($params['id'])) {
            $query->where('taskassignments.id', '=', $params['id']);
        }
        if (isset($params['task_id'])) {
            $query->where('taskassignments.task_id', '=', $params['task_id']);
        }

        return $query->get()->first();
    }

    public function getTaskAssignedStudentList($params) {
        return $this->taskassignment
                        ->select([
                            'users.id', 'users.first_name', 'users.last_name', 'taskstudents.attempt_status', 'taskstudents.created_at'
                        ])
                        ->join('taskstudents', 'taskassignments.id', '=', 'taskstudents.assign_id')
                        ->join('users', 'users.id', '=', 'taskstudents.student_id')
                        ->where(['taskstudents.assign_id' => $params['assign_id'], 'student_source_id' => $params['student_source_id']]);
    }

    public function getTaskStudents($params) {
        return Taskstudents::select(['taskstudents.assign_id', 'student_id'])->where('assign_id', '=', $params['assign_id'])->get()->toArray();
    }

    public function sendStudentAssignEmailNotif($params) {
        //email notification
        $test_name = '';
        $topic_name = '';
        if ($params['task_type'] == TEST) {
            $emailTemplateId = 30;
            $test = $this->getTest($params['task_id']);
            $test_name = $test->set_name;
        } else {
            $emailTemplateId = 29;
            $revision = $this->getRevision($params['task_id']);
            $topic_name = $revision->substrand;
        }
        
        $users = User::select(['id','email' ,'first_name', 'last_name'])->whereIn('id', $params['studentIds'])->get()->toArray();
        foreach ($users as $user) {
            $to_name = $user['first_name'] . ' ' . $user['last_name'];
            $emailParam = array(
                'addressData' => array(
                    'to_email' => $user['email'], //$params['to_email'],
                    'to_name' => $to_name,
                ),
                'userData' => array(
                    'name' => $to_name,
                    'teacher_name' => $params['teacher_name'],
                    'test_name' => $test_name,
                    'topic_name' => $topic_name,
                )
            );
            if(!empty($user['email']))
                $this->emailRepo->sendEmail($emailParam, $emailTemplateId);
        }
    }
/**
     * get test list grid query.
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getSetQuestionList($params) {
        
         return DB::table('questions')->select([
                            'id',
                            'description',
                            'subject',
                            'set_group',
                            'question_set_id',
                            'question_id',
                            'question_type_id',
                            'sub_questions',
                            'sub_questions_ans'])
                        ->where('question_set_id', '=', $params['id'])
                        ->where('status', '!=', DELETED)
                 ->get();
    }
    
    /**
     * get task question
     * @param type $params
     * @return type
     */
    public function testgetPrintTestQuestions($params = array()) {
       
        $query = $this->question
                ->select([
            'id', 'total_marks', 'strands_id', 'substrands_id', 'paper_id',
            'question_type_id AS question_type', 'description',
            'sub_questions', 'sub_questions_ans'
        ]);
     //   $query->where('status', 'Published');
       // if (isset($params['questionids'])) {
        $alltypequestion= '1618,1691,1630,1611,1687,1607,1661,1676,1545,1637,1582,19,1634,1539,228,1651,1609,1680,1682,1677,1681,1678,1679,1616,1528,1674,1656,1646';   // Drag Drop & Re-ordering(1644)
        $kk = $alltypequestion='516,162,229,1165,332,102,20473,120,10947,189,199,10949,476,14463,14894,14146,12102,15343,16201,14447,16136,14885,14890,13664,14802,13432,13103';//='1630';
        
        $kkarr  = explode(",",$kk);
            $query->whereIn('id', $kkarr);

       // }
        $questions =  $query->get()->toArray();
            $questionTemp = array();
        $totalQuestions = 0;
        $totalMarks = 0;
        if (count($questions)) {
            foreach ($questions as $question) {
                $questionTemp[$question['paper_id']]['subject'] = $params['subject']="math";
                $questionTemp[$question['paper_id']]['set_name'] = $setName="aaaa";
                $questionTemp[$question['paper_id']]['paper'] = "Paper 1";
                $questionTemp[$question['paper_id']]['total_questions'] = isset($questionTemp[$question['paper_id']]['total_questions']) ? ($questionTemp[$question['paper_id']]['total_questions'] + 1) : 1;
                $questionTemp[$question['paper_id']]['total_marks'] = isset($questionTemp[$question['paper_id']]['total_marks']) ? ($questionTemp[$question['paper_id']]['total_marks'] + $question['total_marks']) : $question['total_marks'];
                $questionTemp[$question['paper_id']]['questionsData'][] = $this->prepareQuestionData($question);
                $totalMarks = $totalMarks + $question['total_marks'];
                $totalQuestions = $totalQuestions + 1;
            }
        }
        
        return array(
            'subject' => 'Math',
            'set_name' => $setName,
            'key_stage' => $params['key_stage']="2",
            'year_group' => $params['year_group']="6",
            'total_questions' => $totalQuestions,
            'total_marks' => $totalMarks,
            'questions' => array_values($questionTemp)
        );        
            
            
        //$query->whereIn('question_type_id', ['13', '11', '3', '7', '4', '22', '16', '2', '1', '12', '6', '14', '8', '9', '19', '15', '20', '18', '10', '17', '21', '23', '24', '25', '26', '27', '28', '29', '30']);
        
    }
    
    public function getTaskAssignmentDetails($params = array()){
                return $this->taskassignment
                        ->select([
                            'taskassignments.difficulty', 'task.key_stage', 'task.year_group', 'task.subject', 'task.strand','task.substrand','strands.strand as strand_name','substrands.strand as sub_strand_name'
                        ])
                        ->join('task', 'task.id', '=', 'taskassignments.task_id')
                        ->join('strands as strands', 'task.strand', '=', 'strands.id')
                        ->join('strands as substrands', 'task.substrand', '=', 'substrands.id')
                        ->where('taskassignments.id', '=', $params['id']);
                        
    }
    
    
 public function getPrintRevisionQuestions($params) {
        $questions = $this->getTaskQuestions(array(
            'subject' => $params['subject'],
            'key_stage' => $params['key_stage'],
            'year_group' => $params['year_group'],
            'strand' => $params['strand'],
            'substrand' => $params['substrand'],
            'difficulty' => $params['difficulty'],
            'revision' => $params['revision'],
        ));
        $questionTemp = array();
        $totalQuestions = 0;
        $totalMarks = 0;
        if (count($questions)) {
            foreach ($questions as $question) {
                $questionTemp['subject'] = $params['subject'];
                $questionTemp['total_questions'] = isset($questionTemp[$question['paper_id']]['total_questions']) ? ($questionTemp[$question['paper_id']]['total_questions'] + 1) : 1;
                $questionTemp['total_marks'] = isset($questionTemp[$question['paper_id']]['total_marks']) ? ($questionTemp[$question['paper_id']]['total_marks'] + $question['total_marks']) : $question['total_marks'];
                $questionTemp['questionsData'][] = $this->prepareQuestionData($question);
                $totalMarks = $totalMarks + $question['total_marks'];
                $totalQuestions = $totalQuestions + 1;
            }
            /*foreach ($questions as $question) {
                $questionTemp[$question['paper_id']]['subject'] = $params['subject'];
                $questionTemp[$question['paper_id']]['set_name'] = $setName;
                $questionTemp[$question['paper_id']]['paper'] = $subjectPaper[$question['paper_id']]['name'];
                $questionTemp[$question['paper_id']]['total_questions'] = isset($questionTemp[$question['paper_id']]['total_questions']) ? ($questionTemp[$question['paper_id']]['total_questions'] + 1) : 1;
                $questionTemp[$question['paper_id']]['total_marks'] = isset($questionTemp[$question['paper_id']]['total_marks']) ? ($questionTemp[$question['paper_id']]['total_marks'] + $question['total_marks']) : $question['total_marks'];
                $questionTemp[$question['paper_id']]['questionsData'][] = $this->prepareQuestionData($question);
                $totalMarks = $totalMarks + $question['total_marks'];
                $totalQuestions = $totalQuestions + 1;
            }*/
        }
        return array(
            'subject' => $params['subject'],
            'key_stage' => $params['key_stage'],
            'year_group' => $params['year_group'],
            'strand' => $params['strand'],
            'substrand' => $params['substrand'],
            'difficulty' => $params['difficulty'],
            'total_questions' => $totalQuestions,
            'total_marks' => $totalMarks,
            'questions' => $questionTemp
        );
    }    
}
