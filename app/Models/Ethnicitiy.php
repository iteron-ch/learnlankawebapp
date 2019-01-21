<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class Ethnicitiy extends Model {

    use DatePresenter;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ethnicities';

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
    
    protected function getEthnicitiy() {
        return Ethnicitiy::orderBy('ethnicity_name')->lists('ethnicity_name', 'id')->all();
    }


}
