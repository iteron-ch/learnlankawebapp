<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model {

    protected $table = "cities";

    protected function getCityList($keyword) {
        $schoolType = City::
                where('name', 'LIKE', $keyword)
                ->orderBy('name')
                ->lists('name', 'name')
                ->take(10)
                ;
        return $schoolType;
    }

}
