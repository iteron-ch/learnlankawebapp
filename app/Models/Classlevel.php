<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class Classlevel extends Model {

use DatePresenter;

/**
 * The database table used by the model.
 *
 * @var string
 */
protected $table = 'classlevels';

/**
 * One to Many relation
 *
 * @return Illuminate\Database\Eloquent\Relations\hasMany
 */
public function user() {
    return $this->hasMany('App\Models\User');
    }

    /**
     * will return the level listing
     * @return type 
     */
    protected function getClassLevel() {
        return Classlevel::orderBy('level')->lists('level', 'id')->all();
    }

}