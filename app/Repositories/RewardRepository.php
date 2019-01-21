<?php

/**
 * This is used for reward save/edit.
 * @package    reward
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;

use App\Models\Reward;
use App\Models\Rewardstudent;
use DB;
use Carbon\Carbon;

/**
 * This is used for reward save/edit.
 * @package    reward
 * @author     Icreon Tech  - dev2.
 */
class RewardRepository extends BaseRepository {

    protected $rewardstudent;

    /**
     * Create a new RewardRepository instance.
     *
     * @param  App\Models\Reward $reward
     * @return void
     */
    public function __construct(Reward $reward, Rewardstudent $rewardstudent) {
        $this->model = $reward;
        $this->rewardstudent = $rewardstudent;
    }

    /**
     * Save Test/Revision.
     * @author     Icreon Tech - dev2.
     * @param type $inputs
     * @param type $questionset
     */
    public function save($inputs, $model) {
        if (isset($inputs['id'])) {
            if ($inputs['user_type'] == SCHOOL || $inputs['user_type'] == TEACHER) {
                DB::table('reward_students')->where('rewards_id', '=', $inputs['id'])->delete();
            }
            $model->status = $inputs['status'];
            $model->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
        } else {
            $model->task_type = $inputs['task_type'];
            $model->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $model->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
        }
        $model->subject = $inputs['subject'];
        if ($inputs['task_type'] == TEST) {
            $model->question_set = $inputs['question_set'];
        } else {
            $model->strand = $inputs['strand'];
            $model->substrand = $inputs['substrand'];
        }
        $model->percent_min = $inputs['percent_min'];
        $model->percent_max = $inputs['percent_max'];
        $model->studentawards_id = $inputs['studentawards_id'];
        $model->user_type = $inputs['user_type'];
        
        if ($inputs['user_type'] == SCHOOL || $inputs['user_type'] == TEACHER) {
            $model->student_source = $inputs['student_source'];
            $students = array_unique($inputs['students']);
        }
        $lastId = $model->save() ? $model->id : FALSE;
        if ($lastId && ($inputs['user_type'] == SCHOOL || $inputs['user_type'] == TEACHER)) {
            foreach ($students as $key => $value) {
                $Arr = array(
                    'rewards_id' => $lastId,
                    'student_id' => $value,
                    'created_at' => Carbon::now()->toDateTimeString(),
                );
                $student[] = $Arr;
            }
            //asd($student);
            DB::table('reward_students')->insert($student);
        }
    }

    /**
     * create new test/revision.
     * @author     Icreon Tech - dev2.
     * @param  array  $inputs
     */
    public function store($inputs) {
        $model = new $this->model;
        $this->save($inputs, $model);
    }

    /**
     * Update test/revision.
     * @author     Icreon Tech - dev2.
     * @param  array  $inputs
     * @param  int $id
     * @return void
     */
    public function update($inputs, $id) {
        $model = $this->getById($id);
        $this->save($inputs, $model);
    }

    /**
     * get test list grid query.
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getRewardsTestList($params) {
        return $this->model
                        ->select([
                            'rewards.id', 'rewards.percent_min', 'rewards.percent_max', 'rewards.subject', 'rewards.created_at',
                            'rewards.updated_at', 'rewards.status', 'questionsets.set_name', 'studentawards.title'
                        ])
                        ->join('questionsets', 'rewards.question_set', '=', 'questionsets.id')
                        ->join('studentawards', 'rewards.studentawards_id', '=', 'studentawards.id')
                        ->where('rewards.status', '!=', DELETED)
                        ->where('rewards.created_by', '=', $params['created_by'])
                        ->where('rewards.task_type', '=', $params['task_type'])
                        ->where('questionsets.status', '!=', DELETED);
    }

    /**
     * get revision list grid query.
     * @author     Icreon Tech - dev2.
     * @param  array  $params
     * @return response
     */
    public function getRewardsRevisionList($params) {
        return $this->model
                        ->select([
                            'rewards.id', 'rewards.percent_min', 'rewards.percent_max', 'rewards.subject', 'rewards.created_at',
                            'rewards.updated_at', 'rewards.status', '.strands.strand AS strand',
                            '.substrands.strand AS substrand', 'studentawards.title'
                        ])
                        ->join('studentawards', 'rewards.studentawards_id', '=', 'studentawards.id')
                        ->join('strands AS strands', 'rewards.strand', '=', 'strands.id')
                        ->join('strands AS substrands', 'rewards.substrand', '=', 'substrands.id','left')
                        ->where('rewards.status', '!=', DELETED)
                        ->where('rewards.created_by', '=', $params['created_by'])
                        ->where('rewards.task_type', '=', $params['task_type']);
    }

    public function getRewardsData($id) {
        return $this->model->findOrFail($id)->toArray();
    }

    public function getRewardsStudents($params) {
        $query = $this->rewardstudent->select(['student_id']);
        if (isset($params['rewards_id'])) {
            $query->where('rewards_id', '=', $params['rewards_id']);
        }
        return $query->get()->toArray();
    }

    /**
     * Update a rewards status.
     *
     * @param  array  $inputs

     * @return void
     */
    public function destroyReward($inputs, $id) {
        $user = $this->model->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $user->updated_by = $inputs['updated_by'];
        $user->deleted_at = $dateTime;
        $user->status = DELETED; // deleted
        $user->save();
        DB::table('reward_students')->where('rewards_id', '=', $id)->delete();
    }

}
