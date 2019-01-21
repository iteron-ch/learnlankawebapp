<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionanswer extends Model {

    protected $table = "questionanswer";
    protected $fillable = array('id', 'attempt_id','task_type', 'question_answer','question_id', 'attempt_status');

}
