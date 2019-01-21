<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Question extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'questions';
    protected $fillable = ['description', 'descvisible', 'status', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function posts() {
        return $this->hasMany('App\Models\QuestionDetail');
    }

    protected function getRevisionQuestionCount($params) {
        return $this->select(DB::raw('COUNT(id) AS cnt'))
                        ->where([
                            'set_group' => $params['task_type'],
                            'subject' => $params['subject'],
                            'key_stage' => $params['key_stage'],
                            'year_group' => $params['year_group'],
                            'strands_id' => $params['strand'],
                            'substrands_id' => $params['substrand'],
                            'status' => PUBLISH
                        ])
                        ->get()->first()->toArray();
    }

}
