<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schoolclass extends Model {

    //
    protected $table = 'schoolclasses';

    /**
     * will return the level listing
     * @return type 
     */
    protected function getSchoolClass() {
        return Schoolclass::orderBy('class_name')->lists('class_name', 'id')->all();
    }
    
    protected function getClassBySchool($schoolID) {
        return Schoolclass::where('created_by','=',$schoolID)->orderBy('class_name')->lists('class_name', 'id');
    }

}
