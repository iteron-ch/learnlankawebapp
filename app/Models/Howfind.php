<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class Howfind extends Model {

    use DatePresenter;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'howfinds';

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
    protected function getHowFind() {
        return Howfind::where('status', "=", ACTIVE)->orderBy('name')->lists('name', 'id')->all();
    }
    /**
     * will return the who are you listing
     * @return type 
     */
    protected function getHowFindEnquiry() {
        return Howfind::where('status', "=", ACTIVE)->orderBy('name')->lists('name', 'name')->all();
    }
}
