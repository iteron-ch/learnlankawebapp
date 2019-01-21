<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studentstrandmeta extends Model {

    protected $table = "student_strand_meta";
    protected $fillable = array('id', 'student_id','strand_id','task_type');


}
