<?php

/**
 * This controller is used for Examination.
 * @package    Examination
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Controllers;

use App\Repositories\StudentTaskRepository;
use App\Repositories\StudentAwardRepository;
use App\Repositories\TaskRepository;
use App\Repositories\EmailRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

/**
 * This controller is used for Examination.
 * @package    Examination
 * @author     Icreon Tech  - dev2.
 */
class ExaminationController extends Controller {

    /**
     * The StudentTaskRepository instance.
     *
     * @var App\Repositories\StudentTaskRepository
     */
    protected $studentTaskRepo;

    /**
     * The StudentAwardRepository instance.
     *
     * @var App\Repositories\StudentAwardRepository
     */
    protected $studentAwardRepo;

    /**
     * The TaskRepository instance.
     *
     * @var App\Repositories\TaskRepository
     */
    protected $taskRepo;

    /**
     * Create a new ExaminationController instance.
     * @param  App\Repositories\StudentTaskRepository $studentTaskRepo
     * @return void
     */
    public function __construct(StudentTaskRepository $studentTaskRepo, StudentAwardRepository $studentAwardRepo, TaskRepository $taskRepo) {
        $this->studentTaskRepo = $studentTaskRepo;
        $this->studentAwardRepo = $studentAwardRepo;
        $this->taskRepo = $taskRepo;
    }

    public function examinationTest($attemptPaperId) {
        $data = array();
        $data['instratctArray'] = view_instructions();
        $attemptPaperId = decryptParam($attemptPaperId);
        $studentId = session()->get('user')['id'];

        //check wether attempt test paper exist or not
        $studentStoredTestPaper = $this->studentTaskRepo->getStudentStoredTestPaper(array(
            'student_id' => $studentId,
            'attempt_paperid' => $attemptPaperId
        ));
        if (!count($studentStoredTestPaper)) {
            return redirect('error404');
        }
        //end
        //check wether student has valid test assigned, else return 404 (or any specified page or message)
        $testPaperDetail = $this->studentTaskRepo->getStudentTestPaperDetail(array(
            'student_id' => $studentId,
            'tutor_id' => session()->get('user')['tutor_id'],
            'id' => $studentStoredTestPaper['student_test_attempt_id']
        ));
        if (!$testPaperDetail) {
            return redirect('error404');
        }
        //end
        //wether this attempt is complete attempt (Complete attempt : last paper first attempt)
        $isCompleteAttempt = $this->studentTaskRepo->isCompteteTestAttempt(array(
            'subject' => $testPaperDetail['subject'],
            'question_paper_id' => $studentStoredTestPaper['paper_id']
        ));
        //end
        //update student revision attempt (add attempt_date), if it is first time
        if ($studentStoredTestPaper['attempt_at'] == NULL_DATETIME) {
            $this->studentTaskRepo->updateStudentTestPaper(array(
                'id' => $studentStoredTestPaper['id'],
                'attempt_at' => $this->studentTaskRepo->currentDateTime
            ));
             //insert into question answer table
            $this->studentTaskRepo->saveDefaultQuestionAnswer(array(
                'attempt_id' => $studentStoredTestPaper['id'],
                'task_type' => TEST,
                'question_ids' => explode(",", $studentStoredTestPaper['questionids']),
                'student_id' => $studentId,
                'set_id' => $testPaperDetail['question_set'],
                'test_attempt_id' => $testPaperDetail['id'],
                'paper_id' => $studentStoredTestPaper['paper_id'],
                'attempt_num' => $studentStoredTestPaper['attempt']
            ));
            //end
            //update test meta table for attempt date, attempt is complete attempt (Complete attempt : last paper first attempt)
            if ($isCompleteAttempt) {
                $this->studentTaskRepo->updateStudentTestMeta(array(
                    'id' => $studentStoredTestPaper['student_test_attempt_id'],
                    'attempt_at' => $this->studentTaskRepo->currentDateTime
                ));
            }
        }
        //end
        $studentStoredTestPaper['is_complete_attempt'] = $isCompleteAttempt;
        $questions = $this->studentTaskRepo->getStudentTestQuestions($studentStoredTestPaper);
        $questions['questionsummary']['titlefirst'] = $testPaperDetail['set_name'];
        $questions['questionsummary']['titlesecond'] = subjectPapers()[$testPaperDetail['subject']][$studentStoredTestPaper['paper_id']]['name'];
        $data['questions'] = htmlentities(json_encode($questions));
        return view('front.examination.examination', $data);
    }

    public function examinationRevision($studentRevisionId) {
        $data = array();
        $data['instratctArray'] = view_instructions();
        $studentRevisionId = decryptParam($studentRevisionId);
        $studentId = session()->get('user')['id'];
        //check wether student revision exist or not.
        $studentStoredRevision = $this->studentTaskRepo->getStudentStoredRevision(array(
            'student_revisionid' => $studentRevisionId,
            'student_id' => $studentId
        ));
        if (!count($studentStoredRevision)) {
            return redirect('error404');
        }
        //end
        //check wether student has a valid assigned task (may be task attempt date has over)
        $revisionAssigned = $this->studentTaskRepo->getStudentRevisionDetail(array(
            'tutor_id' => session()->get('user')['tutor_id'],
            'student_id' => $studentId,
            'task_id' => $studentStoredRevision['task_id']
        ));
        if (!$revisionAssigned) {
            return redirect('error404');
        }
        $revisionAssigned = $revisionAssigned->toArray();
        //end 
        //update student revision attempt (add attempt_date), if it is first time
        if ($studentStoredRevision['attempt_at'] == NULL_DATETIME) {
            $this->studentTaskRepo->updateStudentRevision(array(
                'id' => $studentStoredRevision['id'],
                'attempt_at' => $this->studentTaskRepo->currentDateTime
            ));
            $studentStoredRevision['remainingtime'] = REVISION_TIME;
            //insert into question answer table
            $this->studentTaskRepo->saveDefaultQuestionAnswer(array(
                'attempt_id' => $studentStoredRevision['id'],
                'task_type' => REVISION,
                'student_id' => $studentId,
                'question_ids' => explode(",", $studentStoredRevision['questionids'])
            ));
            //end
        }
        //end
        $questions = $this->studentTaskRepo->getStudentRevisionQuestions($studentStoredRevision);
        $questions['questionsummary']['titlefirst'] = $revisionAssigned['strand'];
        $questions['questionsummary']['titlesecond'] = $revisionAssigned['substrand'];
        $data['questions'] = htmlentities(json_encode($questions));
        return view('front.examination.examination', $data);
    }

    public function questionAttempt(Request $request) {
        $input = $request->all();
        $questionsummary = $input['questionsummary'];
        $updateData = array(
            'id' => $questionsummary['attemptid'],
            'num_remaining' => $questionsummary['remaining'],
            'num_answered' => $questionsummary['answered'],
            'num_skipped' => $questionsummary['skipped'],
            'remainingtime' => $questionsummary['remainingtime']
        );
        if ($questionsummary['task_type'] == REVISION) {
            $this->studentTaskRepo->updateStudentRevision($updateData);
        }
        if ($questionsummary['task_type'] == TEST) {
            $this->studentTaskRepo->updateStudentTestPaper($updateData);
        }
        $this->studentTaskRepo->saveQuestionAnswer($input);
        return response()->json(array('questionReference' => TRUE));
    }

    public function questionAttemptComplete(Request $request, UserRepository $userRepo, EmailRepository $emailRepo) {
        $inputs = $request->all();
        $data = $inputs['data'];
        $sessionData = session()->get('user');
        $student_id = $sessionData['id'];
        $student_name = $sessionData['first_name'] . ' ' . $sessionData['last_name'];
        $to_name = '';
        $to_email = '';
        $result_data = array(
            'student_id' => $student_id,
            'student_name' => $student_name,
            'tutor_id' => '',
            'tutor_name' => '',
            'teacher_id' => '',
            'teacher_name' => '',
            'school_id' => '',
            'school_name' => '',
            'class_id' => '',
            'class_name' => ''
        );
        if(!empty($sessionData['tutor_id'])){
            $studentParentUser = $userRepo->getUser(array(
                    'id' => $sessionData['tutor_id'],
                    'select' => array('id', 'email', 'first_name', 'last_name')
                ))->get()->first();
            if(count($studentParentUser)){
                $to_name = $studentParentUser->first_name . ' ' . $studentParentUser->last_name;
                $to_email = $studentParentUser->email;
                $result_data['tutor_id'] = $studentParentUser->id;
                $result_data['tutor_name'] = $studentParentUser->first_name . ' ' . $studentParentUser->last_name;
            }
       }else{
            $studentSchoolTeacher = $userRepo->getUser(array(
                    'ids' => array($sessionData['school_id'],$sessionData['teacher_id']),
                    'select' => array('id', 'email', 'first_name', 'last_name','school_name','user_type')
                ))->orderBy('user_type')->get()->toArray();
            if(count($studentSchoolTeacher) == 2){
                if(isset($studentSchoolTeacher[0])){
                    $schoolDetail =  $studentSchoolTeacher[0];
                    $result_data['school_id'] = $schoolDetail['id'];
                    $result_data['school_name'] = $schoolDetail['school_name'];
                }
                if(isset($studentSchoolTeacher[1])){
                    $teacherDetail = $studentSchoolTeacher[1];
                    $result_data['teacher_id']      = $teacherDetail['id'];
                    $result_data['teacher_name']    = $teacherDetail['first_name'] . ' ' . $teacherDetail['last_name'];
                    $to_name = $teacherDetail['first_name'] . ' ' . $teacherDetail['last_name'];
                    $to_email = $teacherDetail['email'];
                }
            }
            //get student class
            $studentAssigendClass = $userRepo->getStudentAssigendClass(array('student_id' => $student_id));
            if(count($studentAssigendClass)){
                $result_data['class_id'] = $studentAssigendClass['id'];
                $result_data['class_name'] = $studentAssigendClass['class_name'];
            }
        }
        if ($data['task_type'] == REVISION) {
            //get the current revision detail
            $studentStoredRevision = $this->studentTaskRepo->getStudentStoredRevision(array(
                'student_revisionid' => $data['attemptid']
            ));
            $revisionDetail = $this->taskRepo->getRevision($studentStoredRevision['task_id'])->toArray();
            
            $revisionAggrigate = $this->studentTaskRepo->makeRevisionAttemptCompleted(array(
                'attempt_id' => $studentStoredRevision['id'],
                'questionids' => $studentStoredRevision['questionids'],
                'complete_time' => $this->studentTaskRepo->currentDateTime,
                'student_id' => $student_id,
                'assign_id' => $studentStoredRevision['assign_id']
            ));
            
            $percentageObtained = round((($revisionAggrigate['mark_obtained']/$studentStoredRevision['total_marks'])*100),2);
            
            $result_data['task_id']         = $studentStoredRevision['task_id'];
            $result_data['attempt_num']     = $studentStoredRevision['attempt'];
            $result_data['total_time']      = $studentStoredRevision['quesmaxtime'];
            $result_data['time_take']       = calculateTaskCompleteTime(array(
                'quesmaxtime' => $studentStoredRevision['quesmaxtime'],
                'remainingtime' => $studentStoredRevision['remainingtime'],
                'completetime' => $studentStoredRevision['attempt_complete_time']
            ));
            $result_data['percentage_obtained']   = $percentageObtained;
            $result_data['strand_id']       = $revisionDetail['strand_id'];
            $result_data['strand_name']     = $revisionDetail['strand'];
            $result_data['substrand_id']    = $revisionDetail['substrand_id'];
            $result_data['substrand_name']  = $revisionDetail['substrand'];
            $result_data['subject']  = $revisionDetail['subject'];
            //store average mark % and time line.
            $this->studentTaskRepo->studentRevisionResultData($result_data);
            //award for student, if eligible
            $this->studentAwardRepo->rewardToStudent(array(
                'task_type' => REVISION,
                'subject' => $revisionDetail['subject'],
                'strand' => $revisionDetail['strand_id'],
                'substrand' => $revisionDetail['substrand_id'],
                'student_id' => $student_id,
                'tutor_id' => $sessionData['tutor_id'],
                'student_percent' => $percentageObtained,
                'name' => $student_name,
                'date' => $this->studentTaskRepo->currentDateTime,
                'signature' => public_path('images/signature.png')
            ));
            //end
            //email notification
            $emailParam = array(
                'addressData' => array(
                    'to_email' => $to_email,
                    'to_name' => $to_name,
                ),
                'userData' => array(
                    'name' => $to_name,
                    'pupil_name' => $student_name,
                    'topic_name' => $revisionDetail['substrand'],
                    'percentage' => $percentageObtained
                )
            );
            if(!empty($to_email))
                $emailRepo->sendEmail($emailParam, 25);
            //end

            return response()->json(array('questionReference' => route('revision.result', encryptParam($data['attemptid']))));
        }
        if ($data['task_type'] == TEST) {
            //get the current test paper detail
            $studentStoredTestPaper = $this->studentTaskRepo->getStudentStoredTestPaper(array(
                'attempt_paperid' => $data['attemptid']
            ));
            //make test paper completed
            $this->studentTaskRepo->makeTestPaperAttemptCompleted(array(
                'attempt_id' => $studentStoredTestPaper['id'],
                'questionids' => $studentStoredTestPaper['questionids'],
                'complete_time' => $this->studentTaskRepo->currentDateTime,
                'student_id' => $student_id
            ));

            //wether current test paper is final paper for this particular attempt
            if ($data['is_complete_attempt']) {
                //get Test detail
                $testDetail = $this->taskRepo->getTest($studentStoredTestPaper['task_id'])->toArray();
                $cnt_papers = count(subjectPapers()[$testDetail['subject']]);
                //make test attempt complete (update student_test_attempt table)
                $this->studentTaskRepo->makeTestAttemptCompleted(array(
                    'test_attempt_id' => $studentStoredTestPaper['student_test_attempt_id'],
                    'attempt' => $studentStoredTestPaper['attempt'],
                    'assign_id' => $studentStoredTestPaper['assign_id'],
                    'student_id' => $student_id
                ));
                $test_attempt_1_completed = $studentStoredTestPaper['attempt'] != 1 ? $studentStoredTestPaper['completed_at_attempt_1'] : $this->studentTaskRepo->currentDateTime;
                //test report
                $result_data['test_attempt_id']         = $studentStoredTestPaper['student_test_attempt_id'];
                $result_data['task_id']         = $studentStoredTestPaper['task_id'];
                $result_data['attempt_num']         = $studentStoredTestPaper['attempt'];
                $result_data['assignment_num']         = $studentStoredTestPaper['assignment_num'];
                $result_data['test_attempt_at']         = $test_attempt_1_completed;
                $result_data['last_assessment_date']         = $this->studentTaskRepo->currentDateTime;
                $result_data['question_set_id']         = $testDetail['question_set'];
                $result_data['set_name']         = $testDetail['set_name'];
                $result_data['subject']         = $testDetail['subject'];
                $result_data['cnt_papers']         = $cnt_papers;
                $testAttemptAverage = $this->studentTaskRepo->studentTestResultData($result_data);
                $percentageObtained = $testAttemptAverage;
                    
                //test strand report (Gap Analysis)
                $this->studentTaskRepo->studentTestStrandReoprt(array(
                    'student_id' => $student_id,
                    'test_attempt_id' => $studentStoredTestPaper['student_test_attempt_id'],
                    'subject' => $testDetail['subject'],
                    'attempt_num' => $studentStoredTestPaper['attempt'],
                    'cnt_papers' => $cnt_papers,
                    'set_id' => $testDetail['question_set']
                ));
                //award for student, if eligible
                if ($studentStoredTestPaper['attempt'] == 1) {
                    $this->studentAwardRepo->rewardToStudent(array(
                        'task_type' => TEST,
                        'subject' => $testDetail['subject'],
                        'question_set' => $testDetail['question_set'],
                        'student_id' => $student_id,
                        'tutor_id' => session()->get('user')['tutor_id'],
                        'student_percent' => $percentageObtained,
                        'name' => $student_name,
                        'date' => $this->studentTaskRepo->currentDateTime,
                        'signature' => public_path('images/signature.png')
                    ));
                    
                    /* code to update the base line value for student in users table */
                        $studentDetailsData = $userRepo->getUser(array(
                            'id' => $student_id
                        ))->get()->first()->toArray();
                        if(count($studentDetailsData)>0) {
                            if(($testDetail['subject'] == MATH && empty($studentDetailsData['ks2_maths_baseline_value'])) || ($testDetail['subject'] == ENGLISH && empty($studentDetailsData['ks2_english_baseline_value']))) {
                                $this->studentTaskRepo->updateStudentBaseLine(array(
                                    'student_id' => $student_id,
                                    'subject' => $testDetail['subject'],
                                    'percentageObtained' => $percentageObtained,
                                ));
                            }
                        }
                    /* end code to update the base line value for student in users table */    
                }
                //end
                //email notification
                $emailParam = array(
                    'addressData' => array(
                        'to_email' => $to_email, 
                        'to_name' => $to_name,
                    ),
                    'userData' => array(
                        'name' => $to_name,
                        'pupil_name' => $student_name,
                        'test_name' => $testDetail['set_name'],
                        'percentage' => $percentageObtained
                    )
                );
                if(!empty($to_email))
                    $emailRepo->sendEmail($emailParam, 24);
                //end
            }
            return response()->json(array('questionReference' => route('test.result', encryptParam($data['attemptid']))));
        }
    }
    
    public function migrateTestReportData(UserRepository $userRepo){ die;
        ini_set('max_execution_time', 0);
        $allCompletedTest = $this->studentTaskRepo->getAllCompletedTest();
        foreach ($allCompletedTest as $completedTest){
            $student_id = $completedTest['student_id'];
            $student_name = $completedTest['first_name'] . ' ' . $completedTest['last_name'];
            $result_data = array(
                'student_id' => $student_id,
                'student_name' => $student_name,
                'tutor_id' => '',
                'tutor_name' => '',
                'teacher_id' => '',
                'teacher_name' => '',
                'school_id' => '',
                'school_name' => '',
                'class_id' => '',
                'class_name' => ''
            );
            for($attempt_num = 1; $attempt_num <= $completedTest['attempt_completed']; $attempt_num++){
                $studentParentUser = array();
                $studentSchoolTeacher = array();
                $schoolDetail= array();
                $teacherDetail= array();
                if(!empty($completedTest['tutor_id'])){
                    $studentParentUser = $userRepo->getUser(array(
                            'id' => $completedTest['tutor_id'],
                            'select' => array('id', 'email', 'first_name', 'last_name')
                        ))->get()->first();
                    if(count($studentParentUser)){
                        $result_data['tutor_id'] = $studentParentUser->id;
                        $result_data['tutor_name'] = $studentParentUser->first_name . ' ' . $studentParentUser->last_name;
                    }
                    
               }else{
                    $studentSchoolTeacher = $userRepo->getUser(array(
                            'ids' => array($completedTest['school_id'],$completedTest['teacher_id']),
                            'select' => array('id', 'email', 'first_name', 'last_name','school_name','user_type')
                        ))->orderBy('user_type')->get()->toArray();
                    if(count($studentSchoolTeacher) == 2){
                        if(isset($studentSchoolTeacher[0])){
                            $schoolDetail =  $studentSchoolTeacher[0];
                            $result_data['school_id'] = $schoolDetail['id'];
                            $result_data['school_name'] = $schoolDetail['school_name'];
                        }
                        if(isset($studentSchoolTeacher[1])){
                            $teacherDetail = $studentSchoolTeacher[1];
                            $result_data['teacher_id']      = $teacherDetail['id'];
                            $result_data['teacher_name']    = $teacherDetail['first_name'] . ' ' . $teacherDetail['last_name'];
                        }
                    }
                    //get student class
                    $studentAssigendClass = $userRepo->getStudentAssigendClass(array('student_id' => $student_id));
                    if(count($studentAssigendClass)){
                        $result_data['class_id'] = $studentAssigendClass['id'];
                        $result_data['class_name'] = $studentAssigendClass['class_name'];
                    }
                }
                

                //get Test detail
                $testDetail = $this->taskRepo->getTest($completedTest['task_id'])->toArray();
                //make test attempt complete (update student_test_attempt table)
                //get test report average
                $result_data['task_id']         = $completedTest['task_id'];
                $result_data['test_attempt_id']         = $completedTest['id'];
                $result_data['test_attempt_at']         = $completedTest['completed_at_attempt_1'];
                $result_data['last_assessment_date']         = $completedTest['last_assessment_date'];
                $result_data['attempt_num']         = $attempt_num;
                $result_data['assignment_num']         = $completedTest['assignment_num'];
                $result_data['question_set_id']         = $testDetail['question_set'];
                $result_data['set_name']         = $testDetail['set_name'];
                $result_data['subject']         = $testDetail['subject'];
                $result_data['cnt_papers']         = count(subjectPapers()[$testDetail['subject']]);
                $this->studentTaskRepo->studentTestResultData($result_data);
                $records[$completedTest['id']][] = $result_data;
            }
            
        }
        asd($records);
        
    }
    
    public function migrateRevisionReportData(UserRepository $userRepo){ die;
        ini_set('max_execution_time', 0);
        $allCompletedRevision = $this->studentTaskRepo->getAllCompletedRevision();
        foreach($allCompletedRevision as $completedRevision){
            $student_id = $completedRevision['student_id'];
            $student_name = $completedRevision['first_name'] . ' ' . $completedRevision['last_name'];
            $result_data = array(
                'student_id' => $student_id,
                'student_name' => $student_name,
                'tutor_id' => '',
                'tutor_name' => '',
                'teacher_id' => '',
                'teacher_name' => '',
                'school_id' => '',
                'school_name' => '',
                'class_id' => '',
                'class_name' => ''
            );
            $studentParentUser = array();
            $studentSchoolTeacher = array();
            $schoolDetail= array();
            $teacherDetail= array();
            if(!empty($completedRevision['tutor_id'])){
                $studentParentUser = $userRepo->getUser(array(
                        'id' => $completedRevision['tutor_id'],
                        'select' => array('id', 'email', 'first_name', 'last_name')
                    ))->get()->first();
                if(count($studentParentUser)){
                    $result_data['tutor_id'] = $studentParentUser->id;
                    $result_data['tutor_name'] = $studentParentUser->first_name . ' ' . $studentParentUser->last_name;
                }

           }else{
                $studentSchoolTeacher = $userRepo->getUser(array(
                        'ids' => array($completedRevision['school_id'],$completedRevision['teacher_id']),
                        'select' => array('id', 'email', 'first_name', 'last_name','school_name','user_type')
                    ))->orderBy('user_type')->get()->toArray();
                if(count($studentSchoolTeacher) == 2){
                    if(isset($studentSchoolTeacher[0])){
                        $schoolDetail =  $studentSchoolTeacher[0];
                        $result_data['school_id'] = $schoolDetail['id'];
                        $result_data['school_name'] = $schoolDetail['school_name'];
                    }
                    if(isset($studentSchoolTeacher[1])){
                        $teacherDetail = $studentSchoolTeacher[1];
                        $result_data['teacher_id']      = $teacherDetail['id'];
                        $result_data['teacher_name']    = $teacherDetail['first_name'] . ' ' . $teacherDetail['last_name'];
                    }
                }
                //get student class
                $studentAssigendClass = $userRepo->getStudentAssigendClass(array('student_id' => $student_id));
                if(count($studentAssigendClass)){
                    $result_data['class_id'] = $studentAssigendClass['id'];
                    $result_data['class_name'] = $studentAssigendClass['class_name'];
                }
            }
        
            $revisionDetail = $this->taskRepo->getRevision($completedRevision['task_id'])->toArray();

           

            $percentageObtained = round((($completedRevision['mark_obtain']/$completedRevision['total_marks'])*100),2);

            $result_data['task_id']         = $completedRevision['task_id'];
            $result_data['attempt_num']     = $completedRevision['attempt'];
            $result_data['total_time']      = $completedRevision['quesmaxtime'];
            $result_data['time_take']       = calculateTaskCompleteTime(array(
                    'quesmaxtime' => $completedRevision['quesmaxtime'],
                    'remainingtime' => $completedRevision['remainingtime'],
                    'completetime' => $completedRevision['attempt_complete_time']
                ));
            $result_data['percentage_obtained']   = $percentageObtained;
            $result_data['strand_id']       = $revisionDetail['strand_id'];
            $result_data['strand_name']     = $revisionDetail['strand'];
            $result_data['substrand_id']    = $revisionDetail['substrand_id'];
            $result_data['substrand_name']  = $revisionDetail['substrand'];
            $result_data['subject']  = $revisionDetail['subject'];
            //store average mark % and time line.
            $this->studentTaskRepo->studentRevisionResultData($result_data);
        }
    }
    
    public function migrateTestStrandReportData(){ die;
        ini_set('max_execution_time', 0);
        $allCompletedTest = $this->studentTaskRepo->getAllCompletedTest();
        foreach ($allCompletedTest as $completedTest){
            $student_id = $completedTest['student_id'];
            for($attempt_num = 1; $attempt_num <= $completedTest['attempt_completed']; $attempt_num++){
                //get Test detail
                $testDetail = $this->taskRepo->getTest($completedTest['task_id'])->toArray();
                //test strand report (Gap Analysis)
                $this->studentTaskRepo->studentTestStrandReoprt(array(
                    'student_id' => $student_id,
                    'test_attempt_id' => $completedTest['id'],
                    'subject' => $testDetail['subject'],
                    'attempt_num' => $attempt_num,
                    'cnt_papers' => count(subjectPapers()[$testDetail['subject']]),
                    'set_id' => $testDetail['question_set']
                ));
            }
        }
    }
    public function updateTestAttemptCompletedate(){die;
        ini_set('max_execution_time', 0);
        $this->studentTaskRepo->updateTestAttemptCompletedate(['attempt' => 3,'paper_id' => 5]);
    }
    
    public function updateStudentBaseLineData(){ die;
        ini_set('max_execution_time', 0);
        $param['subject'] = 'English';
        $allData = $this->studentTaskRepo->getAllStudentBaseLine($param);
        foreach ($allData as $key=>$val){
            $updateParam['subject'] = $param['subject'];
            $updateParam['student_id'] = $val->student_id;
            $updateParam['percentageObtained'] = $val->att1_avg_marks;
            $this->studentTaskRepo->updateAllStudentBaseLine($updateParam);
        }
        die;
    }
}

    