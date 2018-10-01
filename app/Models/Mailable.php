<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailable extends Model
{
	public $timestamps = False;
	protected $fillable = ['service_id','mail_id','interval_id'];
}
