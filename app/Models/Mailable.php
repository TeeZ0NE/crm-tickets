<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailable extends Model
{
	public $timestamps = False;
	protected $fillable = ['service_id','mail_id','interval_id'];

	public function getService()
	{
		return $this->belongsTo(Service::class,'service_id')->select(['id','name']);
	}

	public function getInterval()
	{
		return $this->belongsTo(Interval::class,'interval_id');
	}

	public function getEmail()
	{
		return $this->belongsTo(Email::class,'mail_id');
	}
}
