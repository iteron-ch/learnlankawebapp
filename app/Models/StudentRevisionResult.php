<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRevisionResult extends Model {

    protected $table = "student_revision_result_data";
    protected $fillable = array('id', 'student_id','strand_id','substrand_id','class_id');


}
