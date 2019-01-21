<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

    protected $table = "events";

    /**
     * This is used to List all Events
     * @param Request $request
     * @return type
     * @author     Icreon Tech  - dev5.
     */
    public function showEvent() {
        return $this
                ->select(['title','id','start_date','start_time','end_time'])
                //->where('status','Published')
                ->orderBy('title')
                ->get()->toArray();
    }
    

}
