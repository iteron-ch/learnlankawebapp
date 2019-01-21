<?php

namespace App\Repositories;

use App\Models\Questionset;
use App\Models\Task;
use Carbon\Carbon;
use DB;

class QuestionSetRepository extends BaseRepository {

    /**
     * Create a new UserRepository instance.
     *
     * @param  App\Models\User $user
     * @param  App\Models\Student $student
     * @return void
     */
    public function __construct(Questionset $model) {
        $this->model = $model;
    }

    public function save($inputs, $questionset) {
        $questionset->ks_id = $inputs['ks_id'];
        $questionset->year_group = $inputs['year_group'];
        $questionset->subject = $inputs['subject'];
        $questionset->set_group = $inputs['set_group'];
        $questionset->set_name = $inputs['set_name'];
        $questionset->is_print = $inputs['is_print'];
        if (isset($inputs['created_by'])) {
            $questionset->created_by = $inputs['created_by'];
        }
        if (isset($inputs['status'])) {
            $questionset->status = $inputs['status'];
        }
        $questionset->updated_by = $inputs['updated_by'];
        $questionset->save();

        //save test for this question set, if question set status is PUBLISHED
        if ($questionset->id && $questionset->status == PUBLISH) {
            Task::addTask([
                'task_type' => TEST,
                'subject' => $questionset->subject,
                'key_stage' => $questionset->ks_id,
                'year_group' => $questionset->year_group,
                'question_set' => $questionset->id
            ]);
        }
    }

    public function store($inputs) {
        $questionset = new $this->model;
        $this->save($inputs, $questionset);
    }

    public function update($inputs, $id) {
        $id = decryptParam($id);
        $questionset = $this->model->where('id', '=', $id)->first();
        $this->save($inputs, $questionset);
    }

    public function showQuestionset($id) {
        $questionset = $this->model
                        ->where('id', '=', $id)
                        ->select(['ks_id', 'group_id', 'set_name',
                            'subject', 'set_group', 'year_group',
                            'tot_questions', 'tot_ques_published', 'tot_ques_draft',
                            'tot_contributors', 'ispublished', 'status',
                            'created_at', 'updated_at',
                        ])
                        ->get()->first();
        return $questionset;
    }

    public function destroyQuestionset($inputs, $id) {
        $questionset = $this->model->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $questionset->updated_by = $inputs['updated_by'];
        $questionset->deleted_at = $dateTime;
        $questionset->status = DELETED;
        $questionset->save();
    }

    public function getQuestionset() {
        return $this->model->select(['id', 'ks_id', 'set_name', 'set_group', 'status', 'created_at', 'updated_at', 'tot_questions', 'tot_ques_published', 'tot_ques_draft', 'tot_contributors','is_print'])
                        ->where('status', '!=', DELETED);
    }

    public function getPaperwisePublishedSetQuestions($params) {
        return $this->model->select(['questions.paper_id', DB::raw('COUNT(*) AS paper_question')])
                        ->join('questions', 'questionsets.id', '=', 'questions.question_set_id')
                        ->where([
                            'questions.question_set_id' => $params['question_set_id'],
                            'questions.key_stage' => $params['key_stage'],
                            'questions.year_group' => $params['year_group'],
                            'questions.status' => PUBLISH,
                        ])
                ->groupBy('questions.paper_id')
                ->orderBy('questions.paper_id')
                        ->get()->toArray();
    }

}
