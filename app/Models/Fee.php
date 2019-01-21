<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $table = 'fees';
    protected $fillable = ['school_sign_up_fee', 'parent_sign_up_fee', 'per_student_fee','per_5_student_fee','created_at','updated_at','created_by','updated_by'];
}
