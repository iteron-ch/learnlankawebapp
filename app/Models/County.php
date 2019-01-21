<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class County extends Model {

use DatePresenter;

/**
 * The database table used by the model.
 *
 * @var string
 */
protected $table = 'counties';

/**
 * One to Many relation
 *
 * @return Illuminate\Database\Eloquent\Relations\hasMany
 */
public function user() {
    return $this->hasMany('App\Models\User');
    }

    /**
     * will return the county listing
     * @return type 
     */
    protected function getCounty() {
        return County::orderBy('name')->lists('name', 'id')->all() + ['-1' => trans('admin/admin.other_county')];
    }

}