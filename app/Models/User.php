<?php

namespace App\Models;
use Illuminate\Contracts\Auth\{Authenticatable, CanResetPassword as CanResetPasswordContract};
use Illuminate\Auth\{Authenticatable as AuthenticableTrait, Passwords\CanResetPassword};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Model implements Authenticatable,CanResetPasswordContract
{
	use AuthenticableTrait, CanResetPassword, Notifiable ;
	protected $fillable = ['name','email','password','active',];
	protected  $hidden = ['password', 'remember_token',];

	/**
	 * get services real admins
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function getServices()
	{
		return $this->hasManyThrough(Service::class, AdminNik::class, 'user_id', 'id', 'id', 'service_id');
	}

	/**
	 * getting all niks real admins
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getNiks()
	{
		return $this->hasMany(AdminNik::class, 'user_id');
	}

	/**
	 * getting activities on closed tickets
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function getSummaryCountTicketsAndReplies()
	{
		return
			DB::table('users as s')->
			select(DB::raw('s.name, COUNT(sact.ticket_id) as ticket_count, SUM(sact.replies) as reply_count'))->
			leftJoin('sysadmin_niks as snik', 'snik.user_id', '=', 's.id')->
			join('sysadmin_activities as sact', 'sact.sysadmin_niks_id', '=', 'snik.id')->
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
			DB::table('users as s')->
			select(DB::raw('s.name, COUNT(sact.ticket_id) as ticket_count, SUM(sact.replies) as reply_count'))->
			leftJoin('sysadmin_niks as snik', 'snik.user_id', '=', 's.id')->
			join('sysadmin_activities as sact', 'sact.sysadmin_niks_id', '=', 'snik.sysadmin_niks_id')->
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
		return $this::orderByDesc('active')->orderBy('name')->get();
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
