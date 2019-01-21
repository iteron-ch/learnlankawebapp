<?php

/**
 * This controller is used for Task.
 * @package    task
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\TaskRepository;
use Illuminate\Http\Request;
use Datatables;
use Illuminate\Support\Collection;

/**
 * This controller is used for task centre.
 * @author     Icreon Tech - dev1.
 */
class TaskController extends Controller {

    /**
     * The TaskRepository instance.
     * @var App\Repositories\TaskRepository
     */
    protected $taskRepo;

    /**
     * Create a new TaskController instance.
     * @param  App\Repositories\TaskRepository $taskRepo
     * @return void
     */
    public function __construct(TaskRepository $taskRepo) {
        $this->taskRepo = $taskRepo;
    }

   
    /**
     * get the list of intervention used in tests
     * @param Request $request
     * @return type
     */
    public function interventionTopics(Request $request) {

        $param['limit'] = 5;
        if ($request->has('subject'))
            $subject = $request->get('subject');

        $param['subject'] = $subject;
        /*
        $topicArray = $this->taskRepo->getInterventionTopics($param)->toArray();

          foreach ($topicArray as $key => $val) {
          if (strtolower($subject) == strtolower(MATH)) {
          $data[$key]['strand_math'] = isset($topicArray[$key]['strand']) ? $topicArray[$key]['strand'] : 'NA';
          $data[$key]['percent'] = (($key + 1) * 9) . '%';
          } else {
          $data[$key]['strand_english'] = isset($topicArray[$key]['strand']) ? $topicArray[$key]['strand'] : 'NA';
          $data[$key]['percent'] = (($key + 1) * 9) . '%';
          }
          } */
        $data[0]['strand_math'] = 'Compare, describe and order measures';
        $data[0]['percent'] = '15%';
        $data[1]['strand_math'] = 'Negative numbers';
        $data[1]['percent'] = '11%';
        $data[2]['strand_math'] = 'Angles – measuring and properties';
        $data[2]['percent'] = '26%';
        $data[3]['strand_math'] = 'Generate and describe linear number sequences';
        $data[3]['percent'] = '22%';
        $data[4]['strand_math'] = 'Number sentences involving two unknowns';
        $data[4]['percent'] = '17%';
        
        $data[0]['strand_english'] = 'Co-ordinating conjunctions';
        $data[0]['percent'] = '11%';
        $data[1]['strand_english'] = 'Relative clauses';
        $data[1]['percent'] = '17%';
        $data[2]['strand_english'] = 'Adverbs';
        $data[2]['percent'] = '30%';
        $data[3]['strand_english'] = 'Formal and informal structures';
        $data[3]['percent'] = '15%';
        $data[4]['strand_english'] = 'Synonyms and antonyms';
        $data[4]['percent'] = '18%';
        
        
        
        /* $param['subject'] = MATH;
          $mathTopic = $this->taskRepo->getInterventionTopics($param)->toArray();
          $param['subject'] = ENGLISH;
          $englishTopic = $this->taskRepo->getInterventionTopics($param)->toArray();
          if(count($englishTopic)>count($mathTopic)) {
          foreach($englishTopic as $key=>$val) {
          $data[$key]['strand_english'] = $englishTopic[$key]['strand'];
          $data[$key]['strand_math'] = isset($mathTopic[$key]['strand'])?$mathTopic[$key]['strand']:'NA';
          }
          }
          else if(count($englishTopic)<count($mathTopic)) {
          foreach($mathTopic as $key=>$val) {
          $data[$key]['strand_english'] = isset($englishTopic[$key]['strand'])?$englishTopic[$key]['strand']:'NA';
          $data[$key]['strand_math'] = $mathTopic[$key]['strand'];
          }
          } */

        $topics = new Collection($data);
        return Datatables::of($topics)->make(true);
    }

    public function activityTopics(Request $request) {

        $source = $request->get('source');
        if ($source == 'tutor') {
            $data = array();
            $data[0]['topic'] = 'David has completed Combining words,phrases and clauses on 17/11/2015';
            //$data[0]['date'] = '17/11/2015';
            $data[1]['topic'] = 'Tina has completed revisions questions for Toipc Geometry - properties of shapes 17/11/2015';
            //$data[1]['date'] = '17/11/2015';
        } else if ($source == 'school') {
            $data = array();
            $data[0]['topic'] = 'Class 3 has been assigned to Combining words,phrases and clauses on 16/11/2015';
            // $data[0]['date'] = '16/11/2015';
            $data[1]['topic'] = 'Class 5 has been assigned to Geometry - properties of shapes on 16/11/2015';
            // $data[1]['date'] = '16/11/2015';
        }
        $topics = new Collection($data);
        return Datatables::of($topics)->make(true);
    }

    public function dashboardtestResult(Request $request) {
        $data = array();


        $data[0]['class'] = '6A';
        $data[0]['test_mame'] = '<strong>Set One</strong>';
        $data[0]['marks'] = '';

        $data[1]['class'] = '';
        $data[1]['test_mame'] = 'Maths Paper 1';
        $data[1]['marks'] = '64%';


        $data[2]['class'] = '';
        $data[2]['test_mame'] = 'Maths Paper 2';
        $data[2]['marks'] = '30%';

        $data[3]['class'] = '';
        $data[3]['test_mame'] = 'Maths Paper 3';
        $data[3]['marks'] = '30%';


        $data[4]['class'] = '';
        $data[4]['test_mame'] = '<strong>SPAG</strong>';
        $data[4]['marks'] = '';

        $data[5]['class'] = '';
        $data[5]['test_mame'] = 'Spelling';
        $data[5]['marks'] = '64%';

        $data[6]['class'] = '';
        $data[6]['test_mame'] = 'P&G';
        $data[6]['marks'] = '64%';



        $data[7]['class'] = '6A';
        $data[7]['test_mame'] = '<strong>Logical Set One</strong>';
        $data[7]['marks'] = '';

        $data[8]['class'] = '';
        $data[8]['test_mame'] = 'Maths Paper 1';
        $data[8]['marks'] = '64%';


        $data[9]['class'] = '';
        $data[9]['test_mame'] = 'Maths Paper 2';
        $data[9]['marks'] = '30%';

        $data[10]['class'] = '';
        $data[10]['test_mame'] = 'Maths Paper 3';
        $data[10]['marks'] = '30%';


        $data[11]['class'] = '';
        $data[11]['test_mame'] = '<strong>SPAG</strong>';
        $data[11]['marks'] = '';

        $data[12]['class'] = '';
        $data[12]['test_mame'] = 'Spelling';
        $data[12]['marks'] = '64%';

        $data[13]['class'] = '';
        $data[13]['test_mame'] = 'P&G';
        $data[13]['marks'] = '64%';


        $topics = new Collection($data);
        return Datatables::of($topics)->make(true);
    }
    
    public function assignedStudent($assignedId,$classGroupId = NULL){
        $data['assignedId'] = $assignedId;
        $data['classGroupId'] = $classGroupId;
        return view('admin.task.assignlist', $data);
    }
    
    public function assignedStudentList(Request $request, $assignedId,$classGroupId = NULL){
        $params['assign_id'] = $assignedId;
        $params['student_source_id'] = $classGroupId;
        $taskAssignedStudent = $this->taskRepo->getTaskAssignedStudentList($params);
        return Datatables::of($taskAssignedStudent)
                        ->editColumn('completed', function ($test) {
                            if($test->attempt_status == PENDING){
                                return '<span class="glyphicon glyphicon-remove" style="color:red;"></span>';
                            }else{
                                return '<span class="glyphicon glyphicon-ok" style="color:green;"></span>';
                            }
                        })
                        ->make(true);
    }
    public function taskSetPreview($id){
        $param['id'] = decryptParam($id);
        $questionResult = $this->taskRepo->getSetQuestionList($param);
       // asd($questionResult);
        
        $data['questionResult'] = $questionResult;
        return view('admin.task.tasksetpreview', $data);        
    }    
   
}
