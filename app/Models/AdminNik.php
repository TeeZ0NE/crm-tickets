<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNik extends Model
{
    public $timestamps = False;
    protected $fillable = array('service_id','admin_nik','admin_id');
	protected $table = 'sysadmin_niks';

	public function getService()
	{
		return $this->hasOne(Service::class, 'id', 'service_id');
	}

	public function getRealAdmin()
	{
		return $this->hasOne(Sysadmin::class,'id','admin_id');
	}
}
