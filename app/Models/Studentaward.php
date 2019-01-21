<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studentaward extends Model {

    //
    protected $table = 'studentawards';
    protected $fillable = ['title', 'content'];
    
    protected function getActiveStudentAwards() {
        return Studentaward::select(['studentawards.id', 'studentawards.title', 'studentawards.status', 'studentawards.updated_at', 'studentawards.image'])->where('status', '!=', DELETED)
                        ->where('studentawards.status', '=', ACTIVE)
            ->get()->toArray();
    }

}
