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
		return DB::table('sysadmin_activities')->
		select(DB::raw('name, COUNT(DISTINCT ticket_id) AS tickets_count,COUNT(lastreply) AS replies_count, SUM(time_uses) AS time_sum'))->
		leftJoin('sysadmin_niks as sniks', 'sniks.id', '=', 'sysadmin_niks_id')->
		leftJoin('users', 'users.id', '=', 'user_id')->
		whereIn('sysadmin_niks_id', function ($q) {
			$q->select(DB::raw('sniks.id from users left join sysadmin_niks as sniks on sniks.user_id=users.id'));
		})->
		whereBetween('lastreply', [Carbon::now()->startOfMonth(), Carbon::now()])->
		groupBy('name')->
		orderByDesc('tickets_count')->orderByDesc('replies_count')->
		get();
	}
	public function getStatistic4Admin(int $user_id)
	{
		return DB::table('sysadmin_activities')->
		select(DB::raw('name, COUNT(DISTINCT ticket_id) AS tickets_count,COUNT(lastreply) AS replies_count, SUM(time_uses) AS time_sum'))->
		leftJoin('sysadmin_niks as sniks', 'sniks.id', '=', 'sysadmin_niks_id')->
		leftJoin('users', 'users.id', '=', 'user_id')->
		whereIn('sysadmin_niks_id', function ($q) use($user_id) {
			$q->select(DB::raw("sniks.id from users left join sysadmin_niks as sniks on sniks.user_id=users.id  where user_id=$user_id"));
		})->
		whereBetween('lastreply', [Carbon::now()->startOfMonth(), Carbon::now()])->
		groupBy('name')->
		first();
	}
}
