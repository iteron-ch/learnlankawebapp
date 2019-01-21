<?php

namespace App\Http\Controllers;

use App\Repositories\StudentTaskRepository;
use App\Repositories\StrandRepository;
use App\Models\User;

class StudentTaskController extends Controller {

    /**
     * The StudentTaskRepository instance.
     *
     * @var App\Repositories\StudentTaskRepository
     */
    protected $studentTaskRepo;

    /**
     * The StrandRepository instance.
     * @var App\Repositories\StrandRepository
     */
    protected $strandRepo;
    protected $paperStatus;

    /**
     * Create a new StudentTaskController instance.
     *
     * @param  App\Repositories\StudentTaskRepository 
     * @return void
     */
    public function __construct(StudentTaskRepository $studentTaskRepo, StrandRepository $strandRepo) {
        $this->studentTaskRepo = $studentTaskRepo;
        $this->strandRepo = $strandRepo;
        $this->paperStatus = array(
            'inprogress' => 1,
            'notapptemped' => 2,
            'completed' => 3
        );
    }

    /**
     * task landing page
     * @author     Icreon Tech - dev2.
     * @param type $subject
     * @param type $subjectId
     * @return type
     */
    public function task($status = NULL) {
        $studentId = session()->get('user')['id'];
        $tutor_id = session()->get('user')['tutor_id'];
        $status = $status == NULL ? 'pending' : $status;
        $data['status'] = $status;
        if (!empty($tutor_id)) {
            return redirect('error404');
        }
        $subjectPaper = subjectPapers();
        $data['subjectPaper'] = $subjectPaper;
        $data['paperAttempts'] = paperAttempts();
        $data['assignedTask'] = $this->studentTaskRepo->getStudentAssignedTask(array(
            'student_id' => $studentId,
            'key_stage' => session()->get('user')['key_stage'],
            'year_group' => session()->get('user')['year_group'],
            'status' => $status,
            'mathtime' => $subjectPaper[MATH][3]['time'],
            'englishtime' => $subjectPaper[ENGLISH][5]['time']
        ));
        $data['noRecordMsg'] = $this->studentTaskRepo->studentNoTaskMsg($status);
		$data['status'] = $status;
        return view('front.studenttask.tasklist', $data);
    }

    /**
     * test landing page
     * @author     Icreon Tech - dev2.
     * @param type $subject
     * @param type $subjectId
     * @return type
     */
    public function test() {
        return view('front.studenttask.test');
    }

    /**
     * test landing page
     * @author     Icreon Tech - dev2.
     * @param type $subject
     * @return type
     */
    public function testSubject($subject) {
        $data['subject'] = $subject;
        $studentId = session()->get('user')['id'];
        $subjectVar = getSubject($subject);
        $data['subjectPaper'] = subjectPapers();
        $data['paperAttempts'] = paperAttempts();

        $data['assignedTest'] = $this->studentTaskRepo->getStudentAssignedActiveTest(array(
            'student_id' => $studentId,
            'tutor_id' => session()->get('user')['tutor_id'],
            'task_subject' => $subjectVar,
            'key_stage' => session()->get('user')['key_stage'],
            'year_group' => session()->get('user')['year_group'],
        ));
/*        $data['assignedTest'] = array();
        $data['assignedTest'][] = array('set_name'=>'test 1');
        $data['assignedTest'][] = array('set_name'=>'test 2');;
        $data['assignedTest'][] = array('set_name'=>'test 10');
        $data['assignedTest'][] = array('set_name'=>'test 3');
  */
        $data['assignedTest'] = multilevel_array_sort($data['assignedTest'], 'set_name', SORT_ASC);
 //       asd($data['assignedTest']);
        return view('front.studenttask.test-list', $data);
    }

    /**
     * test paper detail
     * @author     Icreon Tech - dev2.
     * @param type $subject
     * @param type $subjectId
     * @return type
     */
    public function testPaper($taskattemptid) {
        $taskattemptid = decryptParam($taskattemptid);
        //get attempt wise test paper
        $studentTestPaper = $this->studentTaskRepo->getStudentAttemptTestPaperStatus(array(
            'test_attempt_id' => $taskattemptid
        ));
        //set attempt status (completed or inprogress)
        foreach ($studentTestPaper as $attempPaper) {
            if ($attempPaper['attempt_at'] != NULL_DATETIME) {
                if ($attempPaper['status'] == COMPLETED) {
                    $paperStatus = $this->paperStatus['completed'];
                } else {
                    $paperStatus = $this->paperStatus['inprogress'];
                }
            } else {
                $paperStatus = $this->paperStatus['notapptemped'];
            }
            $paperAttemptDetail[$attempPaper['attempt']][$attempPaper['paper_id']] = ['status' => $paperStatus, 'id' => encryptParam($attempPaper['id'])];
        }
        //Test attempt tab opened or disabled logic
        $i = 0;
        foreach ($paperAttemptDetail as $key => $paperAttempt) {
            $tempPaperAttempt = array();
            $tempPaperAttempt = array_keys($paperAttempt);
            foreach ($tempPaperAttempt as $indexk => $paperId) {
                if ($paperAttempt[$paperId]['status'] == $this->paperStatus['completed']) {
                    $paperAttempt[$paperId]['clickable'] = FALSE;
                } else {
                    $tabActiveStatus[$key] = TRUE;
                    if ($indexk == 0) {
                        $paperAttempt[$paperId]['clickable'] = TRUE;
                    } else {
                        $paperAttempt[$paperId]['clickable'] = $paperAttempt[$paperId - 1]['status'] == $this->paperStatus['completed'] ? TRUE : FALSE;
                    }
                }
            }
            if ($i == 0) {
                $dataPaperAttemptDetail[$key]['tabactive'] = 1;
            } else {
                $dataPaperAttemptDetail[$key]['tabactive'] = isset($tabActiveStatus[$key - 1]) ? 0 : 1;
            }
            $dataPaperAttemptDetail[$key]['papers'] = $paperAttempt;
            $i++;
        }
        //end
        return response()->json($dataPaperAttemptDetail);
    }

    /**
     * test paper detail
     * @author     Icreon Tech - dev2.
     * @param type $subject
     * @param type $subjectId
     * @return type
     */
    public function testPaperDetail($option, $taskorattempt) {
        $studentId = session()->get('user')['id'];
        $tutorId = session()->get('user')['tutor_id'];
        //very first attempt for test
        if ($option == 'paper') {
            //check wether student has valid test assigned, else return 404 (or any specified page or message)
            $testAssigned = $this->studentTaskRepo->getStudentTestDetail(array(
                'student_id' => $studentId,
                'tutor_id' => $tutorId,
                'task_id' => decryptParam($taskorattempt),
            ));
            if (!$testAssigned) {
                return redirect('error404');
            }
            $testAssigned = $testAssigned->toArray();
            //end
            $attemptPaperId = $this->studentTaskRepo->addStudentTestAttempt(array(
                'student_id' => $studentId,
                'task_id' => decryptParam($taskorattempt),
                'subject' => $testAssigned['subject'],
                'assign_id' => isset($testAssigned['assign_id']) ? $testAssigned['assign_id'] : 0
            ));
            //end
        } else {// get studenttest (student test attemp id)
            $attemptPaperId = decryptParam($taskorattempt);
        }
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
            'tutor_id' => $tutorId,
            'id' => $studentStoredTestPaper['student_test_attempt_id']
        ));
        if (!$testPaperDetail) {
            return redirect('error404');
        }
        $num_question = $studentStoredTestPaper['num_question'];
        //end
        //update test paper detail, if it is first attempt (status is pending)
        if ($studentStoredTestPaper['status'] == 'Pending') {
            $dataQuestionTestPaper = $this->studentTaskRepo->addQuestionTestPaper($studentStoredTestPaper['id'], array(
                'question_set_id' => $testPaperDetail['question_set'],
                'question_paper_id' => $studentStoredTestPaper['paper_id'],
                'key_stage' => session()->get('user')['key_stage'],
                'year_group' => session()->get('user')['year_group'],
                'subject' => $testPaperDetail['subject']
            ));

            $num_question = $dataQuestionTestPaper['num_question'];
        }
        //end
        $data['isCompleted'] = $studentStoredTestPaper['status'] == COMPLETED ? TRUE : FALSE;
        //end
        $subject = $testPaperDetail['subject'];
        $subjectPaper = subjectPapers()[$subject];
        $paper_name = $subjectPaper[$studentStoredTestPaper['paper_id']]['name'];
        $paper_time = $subjectPaper[$studentStoredTestPaper['paper_id']]['time'] / 60;
        $attempt_no = 'Attempt ' . $studentStoredTestPaper['attempt'];
        if (session()->get('user')['tutor_id']) {
            $user = user::findOrFail(session()->get('user')['tutor_id'])->toArray();
            $testPaperDetail['first_name'] = $user['first_name'];
            $testPaperDetail['last_name'] = $user['last_name'];
        }
        $testPaperDetail['attempt_id'] = $studentStoredTestPaper['id'];
        $testPaperDetail['paper_id'] = $studentStoredTestPaper['paper_id'];
        $testPaperDetail['num_question'] = $num_question;
        $testPaperDetail['paper_time'] = $paper_time;
        $data['num_question'] = $num_question;
        $data['studentTestPaperDetail'] = $testPaperDetail;
        $subject = $subject == ENGLISH ? strtolower(ENGLISH) : strtolower(MATHS);
        $data['subject'] = $subject;
        $data['trait'] = array('trait_1' => $testPaperDetail['set_name'], 'trait_2' => $paper_name, 'trait_3' => $attempt_no, 'back_link' => route('studenttask.testsubject', $subject));
        //asd($data);
        return view('front.studenttask.test-detail', $data);
    }

    /**
     * revision landing page
     * @author     Icreon Tech - dev2.
     * @param type $subject
     * @param type $subjectId
     * @return type
     */
    public function revision() {
        return view('front.studenttask.revision');
    }

    /**
     * revision strand page
     * @author     Icreon Tech - dev2.
     * @param type $subject
     * @param type $subjectId
     * @return type
     */
    public function revisionSubject($subject) {
        $data['subject'] = $subject;
        $studentId = session()->get('user')['id'];
        $strandResult = $this->strandRepo->getStrandTree(false);
        $subjectVar = getSubject($subject);
        $data['strand'] = $strandResult['strands'][$subjectVar];
        $data['strandAtt'] = $this->studentTaskRepo->strandAttributesList()[$subjectVar];
        $data['assigned_strands'] = $this->studentTaskRepo->studentActiveRevisionStrands(array(
            'student_id' => $studentId,
            'tutor_id' => session()->get('user')['tutor_id'],
            'task_subject' => $subjectVar,
            'key_stage' => session()->get('user')['key_stage'],
            'year_group' => session()->get('user')['year_group'],
        ));
        if (strtolower($subject) == strtolower(MATHS)) {
            return view('front.studenttask.revision-maths', $data);
        } else if (strtolower($subject) == strtolower(ENGLISH)) {
            return view('front.studenttask.revision-english', $data);
        }
    }

    /**
     * this is used to show the sub revisions 
     * @author     Icreon Tech - dev1.
     * @param type $subject
     * @param type $subjectId
     * @return type
     */
    public function revisionStrand($subject, $strand) {
        $strandId = decryptParam($strand);
        $strand = $this->strandRepo->getById($strandId)->toArray();
        $subject = strtolower($subject);
        $data['subject'] = $subject;
        $data['trait'] = array('trait_1' => $strand['strand'], 'back_link' => route('studenttask.revisionsubject', $subject));
        $data['revisions']['strand'] = array('strandId' => $strand['id'], 'strandName' => $strand['strand'], '');
        $subjectVar = getSubject($subject);
        $studentId = session()->get('user')['id'];
        $data['revisions']['assigned_revision'] = $this->studentTaskRepo->getStudentAssignedRevisions(array(
            'tutor_id' => session()->get('user')['tutor_id'],
            'student_id' => $studentId,
            'task_subject' => $subjectVar,
            'task_strand' => $strand['id'],
            'key_stage' => session()->get('user')['key_stage'],
            'year_group' => session()->get('user')['year_group'],
        ));
        $data['revisions']['assigned_revision'] = multilevel_array_sort($data['revisions']['assigned_revision'], 'substrand', SORT_ASC);
        // asd($data['revisions']['assigned_revision']);
        
        return view('front.studenttask.assigned-revision', $data);
    }

    public function revisionDetail($taskId) {
        $taskId = decryptParam($taskId);
        $studentId = session()->get('user')['id'];
        $tutorId = session()->get('user')['tutor_id'];
        $revisionAssigned = $this->studentTaskRepo->getStudentRevisionDetail(array(
            'tutor_id' => $tutorId,
            'student_id' => $studentId,
            'task_id' => $taskId
        ));
        if (!$revisionAssigned) {
            return redirect('error404');
        }
        $revisionAssigned = $revisionAssigned->toArray();
        $studentStoredRevision = $this->studentTaskRepo->getStudentStoredRevision(array(
            'task_id' => $revisionAssigned['id'],
            'student_id' => $studentId
        ));
        // check wether this revision is already attempt, 
        // if no attempt made, make a new entry for this revision
        // if attempt but not COMPLETED, allow continue for this attemped revision (no new entry for this revision)
        // if attempt and COMPTETED, make a entry for same revision with +1 increased attempt.  
        $isStudentStoredRevision = FALSE;
        $data['isCompleted'] = FALSE;
        $attempt = 1;
        if (count($studentStoredRevision)) {
            if ($studentStoredRevision['status'] == COMPLETED) {
                $attempt = $studentStoredRevision['attempt'] + 1;
            } else {
                $isStudentStoredRevision = TRUE;
            }
        }
        if (!$isStudentStoredRevision) {
            //prepare student revision question against a task
            $saveParams = array(
                'task_id' => $revisionAssigned['id'],
                'subject' => $revisionAssigned['subject'],
                'student_id' => $studentId,
                'strand_id' => $revisionAssigned['strand_id'],
                'substrand_id' => $revisionAssigned['substrand_id'],
                'key_stage' => session()->get('user')['key_stage'],
                'year_group' => session()->get('user')['year_group'],
                'questionIdNotIn' => array(),
                'limit' => REVISION_QUS_LIMIT,
                'attempt' => $attempt
            );
            if (empty($tutorId)) {
                $saveParams['assign_id'] = $revisionAssigned['assign_id'];
                $saveParams['difficulty'] = !empty($revisionAssigned['difficulty']) ? @explode(",", $revisionAssigned['difficulty']) : array();
            }
            $studentStoredRevision = $this->studentTaskRepo->saveStudentStoredRevision($saveParams);
        }
        //$data['isCompleted'] = $studentStoredRevision['status'] == COMPLETED ? TRUE : FALSE;
        //end
        $data['student_revisionid'] = $studentStoredRevision['id'];
        $revisionAssigned['question_num'] = $studentStoredRevision['num_question'];
        if (session()->get('user')['tutor_id']) {
            $user = User::findOrFail(session()->get('user')['tutor_id'])->toArray();
            $revisionAssigned['first_name'] = $user['first_name'];
            $revisionAssigned['last_name'] = $user['last_name'];
        }
        $data['revisionAssigned'] = $revisionAssigned;
        $subject = $revisionAssigned['subject'] == ENGLISH ? strtolower(ENGLISH) : strtolower(MATHS);
        $data['subject'] = $subject;
        $data['trait'] = array('trait_1' => $revisionAssigned['strand'], 'trait_2' => $revisionAssigned['substrand'], 'back_link' => route('studenttask.strand', [$subject, encryptParam($revisionAssigned['strand_id'])]));
        return view('front.studenttask.revision-detail', $data);
    }

}
