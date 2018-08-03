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

	public function getStatistic4AllAdmins()
	{
		return DB::table('sysadmin_activities as sact')->
		select(DB::raw('u.name, COUNT(DISTINCT ticket_id) AS tickets_count,COUNT(sact.lastreply) AS replies_count, SUM(time_uses) AS time_sum, SUM(serv.compl) AS compl'))->
		leftJoin('sysadmin_niks as sniks', 'sniks.id', '=', 'sysadmin_niks_id')->
		leftJoin('users as u', 'u.id', '=', 'user_id')->
		join('tickets as t', 't.id', '=', 'ticket_id')->
		rightJoin('services as serv', 't.service_id', '=', 'serv.id')->
		whereIn('sysadmin_niks_id', function ($q) {
			$q->select(DB::raw('sniks.id from users left join sysadmin_niks as sniks on sniks.user_id=users.id'));
		})->
		whereBetween('sact.lastreply', [Carbon::now()->startOfMonth(), Carbon::now()])->
		groupBy('u.name')->
		orderByDesc('tickets_count')->orderByDesc('replies_count')->
		get();
	}

	public function getAllStatistic4AllAdmins($subMonth = 0)
	{
		/*return DB::table('sysadmin_activities as sact')->
		select(DB::raw('u.name, COUNT(DISTINCT ticket_id) AS tickets_count,COUNT(sact.lastreply) AS replies_count, SUM(time_uses) AS time_sum, SUM(serv.compl) AS compl'))->
		leftJoin('sysadmin_niks as sniks', 'sniks.id', '=', 'sysadmin_niks_id')->
		leftJoin('users as u', 'u.id', '=', 'user_id')->
		join('tickets as t', 't.id', '=', 'ticket_id')->
		rightJoin('services as serv', 't.service_id', '=', 'serv.id')->
		whereIn('sysadmin_niks_id', function ($q) {
			$q->select(DB::raw('sniks.id from users left join sysadmin_niks as sniks on sniks.user_id=users.id'));
		})->
		whereBetween('sact.lastreply', [Carbon::now()->subMonth($subMonth)->startOfMonth(), Carbon::now()->subMonth($subMonth)->endOfMonth()])->
		groupBy('u.name')->
		orderByDesc('tickets_count')->orderByDesc('replies_count')->
		get();*/
		$Carbon = new Carbon();
		return DB::table('sysadmin_activities AS sact')->
		select(DB::raw('u.name, COUNT(DISTINCT ticket_id) AS tickets_count, COUNT(sact.lastreply) AS replies_count ,SUM(time_uses) as time_sum, SUM(serv.compl) as compl'))->
		leftJoin('sysadmin_niks AS sniks', 'sniks.id', '=', 'sysadmin_niks_id')->
		leftJoin('users AS u', 'u.id', '=', 'user_id')->
		join('tickets', 'tickets.id', '=', 'ticket_id')->
		rightJoin('services as serv', 'tickets.service_id', '=', 'serv.id')->
		whereIn('sysadmin_niks_id', function ($q) {
			$q->select(DB::raw('sniks.id FROM users LEFT JOIN sysadmin_niks AS sniks ON sniks.user_id=users.id'));
		})->
		whereBetween('sact.lastreply', [
			$Carbon->now()->subMonth($subMonth)->startOfMonth()->toDateString(),
			$Carbon->now()->subMonth($subMonth)->endOfMonth()->toDateString()
		])->
		groupBy('u.name')->orderByDesc('tickets_count')->orderByDesc('replies_count')->get();


	}

	public function getStatistic4Admin(int $user_id)
	{
		return DB::table('sysadmin_activities as sact')->
		select(DB::raw('u.name, COUNT(DISTINCT ticket_id) AS tickets_count,COUNT(sact.lastreply) AS replies_count, SUM(time_uses) AS time_sum, SUM(serv.compl) AS compl'))->
		leftJoin('sysadmin_niks as sniks', 'sniks.id', '=', 'sysadmin_niks_id')->
		leftJoin('users as u', 'u.id', '=', 'user_id')->
		join('tickets as t', 't.id', '=', 'ticket_id')->
		rightJoin('services as serv', 't.service_id', '=', 'serv.id')->
		whereIn('sysadmin_niks_id', function ($q) use ($user_id) {
			$q->select(DB::raw("sniks.id from users left join sysadmin_niks as sniks on sniks.user_id=users.id  where user_id=$user_id"));
		})->
		whereBetween('sact.lastreply', [Carbon::now()->startOfMonth(), Carbon::now()])->
		groupBy('u.name')->
		first();
	}
}
