<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sysadmin extends Model
{
    public $timestamps = False;
    protected $fillable = array('name');

	/**
	 * get services real admin
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function getServices()
	{
		return $this->hasManyThrough(Service::class,AdminNik::class,'admin_id','id','id','service_id');
    }

	/**
	 * getting all niks real admin
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getNiks()
	{
		return $this->hasMany(AdminNik::class,'admin_id');
    }
}
