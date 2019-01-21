<?php

/**
 * This controller is used for Result
 * @package    Result
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Controllers;

use App\Repositories\StudentTaskRepository;
use App\Repositories\TaskRepository;
use App\Repositories\ResultRepository;
use App\Repositories\StrandRepository;
use Illuminate\Http\Request;
/**
 * This controller is used for Result
 * @package    Result
 * @author     Icreon Tech  - dev2.
 */
class ResultController extends Controller {

    /**
     * The StudentTaskRepository instance.
     *
     * @var App\Repositories\StudentTaskRepository
     */
    protected $studentTaskRepo;

    /**
     * The TaskRepository instance.
     *
     * @var App\Repositories\TaskRepository
     */
    protected $taskRepo;

    /**
     * The ResultRepository instance.
     *
     * @var App\Repositories\ResultRepository
     */
    protected $resultRepo;

    /**
     * Create a new ExaminationController instance.
     * @param  App\Repositories\StudentTaskRepository $studentTaskRepo
     * @return void
     */
    public function __construct(StudentTaskRepository $studentTaskRepo, TaskRepository $taskRepo, ResultRepository $resultRepo,StrandRepository $strandRepo) {
        $this->studentTaskRepo = $studentTaskRepo;
        $this->taskRepo = $taskRepo;
        $this->resultRepo = $resultRepo;
        $this->strandRepo = $strandRepo;
    }

    /**
     * Display a listing of the all Enquiry records.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function revisionprogress() {
        $data = array();
        return view('front.result.revision-progress', $data);
    }

    public function testResultDetail($attemptId) { 
        $attemptId = decryptParam($attemptId);
        $studentId = session()->get('user')['id'];
        //check wether attempt test paper exist or not
        $studentStoredTestPaper = $this->studentTaskRepo->getStudentStoredTestPaper(array(
            'student_id' => $studentId,
            'attempt_paperid' => $attemptId
        ));
        if (!count($studentStoredTestPaper)) {
            return redirect('error404');
        }
        
        if ($studentStoredTestPaper['status'] == COMPLETED) {
            $questionAttemptBreakdown = $this->studentTaskRepo->getQuestionAttemptBreakdown(array(
                'questionids' => explode(",", $studentStoredTestPaper['questionids']),
                'attempt_id' => $studentStoredTestPaper['id'],
                'task_type' => TEST,
                'paper_id' => $studentStoredTestPaper['paper_id'], 
                'attempt' => $studentStoredTestPaper['attempt'], 
                'student_test_attempt_id' => $studentStoredTestPaper['student_test_attempt_id'] 
            ));
            if ($studentStoredTestPaper['remainingtime'] > $studentStoredTestPaper['quesmaxtime']) {
                $over_time = TRUE;
                $time_spent = $studentStoredTestPaper['remainingtime'] / 60;
            } else {
                $over_time = FALSE;
                //$time_spent = ($studentStoredTestPaper['quesmaxtime'] - $studentStoredTestPaper['remainingtime']) / 60;
                //JT need to change in future
                $time_spent_diff = ($studentStoredTestPaper['quesmaxtime'] - $studentStoredTestPaper['remainingtime']);
                $diff_range = range($time_spent_diff, $time_spent_diff+10);
                if(in_array($studentStoredTestPaper['attempt_complete_time'], $diff_range)){
                    $time_spent = ($time_spent_diff) / 60;
                }else{
                    if($studentStoredTestPaper['attempt_complete_time'] - $time_spent_diff > 4500){
                        $time_spent = ($time_spent_diff) / 60;
                    }else{
                        $time_spent = ($studentStoredTestPaper['attempt_complete_time']) / 60;
                    }
                }
                if ($time_spent*60 > $studentStoredTestPaper['quesmaxtime']) {
                    $over_time = TRUE;
                } else {
                    $over_time = FALSE;
                }
                //end JT
            }
            $testDetail = $this->taskRepo->getTest($studentStoredTestPaper['task_id']);
            $testDetail = $testDetail->toArray();

            $subjectPaper = subjectPapers()[$testDetail['subject']];
            $paper_name = $subjectPaper[$studentStoredTestPaper['paper_id']]['name'];
            $attempt_no = 'Attempt ' . $studentStoredTestPaper['attempt'];
            $percentage_obtained = (($studentStoredTestPaper['mark_obtain'] / $studentStoredTestPaper['total_marks']) * 100);
            $subject = $testDetail['subject'] == ENGLISH ? strtolower(ENGLISH) : strtolower(MATHS);
            $data['viewData'] = array(
                'subject' => $subject,
                'set_name' => $testDetail['set_name'],
                'paper_name' => $paper_name,
                'attempt_no' => $attempt_no,
                'time_spent' => ceil($time_spent),
                'over_time' => $over_time,
                'num_total_qus' => $studentStoredTestPaper['num_question'],
                'htmQusseries' => $questionAttemptBreakdown,
                'total_marks' => $studentStoredTestPaper['total_marks'],
                'mark_obtained' => $studentStoredTestPaper['mark_obtain'],
                'percentage_obtained' => round($percentage_obtained, 1),
                'attempt_date' => outputDateFormat($studentStoredTestPaper['attempt_at'])
            );
            
            //make test paper completed
            $attemptAnswerAggregate = $this->studentTaskRepo->makeTestPaperAttemptCompletedForMetaDetails(array(
                'attempt_id' => $attemptId,
                'questionids' => $studentStoredTestPaper['questionids'],
                'complete_time' => $this->studentTaskRepo->currentDateTime,
                'student_id' => $studentId
            ));
            $data['attemptAnswerAggregate'] = $attemptAnswerAggregate;
            return view('front.result.test-result-detail', $data);
        } else {
            return redirect('error404');
        }
    }

    public function revisionResultDetail($attemptId) { 
        $attemptId = decryptParam($attemptId);
        $studentId = session()->get('user')['id'];
        $studentStoredRevision = $this->studentTaskRepo->getStudentStoredRevision(array(
            'student_revisionid' => $attemptId,
            'student_id' => $studentId
        ));
        if (!count($studentStoredRevision)) {
            return redirect('error404');
        }
        if ($studentStoredRevision['status'] == COMPLETED) {
            if ($studentStoredRevision['remainingtime'] > $studentStoredRevision['quesmaxtime']) {
                $over_time = TRUE;
                $time_spent = $studentStoredRevision['remainingtime'] / 60;
            } else {
                $over_time = FALSE;
                //$time_spent = ($studentStoredRevision['quesmaxtime'] - $studentStoredRevision['remainingtime']) / 60;
                //JT need to change in future
                $time_spent_diff = ($studentStoredRevision['quesmaxtime'] - $studentStoredRevision['remainingtime']);
                $diff_range = range($time_spent_diff, $time_spent_diff+10);
                if(in_array($studentStoredRevision['attempt_complete_time'], $diff_range)){
                    $time_spent = ($time_spent_diff) / 60;
                }else{
                    if($studentStoredRevision['attempt_complete_time'] - $time_spent_diff > 4500){
                        $time_spent = ($time_spent_diff) / 60;
                    }else{
                        $time_spent = ($studentStoredRevision['attempt_complete_time']) / 60;
                    }
                }
                //end JT
            }
            $questionAttemptBreakdown = $this->studentTaskRepo->getQuestionAttemptBreakdown(array(
                'questionids' => explode(",", $studentStoredRevision['questionids']),
                'attempt_id' => $studentStoredRevision['id'],
                'task_type' => REVISION
            ));
            $revisionDetail = $this->taskRepo->getRevision($studentStoredRevision['task_id']);
            $revisionDetail = $revisionDetail->toArray();
            $percentage_obtained = (($studentStoredRevision['mark_obtain'] / $studentStoredRevision['total_marks']) * 100);
            $subject = $revisionDetail['subject'] == ENGLISH ? strtolower(ENGLISH) : strtolower(MATHS);
            $data['viewData'] = array(
                'subject' => $subject,
                'strand_id' => $revisionDetail['strand_id'],
                'strand' => $revisionDetail['strand'],
                'substrand' => $revisionDetail['substrand'],
                'time_spent' => round($time_spent),
                'over_time' => $over_time,
                'num_total_qus' => $studentStoredRevision['num_question'],
                'htmQusseries' => $questionAttemptBreakdown,
                'total_marks' => $studentStoredRevision['total_marks'],
                'mark_obtained' => $studentStoredRevision['mark_obtain'],
                'percentage_obtained' => round($percentage_obtained, 1),
                'attempt_date' => outputDateFormat($studentStoredRevision['attempt_at'])
            );
            //asd($data['viewData']);
            
            return view('front.result.revision-result-detail', $data);
        } else {
            return redirect('error404');
        }
    }

    public function myresult() {
        $data['subject'] = '';
        return view('front.result.myresult', $data);
    }

    public function myresultSubject($subject) {
        $data['subject'] = $subject;
        $studentId = session()->get('user')['id'];
        if (strtolower($subject) == strtolower(MATHS)) {
            return view('front.result.myresult-maths', $data);
        } else if (strtolower($subject) == strtolower(ENGLISH)) {
            return view('front.result.myresult-english', $data);
        }
    }

    public function myresultTest($subject) {
        $data['subject'] = $subject;
        $subjectVar = getSubject($subject);
        $subjectPaper = subjectPapers();
        $studentId = session()->get('user')['id'];
        $studentCompletedTestList = $this->studentTaskRepo->studentCompletedTestList(array(
            'student_id' => $studentId,
            'subject' => $subjectVar,
            'mathtime' => $subjectPaper[MATH][3]['time'],
            'englishtime' => $subjectPaper[ENGLISH][5]['time']
        ));
        $data['studentCompletedTestList'] = $studentCompletedTestList;
        if (count($studentCompletedTestList)) {
            $strandResult = $this->strandRepo->getStrandTree(false);
           // asd($strandResult);
            
            //get student first and last test result percentage
            $studentResultReportGraphData = $this->studentTaskRepo->studentTestResultReportGraphData(array(
                'student_id' => $studentId,
                'subject' => $subjectVar
            ));
            $data['studentTaskPercentage'] = json_encode($studentResultReportGraphData['gData']);
            $data['studentFirstLastTestAttempt'] = $studentResultReportGraphData['firstLast'];
            
            //get student strand result
            $data['studentStrandResultList'] = $this->resultRepo->studentStrandResultList(array(
                'student_id' => $studentId,
                'task_type' => TEST,
                'subject' => $subjectVar,
                'strandResult'=>$strandResult
            ));
            
        }
        return view('front.result.myresult-test', $data);
    }

    public function testAttemptResult($attemptid) {
        $data = array();
        list($test_attempt_id, $attempt_num) = explode("-", $attemptid);
        $studentTestPaper = $this->studentTaskRepo->getStudentStoredTestPaper(array(
            'test_attempt_id' => $test_attempt_id,
            'attempt' => $attempt_num,
            'listall' => true
        ));
        foreach ($studentTestPaper as $key => $row) {
            $dataList = array(
                'paper_num' => 'Paper  ' . ($key + 1),
                'num_question' => $row['num_question'],
                'attempt_date' => outputDateFormat($row['attempt_at']),
                'num_correct' => $row['num_correct'],
                'total_marks' => $row['total_marks'],
                'mark_obtain' => $row['mark_obtain'],
            );
            $data['viewdata'][] = $this->resultRepo->calculateTestAttemptResult($dataList);
        }
        return view('front.result.myresult-test-attempt', $data);
    }

    public function myresultRevision($subject, StrandRepository $strandRepo) { 
        $subjectVar = getSubject($subject);
        $data['subject'] = $subject;
        $subjectVar = getSubject($subject);
        $studentId = session()->get('user')['id'];
        $revisionTopicwiseAggegrateResult = $this->studentTaskRepo->getRevisionTopicwiseAggegrateResult(array(
            'student_id' => $studentId,
            'subject' => $subjectVar
        ));
        $dataResult = array();
        $questionAttempt = 0;
        if(count($revisionTopicwiseAggegrateResult)){
            $strandResult = $strandRepo->getStrandTree(false);
            $subjectStrand = $strandResult['strands'][$subjectVar];
            $subjectSubStrand = $strandResult['substrands'];
            foreach($revisionTopicwiseAggegrateResult as $row){
                $dataResult[$row['strand']]['topic'] = $subjectStrand[$row['strand']];
                $dataResult[$row['strand']]['data'][] = array(
                    'subtopic' => @$subjectSubStrand[$row['strand']][$row['substrand']],
                    'subtopic_id' => $row['substrand'],
                    'heighstscore' => round($row['markObtainFra']*100),
                    'avg_last_three_attempt' => $this->resultRepo->lastNattemptAverage(array(
                        'markobtain_on_each_attempt' => $row['markobtain_on_each_attempt'],
                        'markattempt_on_each_attempt' => $row['markattempt_on_each_attempt']
                    )),
                    'star' => $this->resultRepo->revisionResultStarRating(array(
                        'markObtain' => $row['markObtain'],
                        'markAttempt' => $row['markAttempt']
                    ))
                );
                $questionAttempt = $questionAttempt + $row['questionAttempt'];
            }
        }
        //asd($revisionTopicwiseAggegrateResult,0); asd($dataResult);
        $data['dataResult'] = $dataResult;
        $data['questionAttempt'] = $questionAttempt;
        $data['student_id'] = $studentId;
        $ratingResult = $this->studentTaskRepo->getStudentRating(array('student_id'=>$studentId));
        $ratingResultNew = array();
        if(!empty($ratingResult)) {
            foreach($ratingResult as $key=>$val) {
                $ratingResultNew[$val['strand_id']][$val['substrand_id']][] = $val['rating'];

            }
        }
        $data['star_rating'] = $ratingResultNew;
        return view('front.result.myresult-revision', $data);
    }
    
    public function examinationRevisionResult($studentRevisionId) {
        $data = array();
        $studentRevisionId = decryptParam($studentRevisionId);
        $studentId = session()->get('user')['id'];

        //check wether student revision exist or not.
        $studentStoredRevision = $this->studentTaskRepo->getStudentStoredRevision(array(
            'student_revisionid' => $studentRevisionId,
            'student_id' => $studentId
        ));
        if (!count($studentStoredRevision) || $studentStoredRevision['status'] != COMPLETED) {
            return redirect('error404');
        }
        $studentStoredRevision['isCorrectAnsData'] = TRUE;
        //end
        $questions = $this->studentTaskRepo->getStudentRevisionQuestions($studentStoredRevision);
        //asd($questions);
        $revisionDetail = $this->taskRepo->getRevision($studentStoredRevision['task_id']);
        $questions['questionsummary']['titlefirst'] = $revisionDetail['strand'];
        $questions['questionsummary']['titlesecond'] = $revisionDetail['substrand'];
        $data['questions'] = htmlentities(json_encode($questions));
        return view('front.examination.examination_result', $data);
    }
    /**
     * This function is used to show the test result
     * @param type $attemptId
     * @return type
     */
    public function examinationTestResult($attemptId) {
        $data = array();
        $attemptId = decryptParam($attemptId);
        $studentId = session()->get('user')['id'];
        //check wether attempt test paper exist or not
        $studentStoredTestPaper = $this->studentTaskRepo->getStudentStoredTestPaper(array(
            'student_id' => $studentId,
            'attempt_paperid' => $attemptId
        ));
        if (!$studentStoredTestPaper || $studentStoredTestPaper['status'] != COMPLETED) {
            return redirect('error404');
        }
        $studentStoredTestPaper['isCorrectAnsData'] = TRUE;
        $questions = $this->studentTaskRepo->getStudentTestQuestions($studentStoredTestPaper);
        $testPaperDetail = $this->taskRepo->getTest($studentStoredTestPaper['task_id']);
        $questions['questionsummary']['titlefirst']     = $testPaperDetail['set_name'];
        $questions['questionsummary']['titlesecond']    = subjectPapers()[$testPaperDetail['subject']][$studentStoredTestPaper['paper_id']]['name'];
        $data['questions'] = htmlentities(json_encode($questions));
        return view('front.examination.examination_result', $data);
    }
    /**
     * This function is used to save the student self rating
     * @param Request $request
     */
    public function saveStudentRating(Request $request){
        $postParam = array();
        $postParam['student_id'] = decryptParam($request->get('student_id'));
        $postParam['strand_id'] = decryptParam($request->get('strand'));
        $postParam['substrand_id'] = decryptParam($request->get('substrand'));
        $postParam['rating'] = $request->get('rating');
        $status = $this->studentTaskRepo->updateStudentRating($postParam);
        die;
    }
}
