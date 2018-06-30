<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
	protected $fillable = array('userid','name');
	public function getAdmin()
	{
		//return $this->hasOne(SysadminActivity::class);
		return $this->hasManyThrough(Sysadmin::class,SysadminActivity::class,'c_id','id','id','admin_id')->first();
	}
	/**
	 * getting admin's nik 4 this client
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function getAdminNik()
	{
		return $this->hasOne(AdminNik::class);
    }

	/**
	 * getting open tickets of client
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getTickets()
	{
		return $this->hasMany(Ticket::class);
    }

	/**getting closed tickets of client
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getClosetTickets()
	{
		return $this->hasMany(ClosedTicket::class);
    }
}
