<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model {

    protected $table = "users";

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country() {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function county() {
        return $this->belongsTo('App\Models\County');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schooltype() {
        return $this->belongsTo('App\Models\Schooltype');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function whoyou() {
        return $this->belongsTo('App\Models\Whoyou');
    }


}
