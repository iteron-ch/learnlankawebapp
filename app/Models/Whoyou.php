<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class Whoyou extends Model {

use DatePresenter;

/**
 * The database table used by the model.
 *
 * @var string
 */
protected $table = 'whoyous';

/**
 * One to Many relation
 *
 * @return Illuminate\Database\Eloquent\Relations\hasMany
 */
public function user() {
    return $this->hasMany('App\Models\User');
    }

    /**
     * will return the who are you listing
     * @return type 
     */
    protected function getWhoAreYou() {
        return Whoyou::where('status', "=", ACTIVE)->orderBy('name')->lists('name', 'id')->all();
    }

}