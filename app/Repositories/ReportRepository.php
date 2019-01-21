<?php

/**
 * This is used for report.
 * @package    Report
 * @author     Icreon Tech  - dev1.
 */

namespace App\Repositories;

use App\Models\User;
use App\Models\Taskstudents;
use App\Models\Task;
use App\Models\Studenttestattempt;
use App\Models\Studenttest;
use App\Models\Studentrevision;
use App\Models\Questionanswer;
use App\Models\Question;
use App\Models\Schoolclass;
use App\Models\StudentTestResult;
use Carbon\Carbon;
use Image;
use DB;

/**
 * This is used for user save/edit for users data.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class ReportRepository extends BaseRepository {

    /**
     * The Student instance.
     *
     * @var App\Models\Student
     */
    protected $user;

    /**
     * The Task instance.
     *
     * @var App\Models\Task
     */
    protected $task;
    protected $model;

    /**
     * The Studenttestattempt instance.
     *
     * @var App\Models\Studenttestattempt
     */
    protected $studenttestattempt;

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
    public $currentDate;
    public $currentDateTime;

    /**
     * Create a new UserRepository instance.
     *
     * @param  App\Models\User $user
     * @param  App\Models\Student $student
     * @return void
     */
    public function __construct(User $user, Taskstudents $model, Task $task, Studenttestattempt $studenttestattempt, Studenttest $studenttest, Studentrevision $studentrevision) {
        $this->user = $user;
        $this->model = $model;
        $this->task = $task;
        $this->studenttestattempt = $studenttestattempt;
        $this->studenttest = $studenttest;
        $this->studentrevision = $studentrevision;
        $this->currentDate = Carbon::now()->toDateString();
        $this->currentDateTime = Carbon::now()->toDateTimeString();
    }

    public function teacherOverviewTaskList($params) {
        $return = array('duerevision' => 0, 'duetest' => 0);
        $result = $this->getTeacherOverdueTask($params);
        if (count($result)) {
            $duerevision = 0;
            $duetest = 0;
            foreach ($result as $row) {
                if ($row['task_type'] == REVISION) {
                    $return['duerevision'] ++;
                } else {
                    $return['duetest'] ++;
                }
            }
        }
        return $return;
    }

    public function getTeacherOverdueTask($params) {
        $query = $this->model
                ->select(['task.id', 'task.task_type',
                    'student_test_attempt.id AS student_test_attempt_id',
                    'student_test_attempt.status AS student_test_attempt_status',
                    DB::raw('IFNULL(TIMESTAMPDIFF(SECOND, `student_test_attempt`.`attempt_at`,  "' . $this->currentDateTime . '"),"") AS student_test_attempt_attempttime'),
                    'studentrevision.id AS studentrevision_id',
                    'studentrevision.status AS studentrevision_status',
                    DB::raw('IFNULL(TIMESTAMPDIFF(SECOND, `studentrevision`.`attempt_at`,  "' . $this->currentDateTime . '"),"") AS studentrevision_attempttime')
                ])
                ->join('taskassignments', 'taskstudents.assign_id', '=', 'taskassignments.id')
                ->join('task', 'taskassignments.task_id', '=', 'task.id')
                ->where([
            'task.status' => ACTIVE,
            'taskassignments.created_by' => $params['teacher_id']
        ]);
        $query->leftJoin('student_test_attempt', function($join) {
                    $join->on('task.id', '=', 'student_test_attempt.task_id');
                    $join->on('student_test_attempt.student_id', '=', DB::raw("taskstudents.student_id"));
                })
                ->leftJoin('studentrevision', function($join) {
                    $join->on('task.id', '=', 'studentrevision.task_id');
                    $join->on('studentrevision.student_id', '=', DB::raw("taskstudents.student_id"));
                });
        $query->where('taskassignments.completion_date', '<', $this->currentDate)
                ->havingRaw(DB::raw("IF(student_test_attempt_attempttime != '', (student_test_attempt_status = 'Pending'),1)"))
                ->havingRaw(DB::raw("IF(studentrevision_attempttime != '', (studentrevision_status = 'Pending'),1)"));
        return $query->get()->toArray();
    }
    public function studentGraph($params) {
        if (isset($params['tutor_id']) && $params['tutor_id'] != 0 && $params['tutor_id'] != '') {
            // $sqlGraphDetail = "select task.id,task.subject,questionsets.id,(student_test_attempt.total_marks * (student_test_attempt.attempt_completed-1)) as totalMarks, (student_test_attempt.total_marks_obtain*100)/(student_test_attempt.total_marks * (student_test_attempt.attempt_completed-1)) as MarksObtain, student_test_attempt.aggregate_attempt_1 as attempt1, student_test_attempt.aggregate_attempt_2 as attempt2, student_test_attempt.aggregate_attempt_3 as attempt3 
            $sqlGraphDetail = "select student_test_attempt.id,studenttest.attempt,studenttest.total_marks, studenttest.mark_obtain, studenttest.status, task.subject
            from task 
            join student_test_attempt on student_test_attempt.task_id = task.id and student_test_attempt.status='Completed' 
            JOIN studenttest ON studenttest.test_attempt_id = student_test_attempt.id AND studenttest.status='Completed'
            join questionsets on questionsets.id = task.question_set where student_test_attempt.student_id = '" . $params['studentId'] . "'  order by questionsets.set_name " . $params['sortby'];
        } else {
            $sqlGraphDetail = "select student_test_attempt.id,studenttest.attempt,studenttest.total_marks, studenttest.mark_obtain, studenttest.status, task.subject
                    from task Join  taskstudents  on task.id = taskstudents.task_id and task_type = 'Test'  
                    join student_test_attempt on student_test_attempt.student_id = taskstudents.student_id  and student_test_attempt.task_id = taskstudents.task_id and student_test_attempt.status='" . COMPLETED . "'                     
                    JOIN studenttest ON studenttest.test_attempt_id = student_test_attempt.id AND studenttest.status='Completed'                        
                    join questionsets on questionsets.id = task.question_set where taskstudents.student_id = '" . $params['studentId'] . "' order by questionsets.set_name " . $params['sortby'];
        }

        $graphDetail = DB::select($sqlGraphDetail);
        // asd($graphDetail,0);
        $graphArray = array();
        $graphArray['English'] = array();
        $graphArray['Math'] = array();
        $graphDetailNew = array();
        foreach ($graphDetail as $res => $row) {
            $graphDetailNew[$row->id][] = $row;
        }//asd($graphDetailNew, 0);
        if (count($graphDetailNew) > 0) {
            $i = 0;
            foreach ($graphDetailNew as $res1 => $row1) {
                $perceEngMarks = 0;
                $perceMathMarks = 0;
                $totalEngCnt = 0;
                $totalMathCnt = 0;
                foreach ($graphDetailNew[$res1] as $res => $row) {

                    if ($row->subject == 'English') {
                        $perceEngMarks+=round($row->mark_obtain / $row->total_marks * 100);
                        $totalEngCnt++;
                    } else {
                        $perceMathMarks+=round($row->mark_obtain / $row->total_marks * 100);
                        $totalMathCnt++;
                    }
                    if ($row->subject == 'English') {
                        $graphArray['English'][$i] = round($perceEngMarks / $totalEngCnt);
                    } else {
                        $graphArray['Math'][$i] = round($perceMathMarks / $totalMathCnt);
                    }
                }
                $i++;
            }
        }
        $graphArray['English'] = array_values($graphArray['English']);
        $graphArray['Math'] = array_values($graphArray['Math']);
        return $graphArray;
    }
    public function studentTestGraph($params) {
        $studentTestGraph = array('English' => array(),'Math' => array());
        $studentTestGraphResult = StudentTestResult::select(['test_attempt_avg_marks','subject'])->where(['student_id' => $params['studentId']])->orderBy('created_at')->get()->toArray();
        if(count($studentTestGraphResult)){
            foreach($studentTestGraphResult as $gRow){
                if($gRow['subject'] == MATH){
                    $studentTestGraph['Math'][] = round($gRow['test_attempt_avg_marks']);
                }else{
                    $studentTestGraph['English'][] = round($gRow['test_attempt_avg_marks']);
                }
            }
        }
        return $studentTestGraph;
    }
    public function studentDetail($params) {
        $sqlStudentDetail = "select users.id,users.first_name,users.last_name,ks2_maths_baseline_value,ks2_english_baseline_value,group_concat( distinct(schoolclasses.class_name)) as className ,group_concat( distinct(groups.group_name)) as groupName ,users.last_login,count(student_earn_rewards.id) as certificate from users left join classstudents on classstudents.student_id=users.id left join schoolclasses on classstudents.class_id=schoolclasses.id left join groupstudents on groupstudents.student_id = users.id left join groups on groups.id = groupstudents.group_id left join student_earn_rewards on student_earn_rewards.student_id = users.id where users.id='" . $params['studentId'] . "' group by users.id";
        $studentDetail = DB::select($sqlStudentDetail);
        return $studentDetail;
    }
    function pupilOverviewtest($params){ 
        $schoolAvgScoreResult = StudentTestResult::select('*')->where(['student_id' => $params['studentId'],'subject' => $params['subject']])->orderBy('created_at')->get()->toArray();
        $paperNum = $params['subject'] == MATH ? 3 : 2;
        $gridData = array();
        $heightScore = 0;
        $lowestScore = 100;
        $testAvgScore = 0;
        if(count($schoolAvgScoreResult)){
            foreach($schoolAvgScoreResult as $row){
                $avgScore = round($row['test_attempt_avg_marks']);
                $testAvgScore = $testAvgScore + $avgScore;
                $heightScore = $avgScore > $heightScore ? $avgScore : $heightScore;
                $lowestScore = $avgScore < $lowestScore ? $avgScore : $lowestScore;
                $gridData[] = array(
                                'record' => array(
                                        'test_name' => $row['set_name'],
                                        'attempt_1' => $this->progressBar(array(
                                            'percent' => $row['att1_avg_marks']
                                        )),
                                        'attempt_2' => $this->progressBar(array(
                                            'percent' => $row['att2_avg_marks']
                                        )),
                                        'attempt_3' => $this->progressBar(array(
                                            'percent' => $row['att3_avg_marks']
                                        )),
                                        'avg_score' => $this->progressBar(array(
                                            'percent' => $row['test_attempt_avg_marks']
                                        )),
                                        'last_assessementdate' => $row['att_last_assessment_date'],
                                        'timeliness ' => $this->progressBar(array(
                                                'timeliness' => TRUE,
                                                'paperNum' => $paperNum,
                                                'time' => $row['test_attempt_avg_time'],
                                                'p1_time' => $row['p1_max_time'],
                                                'p2_time' => $row['p2_max_time'],
                                                'p3_time' => $row['p3_max_time']
                                        ))
                                    ),
                                'record_detail' => $this->pupilOverviewtestRecordDetail($paperNum,$row)
                            );
            }
            $testAvgScore = round($testAvgScore/count($schoolAvgScoreResult));
        }
        return array(
            'gridData' => $gridData,
            'metaData' => array(
                'heightScore' => $heightScore,
                'lowestScore' => $lowestScore,
                'avgScore' => $testAvgScore,
            )
        );
    }
    function progressBar($params){
        if(isset($params['timeliness'])){
            if(isset($params['paperNum'])){
                if($params['paperNum'] == 3){
                    $maxtime = ($params['p1_time']+$params['p2_time']+$params['p3_time'])/3;
                }else{
                    $maxtime = ($params['p1_time']+$params['p2_time'])/2;
                }
            }else{
                $maxtime = $params['p_time'];
            }
            $label = round($params['time']/60);
            $percentTemp = round(($params['time']/$maxtime)*100);
            $percent = $percentTemp >= 100 ? 100 : $percentTemp;
            if($percent == 100){
                $color['color'] = 'red';
                $color['full'] = 'full';
            }else{
                $color['color'] = 'green';
                $color['full'] = '';
            }
            return array('label' => $label , 'percent' => $percent, 'color' => $color['color'], 'full' => $color['full']);
        }else{
            if(is_numeric($params['percent'])){
                $percent = $label = round($params['percent']);
                $color = $this->getColor($percent);
                return array('label' => $label , 'percent' => $percent, 'color' => $color['color'], 'full' => $color['full']);
            }else{
                return FALSE;
            }
            
        }
        
    }
    function pupilOverviewtestRecordDetail($paperNum,$row){
        $record_detail = array();
        for($i=1;$i<=$paperNum;$i++){
            $att1_p = 'att1_p'.$i;
            $att2_p = 'att2_p'.$i;
            $att3_p = 'att3_p'.$i;
            $p_avg = 'p'.$i.'_avg';
            $p_avg_time = 'p'.$i.'_avg_time';
            $p_max_time = 'p'.$i.'_max_time';
            $p_last_assessementdate = 'p'.$i.'_last_assessment_date';
            $record_detail[$i] = array(
                                    'paper_name' => 'Paper '.$i,
                                    'attempt_1' => $this->progressBar(array(
                                        'percent' => $row[$att1_p]
                                    )),
                                    'attempt_2' => $this->progressBar(array(
                                        'percent' => $row[$att2_p]
                                    )),
                                    'attempt_3' => $this->progressBar(array(
                                        'percent' => $row[$att3_p]
                                    )),
                                    'avg_score' => $this->progressBar(array(
                                        'percent' => $row[$p_avg]
                                    )),
                                    'last_assessementdate' => $row[$p_last_assessementdate],
                                    'timeliness ' => $this->progressBar(array(
                                        'timeliness' => TRUE,
                                        'time' => $row[$p_avg_time],
                                        'p_time' => $row[$p_max_time]
                                    ))
                                );
        }
        return $record_detail;
    }
    function getTimeliness($params){
        if(isset($params['paperNum'])){
            if($params['paperNum'] == 3){
                $maxtime = ($params['p1_time']+$params['p2_time']+$params['p3_time'])/3;
            }else{
                $maxtime = ($params['p1_time']+$params['p2_time'])/2;
            }
        }else{
            $maxtime = $params['p_time'];
        }
        $minute = round($params['time']/60);
        $percentTemp = round(($params['time']/$maxtime)*100);
        $percent = $percentTemp >= 100 ? 100 : $percentTemp;
        $color = $percent > 100 ? 'qa_red' : 'qa_light-green';
        return array('minute' => $minute, 'percent' => $percent, 'color' => $color);
    }
    
    function getColor($avg){
        if ($avg <= 20) {
            $labelClassName = 'red';
        } elseif ($avg > 20 && $avg <= 50) {
            $labelClassName = 'orange';
        } elseif ($avg > 50 && $avg <= 70) {
            $labelClassName = 'green';
        } elseif ($avg > 70) {
            $labelClassName = 'blue';
        }
        $full = $avg == 100 ? 'full' : '';
        return array('color' => $labelClassName,'full' => $full);
    }
    function studentTestReport($params) {

        if (isset($params['tutor_id']) && $params['tutor_id'] != 0 && $params['tutor_id'] != '') {
            // $sqlGraphDetail = "select task.id,task.subject,questionsets.id,(student_test_attempt.total_marks * (student_test_attempt.attempt_completed-1)) as totalMarks, (student_test_attempt.total_marks_obtain*100)/(student_test_attempt.total_marks * (student_test_attempt.attempt_completed-1)) as MarksObtain, student_test_attempt.aggregate_attempt_1 as attempt1, student_test_attempt.aggregate_attempt_2 as attempt2, student_test_attempt.aggregate_attempt_3 as attempt3 
            $sqlGraphDetail = "select student_test_attempt.id,studenttest.attempt,studenttest.total_marks, studenttest.mark_obtain, studenttest.status, task.subject
            from task 
            join student_test_attempt on student_test_attempt.task_id = task.id and student_test_attempt.status='Completed' 
            JOIN studenttest ON studenttest.test_attempt_id = student_test_attempt.id AND studenttest.status='Completed'
            join questionsets on questionsets.id = task.question_set where student_test_attempt.student_id = '" . $params['studentId'] . "'  order by questionsets.set_name " . $params['sortby'];
        } else {
            $sqlGraphDetail = "select student_test_attempt.id,studenttest.attempt,studenttest.total_marks, studenttest.mark_obtain, studenttest.status, task.subject
                    from task Join  taskstudents  on task.id = taskstudents.task_id and task_type = 'Test'  
                    join student_test_attempt on student_test_attempt.student_id = taskstudents.student_id  and student_test_attempt.task_id = taskstudents.task_id and student_test_attempt.status='" . COMPLETED . "'                     
                    JOIN studenttest ON studenttest.test_attempt_id = student_test_attempt.id AND studenttest.status='Completed'                        
                    join questionsets on questionsets.id = task.question_set where taskstudents.student_id = '" . $params['studentId'] . "' order by questionsets.set_name " . $params['sortby'];
        }

        $graphDetail = DB::select($sqlGraphDetail);
        // asd($graphDetail,0);
        $graphArray = array();
        $graphArray['English'] = array();
        $graphArray['Math'] = array();
        $graphDetailNew = array();
        foreach ($graphDetail as $res => $row) {
            $graphDetailNew[$row->id][] = $row;
        }//asd($graphDetailNew, 0);
        if (count($graphDetailNew) > 0) {
            $i = 0;
            foreach ($graphDetailNew as $res1 => $row1) {
                $perceEngMarks = 0;
                $perceMathMarks = 0;
                $totalEngCnt = 0;
                $totalMathCnt = 0;
                foreach ($graphDetailNew[$res1] as $res => $row) {

                    if ($row->subject == 'English') {
                        $perceEngMarks+=round($row->mark_obtain / $row->total_marks * 100);
                        $totalEngCnt++;
                    } else {
                        $perceMathMarks+=round($row->mark_obtain / $row->total_marks * 100);
                        $totalMathCnt++;
                    }
                    if ($row->subject == 'English') {
                        $graphArray['English'][$i] = round($perceEngMarks / $totalEngCnt);
                    } else {
                        $graphArray['Math'][$i] = round($perceMathMarks / $totalMathCnt);
                    }
                }
                $i++;
            }
        }
        $graphArray['English'] = array_values($graphArray['English']);
        $graphArray['Math'] = array_values($graphArray['Math']);
        
        if(!isset($params['testGraph'])) {
        
                if (isset($params['tutor_id']) && $params['tutor_id'] != 0 && $params['tutor_id'] != '') {
                    $sql = "select questionsets.id as setId,questionsets.set_name as setName , student_test_attempt.id as testAttemptId ,student_test_attempt.total_marks as totalMarks,student_test_attempt.total_time,student_test_attempt.aggregate_attempt_1 as attempt1, student_test_attempt.aggregate_attempt_2 as attempt2 ,student_test_attempt.aggregate_attempt_3 as attempt3, student_test_attempt.last_assessment_date 
                        FROM task 
                        JOIN student_test_attempt ON student_test_attempt.task_id = task.id AND student_test_attempt.status='Completed'
                        join questionsets on questionsets.id = task.question_set where student_test_attempt.student_id = '" . $params['studentId'] . "' and task.subject='" . $params['subject'] . "' and task.task_type = '" . $params['report'] . "'
                        order by questionsets.set_name " . $params['sortby'];
                } else {
                    $sql = "select questionsets.id as setId,questionsets.set_name as setName , student_test_attempt.id as testAttemptId ,student_test_attempt.total_marks as totalMarks,student_test_attempt.total_time,student_test_attempt.aggregate_attempt_1 as attempt1, student_test_attempt.aggregate_attempt_2 as attempt2 ,student_test_attempt.aggregate_attempt_3 as attempt3, student_test_attempt.last_assessment_date from task Join  taskstudents  on task.id = taskstudents.task_id and task_type = '" . $params['report'] . "' and subject='" . $params['subject'] . "' join student_test_attempt on student_test_attempt.student_id = taskstudents.student_id and student_test_attempt.task_id = taskstudents.task_id and student_test_attempt.status='Completed' join questionsets on questionsets.id = task.question_set where taskstudents.student_id = '" . $params['studentId'] . "' order by questionsets.set_name " . $params['sortby'];
                }

                $results = DB::select($sql);

                if (count($results) > 0) {
                    foreach ($results as $key => $val) {
                        $sqlSub = "select * from studenttest where status='Completed' and test_attempt_id = " . $val->testAttemptId;
                        $resultSub = DB::select($sqlSub);
                        $results[$key]->subResult = $resultSub;
                    }
                }

                $resultArray = array();
                $resultArray['studentDetail'] = $this->studentDetail($params);
                $resultArray['grid'] = $results;
        
        }
        $resultArray['graph'] = $graphArray;
        
        return $resultArray;
    }

    public function studentRevisionReport($params) {
        $sqlGraphDetail = "SELECT SUM(studentrevision.total_marks) AS totalMarks, SUM(studentrevision.mark_obtain) AS MarksObtain,task.task_type,task.subject,student_strand_meta.substrand_aggrigate FROM studentrevision JOIN task  ON task.id = studentrevision.task_id  AND task.task_type = '" . $params['report'] . "'  AND studentrevision.student_id = '" . $params['studentId'] . "'  JOIN strands ON strands.id=task.strand AND strands.parent_id = 0  JOIN student_strand_meta ON  student_strand_meta.strand_id = strands.id  AND student_strand_meta.task_type='" . $params['report'] . "' AND student_strand_meta.student_id ='" . $params['studentId'] . "' GROUP BY strands.id order by strands.strand " . $params['sortby'];
        $graphDetail = DB::select($sqlGraphDetail);
        $graphArray = array();
        if (count($graphDetail) > 0) {
            // asd($graphDetail);
            foreach ($graphDetail as $res => $row) {
                $perceMarks = 0;
                $substandsArray = unserialize($row->substrand_aggrigate);
                foreach ($substandsArray as $k => $v) {

                    $totalMarks = $v[0];
                    $obtainedMarks = $v[1];
                    $perceMarks+= ($obtainedMarks * 100) / $totalMarks;
                }
                $perceMarks = round($perceMarks / count($substandsArray));


                if ($row->subject == 'English') {
                    $graphArray['English'][] = $perceMarks;
                } else {
                    $graphArray['Math'][] = $perceMarks;
                }
            }
        }
        $sql = "SELECT count(studentrevision.id) as attempt_count, task.id AS taskId, studentrevision.id AS studentRevisionId,strands.id AS strandId,strands.strand,sum(studentrevision.num_answered) as num_answered,studentrevision.quesmaxtime,studentrevision.remainingtime,sum(studentrevision.total_marks) as total_marks,student_strand_meta.strand_total_mark,student_strand_meta.strand_mark_obtain,student_strand_meta.substrand_aggrigate FROM studentrevision JOIN task  ON task.id = studentrevision.task_id  AND task.task_type = '" . $params['report'] . "' AND task.subject = '" . $params['subject'] . "' AND studentrevision.student_id = '" . $params['studentId'] . "' JOIN strands ON strands.id=task.strand AND strands.parent_id = 0 JOIN student_strand_meta ON  student_strand_meta.strand_id = strands.id  AND student_strand_meta.task_type='" . $params['report'] . "' AND student_strand_meta.student_id ='" . $params['studentId'] . "'  and studentrevision.status='Completed' GROUP BY strands.id order by strands.strand " . $params['sortby'];
        $results = DB::select($sql);
        if (count($results) > 0) {
            foreach ($results as $key => $val) {
                $sqlSub = "select strands.id, strands.strand,count(studentrevision.id) as attempt_count, sum(studentrevision.num_answered) as num_answered from task left join studentrevision on studentrevision.task_id =  task.id left join strands on strands.id= task.substrand where studentrevision.student_id = '" . $params['studentId'] . "' and task.strand='" . $val->strandId . "' and studentrevision.status='Completed' group by strands.id";
                $resultSub = DB::select($sqlSub);
                $results[$key]->subResult = $resultSub;
            }
        }


        $resultArray = array();
        $resultArray['studentDetail'] = $this->studentDetail($params);

        $resultArray['graph'] = $graphArray;
        $resultArray['grid'] = $results;
        return $resultArray;
        //student_strand_meta
    }
    
    public function classGapReportNew($params) {
        $questionResults = array();
        $studentResults = array();
        $studentAnsArr = array();
        $questionResultsAll = array();
        $sqlStudentResultDataNew = array();
        $studentResultData = array();
        $studentIdArray = array();
        $studentIds = '';

        $sqlStudent = "select users.id,users.first_name,users.last_name from classstudents left join users on users.id =  classstudents.student_id where classstudents.class_id = '" . $params['schoolId'] . "'";
        if (!empty($params['studentId'])) {
            $sqlStudent .= " AND classstudents.student_id = " . $params['studentId'];
        }
        $studentResults = DB::select($sqlStudent);
        foreach($studentResults as $ley=>$val){
            $studentIdArray[] = $val->id; 
        }
        $studentIds = implode(',',$studentIdArray);

        if (count($studentResults) > 0) {
            $listArr = array();
            $studentAnsArr = array();
               //$sqlStudentResult = "select avg(attempt_per_avg) as avg_marks,substrand_id,student_id from student_test_gap_result_data where subject='English'  and student_id in (".$studentIds.") group by student_id,substrand_id";
               $sqlStudentResult = "select * from student_test_gap_result_data where subject='English' and student_id in (".$studentIds.") group by student_id,substrand_id";
               $studentResultData = DB::select($sqlStudentResult);
//               asd($sqlStudentResultData);
        }
       // foreach($sqlStudentResultData as $key=>$val) {
      //      $sqlStudentResultDataNew[$val->substrand_id][$val->student_id] = $val->avg_marks;
       // }
       // asd($sqlStudentResultDataNew);

        $resultArray = array();
        $resultArray['studentList'] = $studentResults;
        $resultArray['studentResultData'] = $studentResultData;
        //$resultArray['questionResultsAll'] = $questionResultsAll;
        //$resultArray['studentAns'] = $studentAnsArr;

        return $resultArray;
    }
    public function classGapReportData($params){
        $query = Questionanswer::select(['question_id','student_id',DB::raw('SUM(mark_obtain) AS mark_obtain')])->where(['task_type' => TEST, 'set_id' => $params['set_id']])->whereIn('student_id',$params['student_ids']);
        if(!empty($params['paper_id'])){
            $query->where('paper_id',$params['paper_id']);
        }
        $query->groupBy(['question_id','student_id']);
        return $query->get()->toArray();
    }
    public function getSetQuestions($params){
        $query = Question::select(['id','strands_id','substrands_id','total_marks'])->where(['set_group' => TEST, 'question_set_id' => $params['set_id']]);
        if(!empty($params['paper_id'])){
            $query->where('paper_id',$params['paper_id']);
        }
        //$query->where('status','!=','Deleted');
        $query->where('published_date','!=',NULL_DATETIME);
        $query->orderBy('id');
        return $query->get()->toArray();
    }
    public function classGapReport($params) {
        $questionResults = array();
        $studentResults = array();
        $studentAnsArr = array();
        $questionResultsAll = array();

        $sqlStudent = "select users.id,users.first_name,users.last_name from classstudents left join users on users.id =  classstudents.student_id where classstudents.class_id = '" . $params['schoolId'] . "'";
        if (!empty($params['studentId'])) {
            $sqlStudent .= " AND classstudents.student_id = " . $params['studentId'];
        }
        $studentResults = DB::select($sqlStudent);

        if (count($studentResults) > 0) {
            $listArr = array();
            $studentAnsArr = array();
            foreach ($studentResults as $ke => $va) {
                $listArr[] = $va->id;
                $sqlStudentAnswer = "select questions.id,questionanswer.attempt_status from task left join taskstudents on task.id=taskstudents.task_id and task.task_type = 'Test' left join questions on questions.question_set_id = task.question_set join questionanswer on questionanswer.question_id = questions.id where student_id = " . $va->id . " and questions.id is not null group by questions.id";
                $studentAnsResults = DB::select($sqlStudentAnswer);
                if (!empty($studentAnsResults)) {
                    foreach ($studentAnsResults as $k => $v) {
                        $studentAnsArr[$va->id][$v->id] = $v->attempt_status;
                    }
                }
            }
        }

        if (!empty($listArr)) {
            $sqlQuestion = "select questions.id,strands.strand,s1.strand as substrands, questions.quesnote as question From taskstudents left join task on task.id = taskstudents.task_id left join questions on questions.question_set_id = task.question_set  left join strands on questions.strands_id= strands.id left join  strands as s1 on questions.substrands_id= s1.id  where 1=1 ";


            if (!empty($params['studentId'])) {

                $sqlQuestion .=" AND taskstudents.student_id = " . $params['studentId'];
            } else {

                $sqlQuestion .=" AND taskstudents.student_id in(" . implode(",", $listArr) . ")";
            }

            if (!empty($params['questionId'])) {
                $sqlQuestion .=" AND questions.id = " . $params['questionId'];
            }

            $sqlQuestion .=" and questions.id is not null group by strands.strand , s1.strand";

            $questionResults = DB::select($sqlQuestion);

            $sqlQuestionAll = "select questions.id,strands.strand,s1.strand as substrands, questions.quesnote as question From taskstudents left join task on task.id = taskstudents.task_id left join questions on questions.question_set_id = task.question_set  left join strands on questions.strands_id= strands.id left join  strands as s1 on questions.substrands_id= s1.id  where 1=1  AND taskstudents.student_id in(" . implode(",", $listArr) . ") AND questions.id is not null group by strands.strand , s1.strand";

            $questionResultsAll = DB::select($sqlQuestionAll);
        }
        $resultArray = array();
        $resultArray['studentList'] = $studentResults;
        $resultArray['questionList'] = $questionResults;
        $resultArray['questionResultsAll'] = $questionResultsAll;
        $resultArray['studentAns'] = $studentAnsArr;

        return $resultArray;
    }
    public function getClassForCompletedTest($schoolID) {
        return StudentTestResult::where('school_id','=',$schoolID)->orderBy('class_id')->lists('class_name', 'class_id');
    }
    public function schoolReort($params){
        //get school classes
        $retrunData = array();
        $retrunData['classList'] = $classList = $this->getClassForCompletedTest($params['schoolId'])->toArray();
        //get school average score
        $schoolAvgScoreResult = StudentTestResult::select(DB::raw('AVG(test_attempt_avg_marks) AS schoolAvgScore'))->where(['school_id' => $params['schoolId'],'subject' => $params['subject']])->get()->first()->toArray();
        $retrunData['schoolAvgScore'] = $schoolAvgScore = !empty($schoolAvgScoreResult['schoolAvgScore']) ? round($schoolAvgScoreResult['schoolAvgScore']) : 0;
       
        //get class test average
        $classTestAvgResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore'),'class_id','question_set_id'])->where(['school_id' => $params['schoolId'],'subject' => $params['subject']])->groupBy(['class_id','question_set_id'])->get()->toArray();
        $allClassTestAvg = array();
        if(count($classTestAvgResult)){
            foreach($classTestAvgResult as $ctRow){
                foreach($classList as $classId => $className){
                    $allClassTestAvg[$ctRow['question_set_id']][$className] = $ctRow['class_id'] == $classId  ? round($ctRow['avgScore']) : (isset($allClassTestAvg[$ctRow['question_set_id']][$className]) ? $allClassTestAvg[$ctRow['question_set_id']][$className] : 0);
                }
            }
        }
        $testCompleted = 0;
        //get school completed test
        $schoolTestsResult = DB::select('SELECT taskassignments.student_num ,taskassignments.student_attempt_completed_num FROM `taskassignments` 
JOIN task ON taskassignments.task_id = task.id AND task_type = "'.TEST.'" AND `subject` = "'.$params['subject'].'" 
WHERE taskassignments.created_by IN (SELECT id FROM users WHERE school_id = '.$params['schoolId'].' AND user_type = "'.TEACHER.'")');
        if(count($schoolTestsResult)){
            foreach($schoolTestsResult as $stRow){
                $testCompleted = $stRow->student_num == $stRow->student_attempt_completed_num ? $testCompleted+1: $testCompleted;
            }
        }
        $retrunData['testCompleted'] = $testCompleted;
        $retrunData['allClassTestAvg'] = array_values($allClassTestAvg);
        
        //get class student average
        $classStudentAvgData = array();
        $allClassStudentAvg = array();
        $classStudentAvgResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore'),'class_id','student_id'])->where(['school_id' => $params['schoolId'],'subject' => $params['subject']])->groupBy(['class_id','student_id'])->get()->toArray();
        if(count($classStudentAvgResult)){
            foreach($classStudentAvgResult as $csRow){
                $classStudentAvgData[$csRow['class_id']][] = round($csRow['avgScore']);
            }
            $perRange = ['Less Than 20%', '21-50% ', '51-70%',' Greater than 70%'];
            foreach($classList as $classId => $className){
                $allClassStudentAvg[$classId]['Class/Progress'] = $className;
                foreach($perRange as $ik => $iv){
                    if(isset($classStudentAvgData[$classId])){
                        $cntSt = 0;
                        foreach ($classStudentAvgData[$classId] as $cstAvg){
                            switch ($ik):
                                case 0:
                                    $cntSt = $cstAvg <= 20 ? $cntSt+1 : $cntSt;
                                    break;
                                case 1:
                                    $cntSt = $cstAvg >= 21 && $cstAvg <= 50 ? $cntSt+1 : $cntSt;
                                    break;
                                case 2:
                                    $cntSt = $cstAvg >= 51 && $cstAvg <= 70 ? $cntSt+1 : $cntSt;
                                    break;
                                case 3:
                                    $cntSt = $cstAvg >= 71 ? $cntSt+1 : $cntSt;
                                    break;
                            endswitch;
                        }
                        $allClassStudentAvg[$classId][$iv] = $cntSt;
                    }else{
                        $allClassStudentAvg[$classId][$iv] = 0;
                    }
                }
            }
        }
        $retrunData['allClassStudentAvg'] = $allClassStudentAvg;
        $retrunData['pupilPerformanceData'] = array(
            'pupilsProgressResult' => array('title' => 'Pupil Progress','data' => ''),
            'hiegistAttainingPupilsResult' => array('title' => 'Highest Attaining Pupils','data' => ''),
            'lowestAttainingPupilsResult' => array('title' => 'Lowest Attaining Pupils','data' => '')
        );
       
        
        if($params['subject'] == MATH){
            $retrunData['pupilPerformanceData']['lowestAttainingPupilsResult']['data'] = $lowestAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time+p3_max_time)/(60*3) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','ASC')->limit(5)->get()->toArray();
            $retrunData['pupilPerformanceData']['hiegistAttainingPupilsResult']['data'] = $hiegistAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time+p3_max_time)/(60*3) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','DESC')->limit(5)->get()->toArray();
            //get Lowest Attaining Pupils
        $schoolStudentsLastTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore,test_attempt_avg_time/60 AS avgTime,(p1_max_time+p2_max_time+p3_max_time)/(60*3) AS avgTotalTime FROM student_test_result_data WHERE id IN (SELECT MIN(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' GROUP BY student_id)');
        $schoolStudentsFirstTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore FROM student_test_result_data WHERE id IN (SELECT MIN(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' GROUP BY student_id)');
        
        
        }else{
            $retrunData['pupilPerformanceData']['lowestAttainingPupilsResult']['data'] = $lowestAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time)/(60*2) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','ASC')->limit(5)->get()->toArray();
            $retrunData['pupilPerformanceData']['hiegistAttainingPupilsResult']['data'] = $hiegistAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time)/(60*2) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','DESC')->limit(5)->get()->toArray();
            
            $schoolStudentsLastTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore,test_attempt_avg_time/60 AS avgTime,(p1_max_time+p2_max_time)/(60*2) AS avgTotalTime FROM student_test_result_data WHERE id IN (SELECT MIN(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' GROUP BY student_id)');
            $schoolStudentsFirstTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore FROM student_test_result_data WHERE id IN (SELECT MIN(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' GROUP BY student_id)');
        }
        $pupilsProgressResult = array();
        $tempPrcDiff = array();
        if(count($schoolStudentsLastTestResult)){
            foreach($schoolStudentsLastTestResult as $key => $ssLtRow){
                $prcDiff = $ssLtRow->avgScore - $schoolStudentsFirstTestResult[$key]->avgScore;
                if($prcDiff >= 0){
                    $prcDiff = $ssLtRow->student_id == 185 ? 45 : 0;
                    array_push($tempPrcDiff,$prcDiff);
                    $ssLtRow->prcDiff = $prcDiff;
                    $pupilsProgressResult[] = (array) $ssLtRow;
                }
                
            }
            array_multisort($tempPrcDiff, SORT_DESC, $pupilsProgressResult);
        }
        
        
        //Pupil Progress
        $retrunData['pupilPerformanceData']['pupilsProgressResult']['data'] = array_slice($pupilsProgressResult, 0,5);
        
        return $retrunData;
    }
    
    public function schoolReort_old($params) {
        $returnResultArray = array();
        // all class of a school
        $sqlClassList = "select * from schoolclasses where created_by = " . $params['schoolId'] . " and status!='Deleted'";
        $classList = DB::select($sqlClassList);

        $resultArray = array();
        $topstudent = array();
        $topTime = array();
        $studentNameArray = array();
        $mostImporvedStudent = array();

        $hittingTargetStudentClasswise = array();

        if (!empty($classList)) {
            foreach ($classList as $k => $v) {
                $resultArray[$v->id] = array();
                // all student of a class
                $sqlClassStudent = "select student_id from classstudents where class_id =" . $v->id;
                $studentList = DB::select($sqlClassStudent);
                if (!empty($studentList)) {
                    foreach ($studentList as $ke => $ve) {


                        //all task for a student
                        $studentTestSql = "select task.id,taskstudents.student_id
                                            from taskstudents 
                                            left join task on task.id = taskstudents.task_id and taskstudents.student_id = " . $ve->student_id . "
                                            where task.task_type ='Test' and subject ='" . $params['subject'] . "'";
                        $studentTestResult = DB::select($studentTestSql);
                        if (!empty($studentTestResult)) {
                            foreach ($studentTestResult as $key => $val) {
                                //all test detail for a student
                                if (empty($params['setId'])) {
                                    $sqlScoreSql = " select users.id as studentId,users.first_name,users.last_name, ((student_test_attempt.attempt_completed - 1)*student_test_attempt.total_marks) as totalMarks, student_test_attempt.total_marks_obtain,student_test_attempt.total_time,student_test_attempt.total_time_spent,student_test_attempt.task_id from student_test_attempt left join users on users.id = student_test_attempt.student_id where student_test_attempt.student_id = " . $val->student_id . " and  student_test_attempt.task_id= " . $val->id . " AND student_test_attempt.status='Completed'";
                                } else {
                                    $sqlScoreSql = "select users.id as studentId,users.first_name,users.last_name,((student_test_attempt.attempt_completed - 1)*student_test_attempt.total_marks) as totalMarks, student_test_attempt.total_marks_obtain,student_test_attempt.total_time,student_test_attempt.total_time_spent,student_test_attempt.task_id from student_test_attempt left join users on users.id = student_test_attempt.student_id join task on task.id = student_test_attempt.task_id ";
                                    if (!empty($params['paperId'])) {
                                        $sqlScoreSql .=" join studenttest on studenttest.test_attempt_id  =student_test_attempt.id and studenttest.paper_id = " . $params['paperId'] . " and studenttest.attempt = '1'";
                                    }
                                    $sqlScoreSql .=" where student_test_attempt.student_id = " . $val->student_id . " and student_test_attempt.task_id= " . $val->id . "  AND student_test_attempt.status='Completed' and task.question_set = " . $params['setId'];
                                    //echo "<br>";
                                }


                                $sqlScoreDetail = DB::select($sqlScoreSql);
                                // avarage marks of each studen
                                // avarage timeline of each student
                                if (!empty($sqlScoreDetail)) {
                                    $avgMarksOfStudent = 0;
                                    $avgTimesOfStudent = 0;

                                    foreach ($sqlScoreDetail as $x => $y) {
                                        if (empty($studentNameArray[$ve->student_id])) {
                                            $studentNameArray[$ve->student_id] = $y->first_name . " " . $y->last_name;
                                        }
                                        if (!empty($y->totalMarks)) {
                                            $avgMarksOfStudent = ($y->total_marks_obtain * 100) / $y->totalMarks;
                                            //$avgTimesOfStudent = ($y->total_time_spent * 100) / $y->total_time;
                                            $avgTimesOfStudent = $y->total_time_spent;
                                        }
                                        if (!empty($topstudent[$ve->student_id])) {
                                            $topstudent[$ve->student_id] = ($topstudent[$ve->student_id] + ($y->total_marks_obtain * 100) / $y->totalMarks) / 2;
                                        } else {
                                            $topstudent[$ve->student_id] = $avgMarksOfStudent;
                                        }
                                        if (!empty($topTime[$ve->student_id])) {
                                            $topTime[$ve->student_id] = ($topTime[$ve->student_id] + $y->total_time_spent) / 2;
                                            // $topTime[$ve->student_id] = ($topTime[$ve->student_id] + ($y->total_time_spent * 100) / $y->total_time) / 2;
                                        } else {
                                            $topTime[$ve->student_id] = $avgTimesOfStudent;
                                        }
                                        if (!empty($mostImporvedStudent[$ve->student_id])) {
                                            if ($mostImporvedStudent[$ve->student_id]['min'] > $avgMarksOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['min'] = $avgMarksOfStudent;
                                            }
                                            if ($mostImporvedStudent[$ve->student_id]['max'] < $avgMarksOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['max'] = $avgMarksOfStudent;
                                            }
                                            if ($mostImporvedStudent[$ve->student_id]['minTimeLine'] > $avgTimesOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['minTimeLine'] = $avgTimesOfStudent;
                                            }
                                            if ($mostImporvedStudent[$ve->student_id]['maxTimeLine'] < $avgTimesOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['maxTimeLine'] = $avgTimesOfStudent;
                                            }
                                        } else {
                                            $mostImporvedStudent[$ve->student_id] = array('min' => $avgMarksOfStudent,
                                                'max' => $avgMarksOfStudent,
                                                'minTimeLine' => $avgTimesOfStudent,
                                                'maxTimeLine' => $avgTimesOfStudent
                                            );
                                        }
                                        $hittingTargetStudentClasswise[$v->id][$ve->student_id] = $topstudent[$ve->student_id];
                                    }
                                    $resultArray[$v->id][$ve->student_id]['score'][] = $sqlScoreDetail[0];
                                }
                            }
                        }
                    }
                }
            }
        }

        //asd($resultArray);
        //Lets assume hitting target is 50%
        $classHittingCount = array();
        $schoolTestResult = array();
        $classNameArray = array();
        $arraySchoolOverViewTest = array();
        $counterArray = array();
        $schoolOverViewCounter = array();

        foreach ($classList as $k => $v) {
            //  $counter = 1;
            $classNameArray[$v->id] = $v->class_name;
            if (!empty($resultArray[$v->id])) {
                foreach ($resultArray[$v->id] as $key => $val) {
                    if (!empty($val)) {
                        // $index  = 0; 
                        foreach ($val as $a => $b) {
                            if (!empty($b)) {
                                foreach ($b as $c => $d) {  //$counter++;
                                    if (!empty($schoolTestResult[$v->id][$c])) {
                                        $schoolTestResult[$v->id][$c] = ($schoolTestResult[$v->id][$c] + ($d->total_marks_obtain * 100) / $d->totalMarks);
                                        $counterArray[$v->id][$c] = $counterArray[$v->id][$c] + 1;
                                    } else {
                                        if (!empty($d->totalMarks)) {
                                            $schoolTestResult[$v->id][$c] = ($d->total_marks_obtain * 100) / $d->totalMarks;
                                            $counterArray[$v->id][$c] = 1;
                                        }
                                    }
                                    if (!empty($arraySchoolOverViewTest[$v->id]['totalMark'])) {
                                        $arraySchoolOverViewTest[$v->id]['totalMark'] = ($arraySchoolOverViewTest[$v->id]['totalMark'] + ($d->total_marks_obtain * 100) / $d->totalMarks);
                                        $arraySchoolOverViewTest[$v->id]['totalTime'] = ($arraySchoolOverViewTest[$v->id]['totalTime'] + $d->total_time_spent);
                                        $schoolOverViewCounter[$v->id]['totalMark'] = $schoolOverViewCounter[$v->id]['totalMark'] + 1;
                                    } else {
                                        if (!empty($d->totalMarks)) {
                                            $arraySchoolOverViewTest[$v->id]['totalMark'] = ($d->total_marks_obtain * 100) / $d->totalMarks;
                                            $arraySchoolOverViewTest[$v->id]['totalTime'] = $d->total_time_spent;
                                            $schoolOverViewCounter[$v->id]['totalMark'] = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $classHittingCount[$v->id] = 0;
            if (!empty($hittingTargetStudentClasswise[$v->id])) {
                $hittingTargetTraficLight = array('red' => 0, 'orange' => 0, 'green' => 0, 'blue' => 0,);
                foreach ($hittingTargetStudentClasswise[$v->id] as $r => $s) {
                    if ($s <= 20) {
                        $hittingTargetTraficLight['red'] = $hittingTargetTraficLight['red'] + 1;
                    } else if ($s > 21 && $s <= 50) {
                        $hittingTargetTraficLight['orange'] = $hittingTargetTraficLight['orange'] + 1;
                    } elseif ($s > 50 && $s < 70) {
                        $hittingTargetTraficLight['green'] = $hittingTargetTraficLight['green'] + 1;
                    } else {
                        $hittingTargetTraficLight['blue'] = $hittingTargetTraficLight['blue'] + 1;
                    }

                    // if ($s > 50) {
                    // $classHittingCount[$v->id] = $classHittingCount[$v->id] + 1;
                    // }
                }
                $classHittingCount[$v->id] = $hittingTargetTraficLight;
            }
        }


        // calculate avarage
        foreach ($schoolTestResult as $avg => $cou) {
            foreach ($cou as $av => $co) {
                $schoolTestResult[$avg][$av] = $schoolTestResult[$avg][$av] / $counterArray[$avg][$av];
            }
        }

        foreach ($arraySchoolOverViewTest as $keys => $vals) {

            $arraySchoolOverViewTest[$keys]['totalMark'] = $arraySchoolOverViewTest[$keys]['totalMark'] / $schoolOverViewCounter[$keys]['totalMark'];
            $arraySchoolOverViewTest[$keys]['totalTime'] = $arraySchoolOverViewTest[$keys]['totalTime'] / $schoolOverViewCounter[$keys]['totalMark'];
        }

        $mostImprovedStudentList = array();
        $mostImprovedStudentTimeLine = array();

        if (!empty($mostImporvedStudent)) {
            foreach ($mostImporvedStudent as $key => $val) {
                if ($val['max'] == $val['min']) {
                    $mostImprovedStudentList[$key] = $val['max'];
                    $mostImprovedStudentTimeLine[$key] = $val['maxTimeLine'];
                } else {
                    $mostImprovedStudentList[$key] = $val['max'] - $val['min'];
                    $mostImprovedStudentTimeLine[$key] = $val['maxTimeLine'] - $val['minTimeLine'];
                }
            }
        }
        // get set name 

        $satArray = array();
        //if(!empty($studentNameArray)){
        //    $studentKey = array_keys($studentNameArray);    
        //    $studentKey = implode(",",$studentKey);
        //$sqlSet = "select qs.id,qs.set_name from task as t join taskstudents as ts on ts.task_id = t.id  left join questionsets as qs on qs.id = t.question_set where ts.student_id in ($studentKey) and t.task_type = 'Test' and t.subject = '".$params['subject']."' group by qs.id";
        $sqlSet = "select qs.id,qs.set_name from task as t join taskstudents as ts on ts.task_id = t.id  left join questionsets as qs on qs.id = t.question_set where t.task_type = 'Test' and t.subject = '" . $params['subject'] . "' group by qs.id";
        $satArray = DB::select($sqlSet);

        //}    
        /* $satArray = array();
          if (!empty($studentNameArray)) {
          $studentKey = array_keys($studentNameArray);
          $studentKey = implode(",", $studentKey);
          $sqlSet = "select qs.id,qs.set_name from task as t join taskstudents as ts on ts.task_id = t.id  left join questionsets as qs on qs.id = t.question_set where ts.student_id in ($studentKey) and t.task_type = 'Test' and t.subject = '" . $params['subject'] . "' group by qs.id";
          $satArray = DB::select($sqlSet);
          } */

        $returnResultArray['classNameArray'] = $classNameArray;
        $returnResultArray['studentNameArray'] = $studentNameArray;
        $returnResultArray['studentTestTime'] = $topTime;
        $returnResultArray['studentMaxMarks'] = $topstudent;
        $returnResultArray['mostImprovedStu'] = $mostImprovedStudentList;
        $returnResultArray['mostImprovedStuTime'] = $mostImprovedStudentTimeLine;
        $returnResultArray['classHittingCount'] = $classHittingCount;
        $returnResultArray['graph'] = $schoolTestResult;
        $returnResultArray['arraySchoolOverViewTest'] = $arraySchoolOverViewTest;
        $returnResultArray['satArray'] = $satArray;
        return $returnResultArray;
    }

    public function schoolTopicReport($params) {
        $returnResultArray = array();
        
        $sqlStrand = "select id,strand from strands where subject = '" . $params['subject'] . "' and status!='Deleted' and parent_id = 0";
        $strandList = DB::select($sqlStrand);

        if (!empty($params['strand'])) {
            $strand = $params['strand'];
        } else {
            $strand = $strandList[0]->id;
        }

        $sqlSubStrand = "select id,strand from strands where parent_id=" . $strand . " and status!='Deleted'";
        $subStrandList = DB::select($sqlSubStrand);

        if (!empty($params['substrand'])) {
            $subStrand = $params['substrand'];
        } else {
            $subStrand = $subStrandList[0]->id;
        }
        
        //echo $sql = "select class_name, SUM(avg_marks) AS avg_marks, SUM(avg_time) AS avg_time, COUNT(id) AS total_record_cnt from student_revision_result_data where school_id= " . $params['schoolId'] . " and strand_id=".$strand." and substrand_id=".$subStrand." and subject = '" . $params['subject'] . "'  GROUP BY class_id";
        $sql = "SELECT schoolclasses.id, schoolclasses.class_name,AVG(avg_marks) AS avg_marks, AVG(avg_time) AS avg_time,AVG(avg_total_time) AS avg_total_time, COUNT(student_revision_result_data.id) AS total_record_cnt 
        FROM schoolclasses 
        LEFT JOIN student_revision_result_data ON schoolclasses.id=student_revision_result_data.class_id AND strand_id=".$strand." AND substrand_id=".$subStrand." and subject='" . $params['subject'] . "' 
        WHERE schoolclasses.created_by=" . $params['schoolId'] . " GROUP BY schoolclasses.id";        
        
        $sqlResult = DB::select($sql);  
        $returnResultArray['strandId'] = $strand;
        $returnResultArray['substrandId'] = $subStrand;
        $returnResultArray['strands'] = $strandList;
        $returnResultArray['substrands'] = $subStrandList;
        $returnResultArray['topicdetail'] = $sqlResult;        
        
        return $returnResultArray;
        
        /*$sqlClassList = "select id,class_name from schoolclasses where created_by = " . $params['schoolId'] . " and status!='Deleted'";
        $classList = DB::select($sqlClassList);

        $sqlStrand = "select id,strand from strands where subject = '" . $params['subject'] . "' and status!='Deleted' and parent_id = 0";
        $strandList = DB::select($sqlStrand);

        if (!empty($params['strand'])) {
            $strand = $params['strand'];
        } else {
            $strand = $strandList[0]->id;
        }

        $sqlSubStrand = "select id,strand from strands where parent_id=" . $strand . " and status!='Deleted'";
        $subStrandList = DB::select($sqlSubStrand);

        if (!empty($params['substrand'])) {
            $subStrand = $params['substrand'];
        } else {
            $subStrand = $subStrandList[0]->id;
        }


        $sqlTopicSql = "select schoolclasses.id as classId,schoolclasses.class_name,task.id as taskId,studentrevision.student_id as studentId,classstudents.school_id, studentrevision.total_marks,studentrevision.mark_obtain,studentrevision.quesmaxtime,(studentrevision.quesmaxtime-studentrevision.remainingtime) as timeUsed,task.strand,task.substrand from schoolclasses left join classstudents on classstudents.class_id = schoolclasses.id and classstudents.school_id = '" . $params['schoolId'] . "' left join studentrevision on studentrevision.student_id = classstudents.student_id left join task  on task.id=studentrevision.task_id where task.task_type = 'Revision' and task.strand = " . $strand . " and task.substrand = " . $subStrand . " and task.subject='" . $params['subject'] . "'";
        $sqlTopicDetail = DB::select($sqlTopicSql);
        $returnResultArray['strandId'] = $strand;
        $returnResultArray['substrandId'] = $subStrand;
        $returnResultArray['class'] = $classList;
        $returnResultArray['strands'] = $strandList;
        $returnResultArray['substrands'] = $subStrandList;
        $returnResultArray['topicdetail'] = $sqlTopicDetail;
        return $returnResultArray;*/
    }

    public function classTestReport_old($params) {
      //  asd($params, 0);
        $returnResultArray = array();
        // all class of a school
        $sqlClassList = "select * from schoolclasses where id = " . $params['schoolId'] . " and status!='Deleted'";
        $classList = DB::select($sqlClassList);

        $resultArray = array();
        $topstudent = array();
        $topTime = array();
        $studentNameArray = array();
        $mostImporvedStudent = array();

        $hittingTargetStudentClasswise = array();

        if (!empty($classList)) {
            foreach ($classList as $k => $v) {
                $class_name = $v->class_name;
                $resultArray[$v->id] = array();
                // all student of a class
                $sqlClassStudent = "select student_id from classstudents where class_id =" . $v->id;
                $studentList = DB::select($sqlClassStudent);
              //  asd($studentList, 0);
                $totalMarksArray = array();
                if (!empty($studentList)) {
                    foreach ($studentList as $ke => $ve) {
                        //all task for a student
                        if (empty($params['setId'])) {
                            $studentTestSql = "select task.id,taskstudents.student_id,task.question_set
                                            from taskstudents 
                                            left join task on task.id = taskstudents.task_id and taskstudents.student_id = " . $ve->student_id . "
                                            where task.task_type ='Test' and subject ='" . $params['subject'] . "'";
                        }
                        else {
                            $studentTestSql = "select task.id,taskstudents.student_id,task.question_set
                                            from taskstudents 
                                            left join task on task.id = taskstudents.task_id and taskstudents.student_id = " . $ve->student_id . "
                                            where task.task_type ='Test' and subject ='" . $params['subject'] . "' and task.question_set = " . $params['setId'];
                        }
                        $studentTestResult = DB::select($studentTestSql);
                      //  asd($studentTestResult, 0);

                        foreach ($studentTestResult as $key => $val) {
                            $sqlScoreSql = " select studenttest.mark_obtain,studenttest.total_marks,studenttest.quesmaxtime,studenttest.remainingtime,
                                studenttest.status, studenttest.paper_id, studenttest.attempt from student_test_attempt left 
                                join studenttest on studenttest.test_attempt_id = student_test_attempt.id and studenttest.status='Completed'
                                join users on users.id = student_test_attempt.student_id where student_test_attempt.student_id = " . $val->student_id . " and  student_test_attempt.task_id= " . $val->id . " AND student_test_attempt.status='Completed'";
                            $sqlScoreDetail = DB::select($sqlScoreSql);
                            // asd($sqlScoreDetail);
                            $totalMarks = 0;

                            foreach ($sqlScoreDetail as $k => $v) {
                                $totalMarks+=number_format(($v->mark_obtain / $v->total_marks) * 100, 2, '.', '');
                            }
                            $totalMarksArray[$val->id]['marks'][] = number_format($totalMarks / count($sqlScoreDetail), 2, '.', '');
                            $totalMarksArray[$val->id]['time'][] = '100';
                        }
                    }
                }
            }
        }
        $totalMarksArray['class_name'] = $class_name;
        return $totalMarksArray;
    }
    public function classTestReport($params) { 
                // get set name 
        $sql = "SELECT users.id as user_id,users.first_name, users.last_name,users.ks2_maths_baseline_value,users.ks2_english_baseline_value, 
                    student_test_result_data.class_id,
                    student_test_result_data.class_name,
                    student_test_result_data.question_set_id,
                    student_test_result_data.set_name,
                    student_test_result_data.assignment_num,
                    student_test_result_data.attempt_count,
                    AVG(student_test_result_data.att1_p1) as att1_p1,
                    AVG(student_test_result_data.att1_p2) as att1_p2,
                    AVG(student_test_result_data.att1_p3) as att1_p3,
                    AVG(student_test_result_data.att1_avg_marks) as att1_avg_marks,
                    AVG(student_test_result_data.att2_p1) as att2_p1,
                    AVG(student_test_result_data.att2_p2) as att2_p2,
                    AVG(student_test_result_data.att2_p3) as att2_p3,
                    AVG(student_test_result_data.att2_avg_marks) as att2_avg_marks,
                    AVG(student_test_result_data.att3_p1) as att3_p1,
                    AVG(student_test_result_data.att3_p2) as att3_p2,
                    AVG(student_test_result_data.att3_p3) as att3_p3,
                    AVG(student_test_result_data.att3_avg_marks) as att3_avg_marks,
                    AVG(student_test_result_data.test_attempt_avg_marks) as test_attempt_avg_marks,
                    AVG(student_test_result_data.p1_avg) as p1_avg,
                    AVG(student_test_result_data.p2_avg) as p2_avg,
                    AVG(student_test_result_data.p3_avg) as p3_avg,
                    AVG(student_test_result_data.att1_p1_time) as att1_p1_time,
                    AVG(student_test_result_data.att1_p2_time) as att1_p2_time,
                    AVG(student_test_result_data.att1_p3_time) as att1_p3_time,
                    AVG(student_test_result_data.att1_avg_time) as att1_avg_time,
                    AVG(student_test_result_data.att2_p1_time) as att2_p1_time,
                    AVG(student_test_result_data.att2_p2_time) as att2_p2_time,
                    AVG(student_test_result_data.att2_p3_time) as att2_p3_time,
                    AVG(student_test_result_data.att2_avg_time) as att2_avg_time,
                    AVG(student_test_result_data.att3_p1_time) as att3_p1_time,
                    AVG(student_test_result_data.att3_p2_time) as att3_p2_time,
                    AVG(student_test_result_data.att3_p3_time) as att3_p3_time,
                    AVG(student_test_result_data.att3_avg_time) as att3_avg_time,
                    AVG(student_test_result_data.test_attempt_avg_time) as test_attempt_avg_time,
                    AVG(student_test_result_data.p1_avg_time) as p1_avg_time,
                    AVG(student_test_result_data.p2_avg_time) as p2_avg_time,
                    AVG(student_test_result_data.p3_avg_time) as p3_avg_time,
                    AVG(student_test_result_data.p1_max_time) as p1_max_time,
                    AVG(student_test_result_data.p2_max_time) as p2_max_time,
                    AVG(student_test_result_data.p3_max_time) as p3_max_time,
                    count(classstudents.student_id) as student_count
        FROM classstudents 
        INNER JOIN users ON users.id = classstudents.student_id";
        $sql.= " LEFT JOIN student_test_result_data ON classstudents.student_id = student_test_result_data.student_id AND SUBJECT = '" . $params['subject'] . "' ";
        if(!empty($params['setId'])) {
            $sql.= " and student_test_result_data.question_set_id='".$params['setId']."'";        
        }
        $sql.= " WHERE classstudents.class_id= " . $params['classId'];

        
        $sql.= " GROUP BY classstudents.student_id ORDER BY users.first_name, users.last_name ASC";

        $sqlResult = DB::select($sql); 
        //asd($sqlResult);
        
        $studentNameArray = array();
        if(!empty($sqlResult)) {
            foreach($sqlResult as $key=>$val){
                $studentNameArray[] = $val->user_id;
            }
        }
        $satArray = array();
        if (!empty($studentNameArray)) {
            //$studentKey = array_keys($studentNameArray);
            $studentKey = implode(",", $studentNameArray);
            $sqlSet = "select qs.id,qs.set_name from task as t join taskstudents as ts on ts.task_id = t.id  left join questionsets as qs on qs.id = t.question_set where ts.student_id in ($studentKey) and t.task_type = 'Test' and t.subject = '" . $params['subject'] . "' group by qs.id";
            $satArray = DB::select($sqlSet);
        }
        //asd($satArray);
        //$returnResultArray['classNameArray'] = $classNameArray;
        //$returnResultArray['studentNameArray'] = $studentNameArray;
        //$returnResultArray['studentTestTime'] = $topTime;
        //$returnResultArray['studentMaxMarks'] = $topstudent;
        //$returnResultArray['mostImprovedStu'] = $mostImprovedStudentList;
        //$returnResultArray['mostImprovedStuTime'] = $mostImprovedStudentTimeLine;
        //$returnResultArray['classHittingCount'] = $classHittingCount;
        //$returnResultArray['graph'] = $schoolTestResult;
        //$returnResultArray['arraySchoolOverViewTest'] = $arraySchoolOverViewTest;
        $returnResultArray['setId'] = $params['setId'];
        $returnResultArray['paper'] = $params['paperId'];
        $returnResultArray['satArray'] = $satArray;
        $returnResultArray['topicdetail'] = $sqlResult;
        return $returnResultArray;        
        
    }
    public function schoolTestReport($params) {
        // get set name 
        $sql = "SELECT  student_test_result_data.student_id, schoolclasses.id, schoolclasses.class_name,AVG(att1_avg_marks) AS att1_avg_marks,AVG(att2_avg_marks) AS att2_avg_marks,AVG(att3_avg_marks) AS att3_avg_marks,AVG(test_attempt_avg_marks) AS test_attempt_avg_marks,
        AVG(p1_avg) AS p1_avg, AVG(p2_avg) AS p2_avg, AVG(p3_avg) AS p3_avg, AVG(att1_avg_time) AS att1_avg_time,AVG(att2_avg_time) AS att2_avg_time, AVG(att3_avg_time) AS att3_avg_time, AVG(test_attempt_avg_time) AS test_attempt_avg_time, AVG(p1_avg_time) AS p1_avg_time, AVG(p2_avg_time) AS p2_avg_time, AVG(p3_avg_time) AS p3_avg_time, AVG(p1_max_time) AS p1_max_time, AVG(p2_max_time) AS p2_max_time, AVG(p3_max_time) AS p3_max_time
        FROM schoolclasses 
        LEFT JOIN student_test_result_data ON schoolclasses.id=student_test_result_data.class_id AND SUBJECT='" . $params['subject'] . "'";
        if(!empty($params['setId'])) {
            $sql.= " and student_test_result_data.question_set_id='".$params['setId']."'";        
        }        
        $sql.= " WHERE schoolclasses.created_by=" . $params['schoolId'] . " GROUP BY schoolclasses.id";        
        
        
        /*$sql = "SELECT users.id as user_id,users.first_name, users.last_name,users.ks2_maths_baseline_value,users.ks2_english_baseline_value, student_test_result_data.*
        FROM classstudents 
        INNER JOIN users ON users.id = classstudents.student_id";
        $sql.= " LEFT JOIN student_test_result_data ON classstudents.student_id = student_test_result_data.student_id AND SUBJECT = '" . $params['subject'] . "' ";
        if(!empty($params['setId'])) {
            $sql.= " and student_test_result_data.question_set_id='".$params['setId']."'";        
        }
        $sql.= " WHERE classstudents.class_id= " . $params['schoolId']; 
        $sql.= " ORDER BY users.first_name, users.last_name ASC
        */

        $sqlResult = DB::select($sql); 
        //asd($sqlResult,0);
        
        $satArray = array();
        $sqlSet = "select qs.id,qs.set_name from task as t join taskstudents as ts on ts.task_id = t.id  left join questionsets as qs on qs.id = t.question_set where t.task_type = 'Test' and t.subject = '" . $params['subject'] . "' group by qs.id";
        $satArray = DB::select($sqlSet);        
        
        //asd($satArray);
        //$returnResultArray['classNameArray'] = $classNameArray;
        //$returnResultArray['studentNameArray'] = $studentNameArray;
        //$returnResultArray['studentTestTime'] = $topTime;
        //$returnResultArray['studentMaxMarks'] = $topstudent;
        //$returnResultArray['mostImprovedStu'] = $mostImprovedStudentList;
        //$returnResultArray['mostImprovedStuTime'] = $mostImprovedStudentTimeLine;
        //$returnResultArray['classHittingCount'] = $classHittingCount;
        //$returnResultArray['graph'] = $schoolTestResult;
        //$returnResultArray['arraySchoolOverViewTest'] = $arraySchoolOverViewTest;
        $returnResultArray['setId'] = $params['setId'];
        $returnResultArray['paper'] = $params['paperId'];
        $returnResultArray['satArray'] = $satArray;
        $returnResultArray['topicdetail'] = $sqlResult;
        return $returnResultArray;        
        
    }
    public function classReport($params) {
        $returnResultArray = array();
        // all class of a school
        $retrunData['classList'] = $classList = Schoolclass::where('id','=',$params['classId'])->lists('class_name', 'id')->toArray();
        
        //get class test average
        $classTestAvgResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore'),'class_id','question_set_id'])->where(['school_id' => $params['schoolId'],'class_id' => $params['classId'],'subject' => $params['subject']])->groupBy(['class_id','question_set_id'])->get()->toArray();
        $allClassTestAvg = array();
        if(count($classTestAvgResult)){
            foreach($classTestAvgResult as $ctRow){
                foreach($classList as $classId => $className){
                    $allClassTestAvg[$ctRow['question_set_id']][$className] = $ctRow['class_id'] == $classId  ? round($ctRow['avgScore']) : (isset($allClassTestAvg[$ctRow['question_set_id']][$classId]) ? $allClassTestAvg[$ctRow['question_set_id']][$classId] : 0);
                }
            }
        }
        $retrunData['allClassTestAvg'] = array_values($allClassTestAvg);
        //get class student average
        $classStudentAvgData = array();
        $allClassStudentAvg = array();
        $classStudentAvgResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore'),'class_id','student_id'])->where(['school_id' => $params['schoolId'],'class_id' => $params['classId'],'subject' => $params['subject']])->groupBy(['class_id','student_id'])->get()->toArray();
        if(count($classStudentAvgResult)){
            foreach($classStudentAvgResult as $csRow){
                $classStudentAvgData[$csRow['class_id']][] = round($csRow['avgScore']);
            }
            $perRange = ['Less Than 20%', '21-50% ', '51-70%',' Greater than 70%'];
            foreach($classList as $classId => $className){
                $allClassStudentAvg[$classId]['Class/Progress'] = $className;
                foreach($perRange as $ik => $iv){
                    if(isset($classStudentAvgData[$classId])){
                        $cntSt = 0;
                        foreach ($classStudentAvgData[$classId] as $cstAvg){
                            switch ($ik):
                                case 1:
                                    $cntSt = $cstAvg <= 20 ? $cntSt+1 : $cntSt;
                                    break;
                                case 2:
                                    $cntSt = $cstAvg >= 21 && $cstAvg <= 50 ? $cntSt+1 : $cntSt;
                                    break;
                                case 3:
                                    $cntSt = $cstAvg >= 51 && $cstAvg <= 70 ? $cntSt+1 : $cntSt;
                                    break;
                                case 4:
                                    $cntSt = $cstAvg >= 71 ? $cntSt+1 : $cntSt;
                                    break;
                            endswitch;
                        }
                        $allClassStudentAvg[$classId][$iv] = $cntSt;
                    }else{
                        $allClassStudentAvg[$classId][$iv] = 0;
                    }
                }
            }
        }
        $retrunData['allClassStudentAvg'] = $allClassStudentAvg;
        $retrunData['pupilPerformanceData'] = array(
            'pupilsProgressResult' => array('title' => 'Pupil Progress','data' => ''),
            'hiegistAttainingPupilsResult' => array('title' => 'Highest Attaining Pupils','data' => ''),
            'lowestAttainingPupilsResult' => array('title' => 'Lowest Attaining Pupils','data' => '')
        );
       
        
        if($params['subject'] == MATH){
            $retrunData['pupilPerformanceData']['lowestAttainingPupilsResult']['data'] = $lowestAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time+p3_max_time)/(60*3) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'class_id' => $params['classId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','ASC')->limit(5)->get()->toArray();
            $retrunData['pupilPerformanceData']['hiegistAttainingPupilsResult']['data'] = $hiegistAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time+p3_max_time)/(60*3) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'class_id' => $params['classId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','DESC')->limit(5)->get()->toArray();
            //get Lowest Attaining Pupils
        $schoolStudentsLastTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore,test_attempt_avg_time/60 AS avgTime,(p1_max_time+p2_max_time+p3_max_time)/(60*3) AS avgTotalTime FROM student_test_result_data WHERE id IN (SELECT MAX(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' AND class_id = '.$params['classId'].' GROUP BY student_id)');
        $schoolStudentsFirstTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore FROM student_test_result_data WHERE id IN (SELECT MIN(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' AND class_id = '.$params['classId'].' GROUP BY student_id)');
        
        
        }else{
            $retrunData['pupilPerformanceData']['lowestAttainingPupilsResult']['data'] = $lowestAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time)/(60*2) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'class_id' => $params['classId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','ASC')->limit(5)->get()->toArray();
            $retrunData['pupilPerformanceData']['hiegistAttainingPupilsResult']['data'] = $hiegistAttainingPupilsResult = StudentTestResult::select([DB::raw('AVG(test_attempt_avg_marks) AS avgScore,AVG(test_attempt_avg_time)/60 AS avgTime,AVG(p1_max_time+p2_max_time)/(60*2) AS avgTotalTime'),'student_id','student_name'])->where(['school_id' => $params['schoolId'],'class_id' => $params['classId'],'subject' => $params['subject']])->groupBy('student_id')->orderBy('avgScore','DESC')->limit(5)->get()->toArray();
            
            $schoolStudentsLastTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore,test_attempt_avg_time/60 AS avgTime,(p1_max_time+p2_max_time)/(60*2) AS avgTotalTime FROM student_test_result_data WHERE id IN (SELECT MAX(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' AND class_id = '.$params['classId'].' GROUP BY student_id)');
            $schoolStudentsFirstTestResult = DB::select('SELECT student_id,student_name,test_attempt_avg_marks AS avgScore FROM student_test_result_data WHERE id IN (SELECT MIN(id) FROM student_test_result_data WHERE `subject` = "'.$params['subject'].'" AND school_id = '.$params['schoolId'].' AND class_id = '.$params['classId'].' GROUP BY student_id)');
        }
        $pupilsProgressResult = array();
        $tempPrcDiff = array();
        if(count($schoolStudentsLastTestResult)){
            foreach($schoolStudentsLastTestResult as $key => $ssLtRow){
                $prcDiff = $ssLtRow->avgScore - $schoolStudentsFirstTestResult[$key]->avgScore;
                if($prcDiff >= 0){
                    array_push($tempPrcDiff,$prcDiff);
                    $ssLtRow->prcDiff = $prcDiff;
                    $pupilsProgressResult[] = (array) $ssLtRow;
                }
                
            }
            array_multisort($tempPrcDiff, SORT_DESC, $pupilsProgressResult);
        }
        
        
        //Pupil Progress
        $retrunData['pupilPerformanceData']['pupilsProgressResult']['data'] = array_slice($pupilsProgressResult, 0,5);
        return $retrunData;
    }
    public function classReportOld($params) {
        $returnResultArray = array();
        // all class of a school
        $sqlClassList = "select * from schoolclasses where id = " . $params['schoolId'] . " and status!='Deleted'";
        $classList = DB::select($sqlClassList);

        $resultArray = array();
        $topstudent = array();
        $topTime = array();
        $studentNameArray = array();
        $mostImporvedStudent = array();

        $hittingTargetStudentClasswise = array();

        if (!empty($classList)) {
            foreach ($classList as $k => $v) {
                $resultArray[$v->id] = array();
                // all student of a class
                $sqlClassStudent = "select student_id from classstudents where class_id =" . $v->id;
                $studentList = DB::select($sqlClassStudent);
                if (!empty($studentList)) {
                    foreach ($studentList as $ke => $ve) {


                        //all task for a student
                        $studentTestSql = "select task.id,taskstudents.student_id
                                            from taskstudents 
                                            left join task on task.id = taskstudents.task_id and taskstudents.student_id = " . $ve->student_id . "
                                            where task.task_type ='Test' and subject ='" . $params['subject'] . "'";
                        $studentTestResult = DB::select($studentTestSql);
                        if (!empty($studentTestResult)) {
                            foreach ($studentTestResult as $key => $val) {
                                //all test detail for a student
                                if (empty($params['setId'])) {
                                    $sqlScoreSql = " select users.id as studentId,users.first_name,users.last_name, ((student_test_attempt.attempt_completed - 1)*student_test_attempt.total_marks) as totalMarks, student_test_attempt.total_marks_obtain,student_test_attempt.total_time,student_test_attempt.total_time_spent,student_test_attempt.task_id from student_test_attempt left join users on users.id = student_test_attempt.student_id where student_test_attempt.student_id = " . $val->student_id . " and  student_test_attempt.task_id= " . $val->id . " AND student_test_attempt.status='Completed'";
                                } else {
                                    $sqlScoreSql = "select users.id as studentId,users.first_name,users.last_name,((student_test_attempt.attempt_completed - 1)*student_test_attempt.total_marks) as totalMarks, student_test_attempt.total_marks_obtain,student_test_attempt.total_time,student_test_attempt.total_time_spent,student_test_attempt.task_id from student_test_attempt left join users on users.id = student_test_attempt.student_id join task on task.id = student_test_attempt.task_id ";
                                    if (!empty($params['paperId'])) {
                                        $sqlScoreSql .=" join studenttest on studenttest.test_attempt_id  =student_test_attempt.id and studenttest.paper_id = " . $params['paperId'] . " and studenttest.attempt = '1'";
                                    }
                                    $sqlScoreSql .=" where student_test_attempt.student_id = " . $val->student_id . " and student_test_attempt.task_id= " . $val->id . "  AND student_test_attempt.status='Completed' and task.question_set = " . $params['setId'];
                                    //echo "<br>";
                                }

                                $sqlScoreDetail = DB::select($sqlScoreSql);
                                // avarage marks of each studen
                                // avarage timeline of each student
                                if (!empty($sqlScoreDetail)) {
                                    $avgMarksOfStudent = 0;
                                    $avgTimesOfStudent = 0;

                                    foreach ($sqlScoreDetail as $x => $y) {
                                        if (empty($studentNameArray[$ve->student_id])) {
                                            $studentNameArray[$ve->student_id] = $y->first_name . " " . $y->last_name;
                                        }
                                        if (!empty($y->totalMarks)) {
                                            $avgMarksOfStudent = ($y->total_marks_obtain * 100) / $y->totalMarks;
                                            $avgTimesOfStudent = $y->total_time_spent;
                                            // $avgTimesOfStudent = ($y->total_time_spent * 100) / $y->total_time;
                                        }
                                        if (!empty($topstudent[$ve->student_id])) {
                                            $topstudent[$ve->student_id] = ($topstudent[$ve->student_id] + ($y->total_marks_obtain * 100) / $y->totalMarks) / 2;
                                        } else {
                                            $topstudent[$ve->student_id] = $avgMarksOfStudent;
                                        }
                                        if (!empty($topTime[$ve->student_id])) {
                                            $topTime[$ve->student_id] = ($topTime[$ve->student_id] + ($y->total_time_spent)) / 2;
                                        } else {
                                            $topTime[$ve->student_id] = $avgTimesOfStudent;
                                        }
                                        if (!empty($mostImporvedStudent[$ve->student_id])) {
                                            if ($mostImporvedStudent[$ve->student_id]['min'] > $avgMarksOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['min'] = $avgMarksOfStudent;
                                            }
                                            if ($mostImporvedStudent[$ve->student_id]['max'] < $avgMarksOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['max'] = $avgMarksOfStudent;
                                            }
                                            if ($mostImporvedStudent[$ve->student_id]['minTimeLine'] > $avgTimesOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['min'] = $avgTimesOfStudent;
                                            }
                                            if ($mostImporvedStudent[$ve->student_id]['maxTimeLine'] < $avgTimesOfStudent) {
                                                $mostImporvedStudent[$ve->student_id]['maxTimeLine'] = $avgTimesOfStudent;
                                            }
                                        } else {
                                            $mostImporvedStudent[$ve->student_id] = array('min' => $avgMarksOfStudent,
                                                'max' => $avgMarksOfStudent,
                                                'minTimeLine' => $avgTimesOfStudent,
                                                'maxTimeLine' => $avgTimesOfStudent
                                            );
                                        }
                                        $hittingTargetStudentClasswise[$v->id][$ve->student_id] = $topstudent[$ve->student_id];
                                    }
                                    $resultArray[$v->id][$ve->student_id]['score'][] = $sqlScoreDetail[0];
                                }
                            }
                        }
                    }
                }
            }
        }

        //Lets assume hitting target is 50%
        $classHittingCount = array();
        $schoolTestResult = array();
        $classNameArray = array();
        $arraySchoolOverViewTest = array();
        $counterArray = array();
        $schoolOverViewCounter = array();

        foreach ($classList as $k => $v) {
            //  $counter = 1;
            $classNameArray[$v->id] = $v->class_name;
            if (!empty($resultArray[$v->id])) {
                foreach ($resultArray[$v->id] as $key => $val) {
                    if (!empty($val)) {
                        // $index  = 0; 
                        foreach ($val as $a => $b) {
                            if (!empty($b)) {
                                foreach ($b as $c => $d) {  //$counter++;
                                    if (!empty($schoolTestResult[$v->id][$c])) {
                                        $schoolTestResult[$v->id][$c] = ($schoolTestResult[$v->id][$c] + ($d->total_marks_obtain * 100) / $d->totalMarks);
                                        $counterArray[$v->id][$c] = $counterArray[$v->id][$c] + 1;
                                    } else {
                                        if (!empty($d->totalMarks)) {
                                            $schoolTestResult[$v->id][$c] = ($d->total_marks_obtain * 100) / $d->totalMarks;
                                            $counterArray[$v->id][$c] = 1;
                                        }
                                    }
                                    if (!empty($arraySchoolOverViewTest[$v->id]['totalMark'])) {
                                        $arraySchoolOverViewTest[$v->id]['totalMark'] = ($arraySchoolOverViewTest[$v->id]['totalMark'] + ($d->total_marks_obtain * 100) / $d->totalMarks);
                                        $arraySchoolOverViewTest[$v->id]['totalTime'] = ($arraySchoolOverViewTest[$v->id]['totalTime'] + ($d->total_time_spent));
                                        $schoolOverViewCounter[$v->id]['totalMark'] = $schoolOverViewCounter[$v->id]['totalMark'] + 1;
                                    } else {
                                        if (!empty($d->totalMarks)) {
                                            $arraySchoolOverViewTest[$v->id]['totalMark'] = ($d->total_marks_obtain * 100) / $d->totalMarks;
                                            $arraySchoolOverViewTest[$v->id]['totalTime'] = $d->total_time_spent;
                                            $schoolOverViewCounter[$v->id]['totalMark'] = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $classHittingCount[$v->id] = 0;
            if (!empty($hittingTargetStudentClasswise[$v->id])) {
                $hittingTargetTraficLight = array('red' => 0, 'orange' => 0, 'green' => 0, 'blue' => 0,);
                foreach ($hittingTargetStudentClasswise[$v->id] as $r => $s) {
                    if ($s <= 20) {
                        $hittingTargetTraficLight['red'] = $hittingTargetTraficLight['red'] + 1;
                    } else if ($s > 21 && $s <= 50) {
                        $hittingTargetTraficLight['orange'] = $hittingTargetTraficLight['orange'] + 1;
                    } elseif ($s > 50 && $s < 70) {
                        $hittingTargetTraficLight['green'] = $hittingTargetTraficLight['green'] + 1;
                    } else {
                        $hittingTargetTraficLight['blue'] = $hittingTargetTraficLight['blue'] + 1;
                    }

                    // if ($s > 50) {
                    // $classHittingCount[$v->id] = $classHittingCount[$v->id] + 1;
                    // }
                }
                $classHittingCount[$v->id] = $hittingTargetTraficLight;
            }

            // $classHittingCount[$v->id] = 0;
            // if (!empty($hittingTargetStudentClasswise[$v->id])) {
            // foreach ($hittingTargetStudentClasswise[$v->id] as $r => $s) {
            // if ($s > 50) {
            // $classHittingCount[$v->id] = $classHittingCount[$v->id] + 1;
            // }
            // }
            // }
        }


        // calculate avarage
        foreach ($schoolTestResult as $avg => $cou) {
            foreach ($cou as $av => $co) {
                $schoolTestResult[$avg][$av] = $schoolTestResult[$avg][$av] / $counterArray[$avg][$av];
            }
        }

        foreach ($arraySchoolOverViewTest as $keys => $vals) {

            $arraySchoolOverViewTest[$keys]['totalMark'] = $arraySchoolOverViewTest[$keys]['totalMark'] / $schoolOverViewCounter[$keys]['totalMark'];
            $arraySchoolOverViewTest[$keys]['totalTime'] = $arraySchoolOverViewTest[$keys]['totalTime'] / $schoolOverViewCounter[$keys]['totalMark'];
        }

        $mostImprovedStudentList = array();
        $mostImprovedStudentTimeLine = array();

        if (!empty($mostImporvedStudent)) {
            foreach ($mostImporvedStudent as $key => $val) {
                $mostImprovedStudentList[$key] = $val['max'] - $val['min'];
                $mostImprovedStudentTimeLine[$key] = $val['maxTimeLine'] - $val['minTimeLine'];
            }
        }

        // get set name 
        $satArray = array();
        if (!empty($studentNameArray)) {
            $studentKey = array_keys($studentNameArray);
            $studentKey = implode(",", $studentKey);
            $sqlSet = "select qs.id,qs.set_name from task as t join taskstudents as ts on ts.task_id = t.id  left join questionsets as qs on qs.id = t.question_set where ts.student_id in ($studentKey) and t.task_type = 'Test' and t.subject = '" . $params['subject'] . "' group by qs.id";
            $satArray = DB::select($sqlSet);
        }

        $returnResultArray['classNameArray'] = $classNameArray;
        $returnResultArray['studentNameArray'] = $studentNameArray;
        $returnResultArray['studentTestTime'] = $topTime;
        $returnResultArray['studentMaxMarks'] = $topstudent;
        $returnResultArray['mostImprovedStu'] = $mostImprovedStudentList;
        $returnResultArray['mostImprovedStuTime'] = $mostImprovedStudentTimeLine;
        $returnResultArray['classHittingCount'] = $classHittingCount;
        $returnResultArray['graph'] = $schoolTestResult;
        $returnResultArray['arraySchoolOverViewTest'] = $arraySchoolOverViewTest;
        $returnResultArray['satArray'] = $satArray;
        return $returnResultArray;
    }

    public function classTopicReport($params) {
       
        $sqlStrand = "select id,strand from strands where subject = '" . $params['subject'] . "' and status!='Deleted' and parent_id = 0";
        $strandList = DB::select($sqlStrand);

        if (!empty($params['strand'])) {
            $strand = $params['strand'];
        } else {
            $strand = $strandList[0]->id;
        }

        $sqlSubStrand = "select id,strand from strands where parent_id=" . $strand . " and status!='Deleted'";
        $subStrandList = DB::select($sqlSubStrand);

        if (!empty($params['substrand'])) {
            $subStrand = $params['substrand'];
        } else {
            $subStrand = $subStrandList[0]->id;
        }        

        $sql = "SELECT users.first_name, users.last_name,student_revision_result_data.class_name, student_revision_result_data.avg_marks,student_revision_result_data.avg_time,
            student_revision_result_data.avg_total_time
        FROM classstudents 
        INNER JOIN users ON users.id = classstudents.student_id
        LEFT JOIN student_revision_result_data ON classstudents.student_id = student_revision_result_data.student_id AND student_revision_result_data.strand_id=".$strand." AND student_revision_result_data.substrand_id=".$subStrand." AND SUBJECT = '" . $params['subject'] . "'
        WHERE classstudents.class_id= " . $params['classId'] . " ORDER BY users.first_name, users.last_name ASC";

        $sqlResult = DB::select($sql);        
        $returnResultArray['strandId'] = $strand;
        $returnResultArray['substrandId'] = $subStrand;
        $returnResultArray['strands'] = $strandList;
        $returnResultArray['substrands'] = $subStrandList;
        $returnResultArray['topicdetail'] = $sqlResult;        
        
        return $returnResultArray;
        
        /*$returnResultArray = array();
        // all class of a school
        $sqlClassList = "select id,class_name from schoolclasses where id = " . $params['schoolId'] . " and status!='Deleted'";
        $classList = DB::select($sqlClassList);

        $sqlStrand = "select id,strand from strands where subject = '" . $params['subject'] . "' and status!='Deleted' and parent_id = 0";
        $strandList = DB::select($sqlStrand);

        if (!empty($params['strand'])) {
            $strand = $params['strand'];
        } else {
            $strand = $strandList[0]->id;
        }

        $sqlSubStrand = "select id,strand from strands where parent_id=" . $strand . " and status!='Deleted'";
        $subStrandList = DB::select($sqlSubStrand);

        if (!empty($params['substrand'])) {
            $subStrand = $params['substrand'];
        } else {
            $subStrand = $subStrandList[0]->id;
        }


        //$sqlTopicSql = "select schoolclasses.id as classId,schoolclasses.class_name,task.id as taskId,studentrevision.student_id as studentId,classstudents.school_id, studentrevision.total_marks,studentrevision.mark_obtain,studentrevision.quesmaxtime,(studentrevision.quesmaxtime-studentrevision.remainingtime) as timeUsed,task.strand,task.substrand from schoolclasses left join classstudents on classstudents.class_id = schoolclasses.id and classstudents.school_id = '" . $params['schoolId'] . "' left join studentrevision on studentrevision.student_id = classstudents.student_id left join task  on task.id=studentrevision.task_id where task.task_type = 'Revision' and task.strand = " . $strand . " and task.substrand = " . $subStrand . " and task.subject='" . $params['subject'] . "'";
        $sqlTopicSql = "select schoolclasses.id as classId,schoolclasses.class_name,task.id as taskId,studentrevision.student_id as studentId,classstudents.school_id, studentrevision.total_marks,studentrevision.mark_obtain,studentrevision.quesmaxtime,(studentrevision.quesmaxtime-studentrevision.remainingtime) as timeUsed,task.strand,task.substrand from schoolclasses left join classstudents on classstudents.class_id = schoolclasses.id and classstudents.class_id = '" . $params['schoolId'] . "' left join studentrevision on studentrevision.student_id = classstudents.student_id left join task  on task.id=studentrevision.task_id where task.task_type = 'Revision' and task.strand = " . $strand . " and task.substrand = " . $subStrand . " and task.subject='" . $params['subject'] . "'";
        $sqlTopicDetail = DB::select($sqlTopicSql);
        $returnResultArray['strandId'] = $strand;
        $returnResultArray['substrandId'] = $subStrand;
        $returnResultArray['class'] = $classList;
        $returnResultArray['strands'] = $strandList;
        $returnResultArray['substrands'] = $subStrandList;
        $returnResultArray['topicdetail'] = $sqlTopicDetail;
        return $returnResultArray;*/
    }

    function deep_in_array_short(&$array, $key) {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;
        return $array;
    }

}
