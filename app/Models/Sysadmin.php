<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
		return $this->hasManyThrough(Service::class, AdminNik::class, 'admin_id', 'id', 'id', 'service_id');
	}

	/**
	 * getting all niks real admin
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getNiks()
	{
		return $this->hasMany(AdminNik::class, 'admin_id');
	}

	public function getCountOfClosedTicketsAndReplies()
	{
		return
			DB::table('sysadmins as s')->
			select(DB::raw('s.name, COUNT(sact.ticket_id) as ticket_count, SUM(sact.replies) as reply_count'))->
			leftJoin('sysadmin_niks as snik', 'snik.admin_id', '=', 's.id')->
			join('sysadmin_activities as sact', 'sact.admin_nik_id', '=', 'snik.admin_nik_id')->
			join('tickets as t', 'sact.ticket_id', '=', 't.id')->
			where('t.is_closed', 0)->
			groupBy('s.id')->
			orderByDesc('ticket_count')->
			orderByDesc('reply_count')->
			get();
	}

}
