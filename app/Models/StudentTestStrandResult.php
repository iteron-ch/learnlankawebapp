<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTestStrandResult extends Model {

    protected $table = "student_test_gap_result_data";
    protected $fillable = array('id', 'student_id','strand_id','substrand_id','set_id','paper_id','class_id');


}
