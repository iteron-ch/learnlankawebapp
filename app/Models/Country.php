<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Presenters\DatePresenter;

class Country extends Model {

    use DatePresenter;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function user() {
        return $this->hasMany('App\Models\User');
    }

    /**
     * will return the countries listing
     * @return type 
     */
    protected function getCountry() {
        return [UKCOUNTRYCODE => 'United Kingdom']+ Country::orderBy('printable_name')->lists('printable_name', 'country_code')->all();
    }

}
