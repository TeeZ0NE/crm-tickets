<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SysadminActivity extends Model
{
	public $timestamps = False;
	protected $fillable = ['sysadmin_niks_id', 'ticket_id', 'lastreply', 'time_uses'];

	public function getTickets()
	{
		return $this->hasMany(Ticket::class, 'id', 'ticket_id');
	}

	public function getSysadmins()
	{
		return $this->hasManyThrough(User::class, AdminNik::class, 'id', 'id', 'sysadmin_niks_id', 'user_id');
	}

	public function getSysAdminsPivot()
	{
		return $this->belongsToMany(User::class, 'sysadmin_niks', 'user_id', 'id');
	}

	public function getAllStatistic4AllAdmins(int $service_id, $subMonth)
	{
		$Carbon = new Carbon();
		return DB::table('sysadmin_activities as sact')->
		select(DB::raw('services.name as service, COUNT(DISTINCT sact.ticket_id) AS tickets_count,COUNT(sact.lastreply) AS replies_count, u.name as user_name, SUM(sact.time_uses) AS sum_time, COUNT(DISTINCT ticket_id)*compl AS rate'))->
		RIGHTJOIN('tickets AS t', 'sact.ticket_id', '=', 't.id')->
		RIGHTJOIN('services', 't.service_id', '=', 'services.id')->
		LEFTJOIN('sysadmin_niks AS sniks', 'sniks.id', '=', 'sact.sysadmin_niks_id')->
		LEFTJOIN('users AS u', 'u.id', '=', 'sniks.user_id')->
		whereIn('sact.sysadmin_niks_id', function ($q) {
			$q->select(DB::raw('sniks.id from users LEFT JOIN sysadmin_niks as sniks on sniks.user_id=users.id'));
		})->
//		-- where users.id=2
		whereBetween('sact.lastreply', [
			$Carbon->now()->subMonth($subMonth)->startOfMonth(),
			$Carbon->now()->subMonth($subMonth)->endOfMonth()
		])->
		where('services.id', $service_id)->
		GROUPBY('service', 'user_name')->
		ORDERBYDESC('tickets_count')->orderByDesc('rate')->get();

	}

	public function getStatistic4Admin(int $service_id, int $user_id, int $subMonth)
	{
		return DB::table('sysadmin_activities as sact')->
		select(DB::raw('services.name as service, COUNT(DISTINCT sact.ticket_id) AS tickets_count,COUNT(sact.lastreply) AS replies_count, u.name as user_name, SUM(sact.time_uses) AS sum_time, COUNT(DISTINCT ticket_id)*compl AS rate'))->
		RIGHTJOIN('tickets AS t', 'sact.ticket_id', '=', 't.id')->
		RIGHTJOIN('services', 't.service_id', '=', 'services.id')->
		LEFTJOIN('sysadmin_niks AS sniks', 'sniks.id', '=', 'sact.sysadmin_niks_id')->
		LEFTJOIN('users AS u', 'u.id', '=', 'sniks.user_id')->
		whereIn('sact.sysadmin_niks_id', function ($q) use ($user_id){
			$q->select(DB::raw('sniks.id from users LEFT JOIN sysadmin_niks as sniks on sniks.user_id=users.id'));
			$q->where('user_id',$user_id);
		})->
//		whereBetween('sact.lastreply', [Carbon::now()->startOfMonth(), Carbon::now()])->
		whereBetween('sact.lastreply', [
			Carbon::now()->subMonth($subMonth)->startOfMonth(),
			Carbon::now()->subMonth($subMonth)->endOfMonth()
		])->
		where('services.id', $service_id)->
		GROUPBY('service', 'user_name')->
		get();
	}

	public function destroyActivity(int $id)
	{
		return $this::find($id)->delete();
	}
}
