<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sysadmin extends Model
{
	public $timestamps = False;
	protected $fillable = ['name'];

	/**
	 * get services real admins
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function getServices()
	{
		return $this->hasManyThrough(Service::class, AdminNik::class, 'admin_id', 'id', 'id', 'service_id');
	}

	/**
	 * getting all niks real admins
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getNiks()
	{
		return $this->hasMany(AdminNik::class, 'admin_id');
	}

	/**
	 * getting activities on closed tickets
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getCountTicketsAndReplies()
	{
		return
			DB::table('sysadmins as s')->
			select(DB::raw('s.name, COUNT(sact.ticket_id) as ticket_count, SUM(sact.replies) as reply_count'))->
			leftJoin('sysadmin_niks as snik', 'snik.admin_id', '=', 's.id')->
			join('sysadmin_activities as sact', 'sact.admin_nik_id', '=', 'snik.admin_nik_id')->
			join('tickets as t', 'sact.ticket_id', '=', 't.id')->
//			where('t.is_closed', 1)->
			groupBy('s.id')->
			orderByDesc('ticket_count')->
			orderByDesc('reply_count')->
			get();
	}
	/**
	 * getting activities on open tickets
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getCountOfOpenTicketsAndReplies()
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
	/**
	 * get sorted admin names
	 * @return mixed
	 */
	public function getAdmins()
	{
		return $this::orderBy('name')->get();
	}

	/**
	 * get sorted admins names and nicknames and services where they are
	 * @return Sysadmin[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getAdminsWservicesAndNicks()
	{
		return $this::with(['getServices', 'getNiks'])->orderBy('name')->get();
	}
}
