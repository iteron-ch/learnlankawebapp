<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emailtemplate extends Model
{
    //
	protected $table = 'emailtemplates';
	protected $fillable = ['title', 'subject', 'message','status','created_at','updated_at','created_by','updated_by'];
}
