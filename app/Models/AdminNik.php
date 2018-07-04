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
		return $this->belongsTo(Service::class, 'service_id', 'id');
	}

	public function getRealAdmin()
	{
		return $this->belongsTo(Sysadmin::class,'admin_id','id');
	}
}
