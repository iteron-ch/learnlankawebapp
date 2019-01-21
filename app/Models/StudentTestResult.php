<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTestResult extends Model {

    protected $table = "student_test_result_data";
    protected $fillable = array('id', 'student_id','question_set_id','assignment_num','class_id');


}
