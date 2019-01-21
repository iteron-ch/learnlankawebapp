<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudents extends Model {

    protected $table = "classstudents";

    
    protected function getStudents() {
        return ClassStudents::where('status', '!=', DELETED)
                        //->where('school_id', '=', $school_id)
                        ->orderBy('id')->lists('id')->all();
    }

}
