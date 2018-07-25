<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNik extends Model
{
    public $timestamps = False;
    protected $fillable = ['service_id','admin_nik','user_id'];
	protected $table = 'sysadmin_niks';

	/**
	 * getting service
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function getService()
	{
		return $this->belongsTo(Service::class, 'service_id', 'id');
	}

	/**
	 * getting real admin with belongsTo
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function getAdmin()
	{
		return $this->belongsTo(User::class,'user_id','id');
	}

	/**
	 * cross table get activity
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getAdminActivity()
	{
		return $this->hasMany(SysadminActivity::class,'sysadmin_niks_id', 'id');
	}

	/**
	 * get sorted admin nicks 4 binding
	 * @return AdminNik[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getNicks()
	{
		return $this::with('getService')->orderBy('admin_nik')->get();
	}
}
