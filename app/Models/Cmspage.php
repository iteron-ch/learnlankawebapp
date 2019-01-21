<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cmspage extends Model
{
    //
	protected $table = 'cmspages';
	protected $fillable = ['title', 'sub_title', 'description','status'];
}
