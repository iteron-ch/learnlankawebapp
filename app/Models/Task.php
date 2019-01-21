<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Task extends Model {

    protected $table = 'task';
    protected $fillable = ['task_type', 'key_stage', 'year_group', 'subject', 'question_set', 'strand', 'substrand'];

    protected function addTask($params) {
        $arr = [
            'task_type' => $params['task_type'],
            'subject' => $params['subject'],
            'key_stage' => $params['key_stage'],
            'year_group' => $params['year_group'],
        ];
        if ($params['task_type'] == REVISION) {
            $arr['strand'] = $params['strand'];
            $arr['substrand'] = $params['substrand'];
            $task = $this->firstOrNew($arr);
            $task->save();
        } else {
            $arr['question_set'] = $params['question_set'];
            $task = $this->firstOrNew($arr);
            $task->save();
        }
        return $task->id;
    }
    
    

}
