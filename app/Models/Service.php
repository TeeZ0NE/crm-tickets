<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Service extends Model
{
	public $timestamps = False;
	protected $fillable = ['name', 'compl', 'href_link', 'is_available'];

	/**
	 * getting all services Ids
	 * @return array
	 */
	public function getServicesIds(): array
	{
		return $this->orderBy('id')->pluck('id')->toArray();
	}

	public function getServiceId(string $service): int
	{
		return $this::firstOrCreate(['name' => $service])->id;
	}

	public function getCompl(int $service_id): float
	{
		return $this->find($service_id)->compl;
	}

	public function getServiceName(int $service_id)
	{
		return $this->find($service_id)->name;
	}

	/**
	 * Set available service
	 *
	 * @param int $service_id
	 * @param bool $is_available
	 */
	public function setServiceAvailable(int $service_id, bool $is_available)
	{
		$service_m = new Service();
		$service_m::find($service_id)->update(['is_available' => $is_available]);
	}

	public function getStatisticYesterday(int $service_id)
	{
		return DB::table('tickets as t')->
		select(DB::raw('distinct(t.id),t.subject,s.name,t.ticketid,sum(sact.time_uses) as sum_time'))->
		rightjoin('sysadmin_activities as sact', 'sact.ticket_id', '=', 't.id')->
		join('services as s', 't.service_id', '=', 's.id')->
		where('t.service_id', $service_id)->
		whereBetween('sact.lastreply', [Carbon::now()->yesterday()->startOfDay(), Carbon::now()->Yesterday()->endOfDay()])->
		groupby('t.id')->
		orderByDesc('sum_time')->
		get();
	}

	/**
	 * getting all services with own id's
	 *
	 * @return object
	 */
	public function getAllServices()
	{
		return $this->select('id', 'name')->get();
	}

	public function getCountTicketsAndSumTimeYesterday(int $service_id)
	{
		return DB::table('services as s')->
		select(DB::raw('s.id, s.name, count(t.id) as tickets_count,sum(sact.time_uses) as sum_time'))->
		leftJoin('tickets as t','t.service_id','=','s.id')->
		join('sysadmin_activities as sact','sact.ticket_id','=','t.id')->
		where('s.id',$service_id)->
		whereBetween('sact.lastreply',[Carbon::now()->yesterday()->startofday(),Carbon::now()->yesterday()->endofday()])->
		groupby('s.name')->
		first();
	}
	public function getStatisticToday(int $service_id)
	{
		return DB::table('tickets as t')->
		select(DB::raw('distinct(t.id),t.subject,s.name,t.ticketid,sum(sact.time_uses) as sum_time'))->
		rightjoin('sysadmin_activities as sact', 'sact.ticket_id', '=', 't.id')->
		join('services as s', 't.service_id', '=', 's.id')->
		where('t.service_id', $service_id)->
		whereBetween('sact.lastreply', [Carbon::now()->startOfDay(), Carbon::now()])->
		groupby('t.id')->
		orderByDesc('sum_time')->
		get();
	}
	public function getCountTicketsAndSumTimetoday(int $service_id)
	{
		return DB::table('services as s')->
		select(DB::raw('s.id, s.name, count(t.id) as tickets_count,sum(sact.time_uses) as sum_time'))->
		leftJoin('tickets as t','t.service_id','=','s.id')->
		join('sysadmin_activities as sact','sact.ticket_id','=','t.id')->
		where('s.id',$service_id)->
		whereBetween('sact.lastreply',[Carbon::now()->startofday(),Carbon::now()])->
		groupby('s.name')->
		first();
	}
	public function getStatisticStartOfMonth(int $service_id)
	{
		return DB::table('tickets as t')->
		select(DB::raw('distinct(t.id),t.subject,s.name,t.ticketid,sum(sact.time_uses) as sum_time'))->
		rightjoin('sysadmin_activities as sact', 'sact.ticket_id', '=', 't.id')->
		join('services as s', 't.service_id', '=', 's.id')->
		where('t.service_id', $service_id)->
		whereBetween('sact.lastreply', [Carbon::now()->startOfMonth(), Carbon::now()])->
		groupby('t.id')->
		orderByDesc('sum_time')->
		get();
	}
	public function getCountTicketsAndSumTimeStartOfMonth(int $service_id)
	{
		return DB::table('services as s')->
		select(DB::raw('s.id, s.name, count(distinct(t.id)) as tickets_count,sum(sact.time_uses) as sum_time'))->
		leftJoin('tickets as t','t.service_id','=','s.id')->
		rightjoin('sysadmin_activities as sact','sact.ticket_id','=','t.id')->
		where('s.id',$service_id)->
		whereBetween('sact.lastreply',[Carbon::now()->startOfMonth(),Carbon::now()])->
		groupby('s.name')->
		first();
	}
	public function getStatisticPrevMonth(int $service_id)
	{
		return DB::table('tickets as t')->
		select(DB::raw('distinct(t.id),t.subject,s.name,t.ticketid,sum(sact.time_uses) as sum_time'))->
		rightjoin('sysadmin_activities as sact', 'sact.ticket_id', '=', 't.id')->
		join('services as s', 't.service_id', '=', 's.id')->
		where('t.service_id', $service_id)->
		whereBetween('sact.lastreply', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->
		groupby('t.id')->
		orderByDesc('sum_time')->
		get();
	}
	public function getCountTicketsAndSumTimePrevMonth(int $service_id)
	{
		return DB::table('services as s')->
		select(DB::raw('s.id, s.name, count(distinct(t.id)) as tickets_count,sum(sact.time_uses) as sum_time'))->
		leftJoin('tickets as t','t.service_id','=','s.id')->
		rightjoin('sysadmin_activities as sact','sact.ticket_id','=','t.id')->
		where('s.id',$service_id)->
		whereBetween('sact.lastreply',[Carbon::now()->subMonth()->startOfMonth(),Carbon::now()->subMonth()->endOfMonth()])->
		groupby('s.name')->
		first();
	}
}
