<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taskstudents extends Model {

    protected $table = "taskstudents";
    
    protected $fillable = ['assign_id', 'task_id', 'student_id', 'student_source_id'];

    protected function saveStudent($student_source_id, $params) {
        $modelTaskStudents = $this->firstOrNew([
            'assign_id' => $params['assign_id'],
            'task_id' => $params['task_id'],
            'student_id' => $params['student_id']
        ]);
        if (!$modelTaskStudents->getOriginal()) { 
            $modelTaskStudents->student_source_id = $student_source_id;
            $modelTaskStudents->save();
            return TRUE;
        } else { 
            return FALSE;
        }
    }

}
