<?php

/**
 * This is used for task save/edit.
 * @package    task
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;

use App\Models\Task;
use App\Models\Taskassignment;
use App\Models\Taskstudents;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Mail;

/**
 * This is used for task save/edit.
 * @package    task
 * @author     Icreon Tech  - dev2.
 */
class RegisterRepository extends BaseRepository {

    protected $user;
    protected $taskassignment;
    protected $taskstudent;

    public function __construct(User $user, Task $task, Taskassignment $taskassignment, TaskStudents $taskstudent)
    {
        $this->user = $user;
        $this->task = $task;
        $this->taskassignment = $taskassignment;
        $this->taskstudent = $taskstudent;
    }

    /**
     * send verification mail to user.
     * @author     Icreon Tech - dev2.
     * @param  User  $user
     */
    public function sendMail(User $user)
    {
        Mail::send('auth.verification', ['user' => $user], function ($m) use ($user) {
            $m->from('pasanjg@gmail.com', 'LearnLanka');

            $m->to($user->email, $user->username)->subject('Email Verification');
        });
    }


    /**
     * get all active tests.
     * @author     Icreon Tech - dev2.
     *
     */
    public function getTests()
    {
        //return $tests = Task::whereId(130)->first();
        //return $tests = Task::where(['task_type' => TEST, 'status' => ACTIVE])->whereNotNull('question_set')->get()->toArray();
        return Task::get()->toArray();
    }


    /**
     * save task assignment.
     * @author     Icreon Tech - dev2.
     * @param  Task  $task_id, User $user_id
     */
    public function saveAssignment($taskId, $studentId)
    {
        $taskassignment = new $this->taskassignment;
        $taskassignment->task_id = $taskId;
        $taskassignment->created_by = 23;
        $taskassignment->updated_by = 23;
        //$taskassignment->student_source = $inputs['selection_type'];
        $taskassignment->assign_date = Carbon::today();
        $taskassignment->completion_date = Carbon::today()->addDays(2);
        $taskassignment->difficulty = !empty($inputs['difficulty']) ? implode(",", $inputs['difficulty']) : '';
        $taskassignment->student_num = 1;
        $taskassignment->save();

        $this->saveAssignedStudent($taskId, $taskassignment->id, $studentId);
    }


    /**
     * save assigned student.
     * @author     Icreon Tech - dev2.
     * 
     */
    public function saveAssignedStudent($taskId, $assignId, $studentId)
    {
        $taskstudent = new $this->taskstudent;
        $taskstudent->assign_id = $assignId;
        $taskstudent->task_id = $taskId;
        $taskstudent->student_id = $studentId;
        $taskstudent->save();
    }


    /**
     * assign tests to student.
     * @author     Icreon Tech - dev2.
     * 
     */
    public function assignTest($studentId)
    {
        $tests  = $this->getTests();

        if (isset($tests) && count($tests) > 0) {
            foreach ($tests as $key => $val) {
                $this->saveAssignment($val['id'], $studentId);
            }
        }
    }


    /**
     * get assigned tests to student.
     * @author     Icreon Tech - dev2.
     * 
     */
    public function getAssignedTests($student_id)
    {
        return $this->task
                ->join('taskstudents', 'taskstudents.task_id', '=', 'task.id')
                ->join('taskassignments','taskassignments.task_id', '=', 'task.id')
                ->select('task.id')
                ->distinct('task.id')
                ->where(['taskstudents.student_id' => $student_id])
                ->get()->toArray();
    }


    /**
     * assign new tests to student.
     * @author     Icreon Tech - dev2.
     * 
     */
    public function assignNewTasks($student_id)
    {
        $assigned = array();
        $tests = array();

        foreach($this->getAssignedTests($student_id) as $key => $val)
        {
            $assigned[] = $val['id'];
        }

        foreach($this->getTests() as $key => $val)
        {
            $tests[] = $val['id'];
        }

        $unassigned = array_diff($tests, $assigned);
        
        if (isset($unassigned) && count($unassigned) > 0) {
            foreach($unassigned as $task)
            {
                $this->saveAssignment($task, $student_id);
            }
            return redirect('/')->with('ok', trans('You have New Tasks assigned'));
            //return $unassigned;

        }
        // else{
        //     //return $unassigned;
        //     return redirect('/')->with('error', trans('No New Tasks assigned'));
        // }
    }
    
}
