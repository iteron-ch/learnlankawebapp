<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paymentmethod extends Model
{
    //
	protected $table = 'paymentmethods';
	protected $fillable = ['paypal_email', 'transaction_key', 'transaction_user_id','transaction_password','created_at','updated_at','created_by','updated_by'];
	
}
