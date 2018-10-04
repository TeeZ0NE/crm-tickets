<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mailable extends Model
{
	public $timestamps = False;
	protected $fillable = ['service_id','interval_id'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function getService()
	{
		return $this->belongsTo(Service::class,'service_id')->select(['id','name']);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function getInterval()
	{
		return $this->belongsTo(Interval::class,'interval_id');
	}

	//$mailable_m::with(['getInterval','getEmail','getService'])->get()->groupBy(['service_id','interval_id'])
//$mailable_m::with(['getInterval','getEmail','getService'])->whereHas('getService',function($f){$f->where('id',2);})->get()->groupBy(['service_id','interval_id'])
//$mailable_m::with(['getInterval','getEmail','getService'])->get()->groupBy(['service_id','interval_id'])

	public function getMailLists()
	{
		return $this->hasMany(MailList::class,'mailable_id');
}

	public function getEmails()
	{
		return $this->hasManyThrough(Email::class, MailList::class, 'mailable_id','id','id','email_id');
	}

	public function getAllLists()
	{
		return $this->with(['getService','getInterval','getEmails'])->orderBy('service_id')->get();
	}

	public function storeNewInterval(int $service_id, int $interval_id)
	{
		return $this->insertGetId(['service_id'=>$service_id,'interval_id'=>$interval_id]);
	}

	public function updateMailable(int $mailable_id, int $service_id, int $interval_id)
	{
		return $this->find($mailable_id)->update(['service_id'=>$service_id,'interval_id'=>$interval_id]);
	}

	public function destroyMailable(int $mailable_id)
	{
		return $this->find($mailable_id)->delete();
	}
}
