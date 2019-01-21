<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class Schooltype extends Model {

use DatePresenter;

/**
 * The database table used by the model.
 *
 * @var string
 */
protected $table = 'schooltypes';

/**
 * One to Many relation
 *
 * @return Illuminate\Database\Eloquent\Relations\hasMany
 */
public function user() {
    return $this->hasMany('App\Models\User');
    }

    /**
     * will return the school type listing
     * @return type 
     */
    protected function getSchoolType() {
        $schoolType = Schooltype::where('status', "=", ACTIVE)->orderBy('school_type')->lists('school_type', 'id')->all();
        return $schoolType;
        
    }

}