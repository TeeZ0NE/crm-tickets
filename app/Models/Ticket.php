<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
	Model, ModelNotFoundException
};
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Ticket extends Model
{
	protected $fillable = ['ticketid', 'subject', 'service_id', 'status_id', 'priority_id', 'compl', 'lastreply', 'last_replier_nik_id', 'is_closed', 'has_deadline', 'last_is_admin', 'user_assign_id',];


	/**
	 * get status
	 *
	 * get' status from Statuses
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function getStatus()
	{
		return $this->hasOne(Status::class, 'id', 'status_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function getService()
	{
		return $this->belongsTo(Service::class, 'service_id', 'id');
	}

	/**
	 * getting count of closed tickets
	 * @param  int $service_id
	 *
	 * @return int
	 */
	public function getCountClosedTickets(int $service_id): int
	{
		return $this::where(['is_closed' => 1, 'service_id' => $service_id])->get()->count();
	}

	/**
	 *
	 * getting count of open tickets on service
	 * @param  int $service_id
	 * @return int
	 */
	public function getCountOpenTickets(int $service_id): int
	{
		return $this::where(['is_closed' => 0, 'service_id' => $service_id])->get()->count();
	}

	/**
	 * getting summary count of tickets
	 *
	 * @param $service_id
	 * @return mixed
	 */
	public function getSummaryCountTickets(int $service_id): int
	{
		return $this::where('service_id', $service_id)->get()->count();
	}

	/**
	 * get inner id from DB
	 *
	 * @param int $tid
	 * @param int $service_id
	 * @return int
	 */
	public function getTicketIdFromDb(int $tid, int $service_id): int
	{
		$id = 0;
		try {
			$id = $this::where([['ticketid', '=', $tid], ['service_id', '=', $service_id]])->firstOrFail()->id;
		} catch (ModelNotFoundException $mnf) {
			Log::error("Error getting ID from DB where ticketid is $tid and service_id is $service_id");
		}
		return $id;
	}

	public function getTicketId(int $ticketid, int $service_id, array $values = [])
	{
		$ticket_m = $this->firstOrCreate(['service_id' => $service_id, 'ticketid' => $ticketid], $values);
		return $ticket_m->id;
	}

	/**
	 * get array from DB 2 compare with outer array ticketid tid
	 *
	 * @param int $service_id
	 * @return array of ticketid
	 */
	public function getTidArray(int $service_id): array
	{
		return $this->where('service_id', $service_id)->pluck('ticketid', 'id')->toArray();
	}

	/**
	 * moving\removing tickets
	 *
	 * check in removing statuses if occur's then remove else move to closed tickets table
	 * @param array $absentIds
	 */
	public function moveRemoveTicketsDecision(array $absentIds)
	{
		# statuses when we remove from tickets
		$removeStatuses = ['error'];
		foreach ($absentIds as $absentId) {
			if (in_array($absentId['status'], $removeStatuses))
				$this->removeTicket(
					array(
						$absentId['service_id'] => $absentId['absentId']
					)
				);
			else $this->moveTicket(
				array(
					$absentId['service_id'] => $absentId['absentId']
				)
			);
		}
	}

	/**
	 * get admin nicks
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function getAdminNiks()
	{
		return $this->hasManyThrough(AdminNik::class, SysadminActivity::class, 'ticket_id', 'id', 'id', 'sysadmin_niks_id');
	}

	/**
	 * Remove ticket from DB (ticket table) and Log it
	 *
	 * $absentId['service_id'] => $absentId['absentId']
	 * @param array $absentTicket
	 */
	private function removeTicket(array $absentTicket): void
	{
		$service_id = key($absentTicket);
		$id = $this->getTicketIdFromDb($absentTicket[$service_id], $service_id);
		if ($id) {
			$res = $this::find($id)->delete();
			Log::warning("Ticket id $absentTicket[$service_id] (service id is $service_id) removed from DB because it has had status 4 remove. Check statuses in Ticket Model", ["result" => $res]);
		} else Log::error("Ticket id $absentTicket[$service_id] (service id is $service_id) checked as remove from DB because it has had status 4 remove but it doesn't remove, real id $id is not found");
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function getPriority(): object
	{
		return $this->hasOne(Priority::class, 'id', 'priority_id');
	}

	/**
	 * sign (check is_closed) when ticket is checked as Closed
	 *
	 * $absentId['service_id'] => $absentId['absentId']
	 * @param array $absentTicket
	 */
	private function moveTicket(array $absentTicket): void
	{
		$service_id = key($absentTicket);
		$msg = 'Ticket id %d (service id is %d) checked as closed", result is %s';
		$id = $this->getTicketIdFromDb($absentTicket[$service_id], $service_id);
		if ($id) {
			$res = $this::find($id)->update(['is_closed' => 1]);
			Log::info(sprintf($msg, $absentTicket[$service_id], $service_id, $res));
		} else Log::error(sprintf($msg, $absentTicket[$service_id], $service_id, $id));
	}

	/**
	 * get real admins name cross sysadminNiks if applicable
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function getAdmin()
	{
		return $this->hasManyThrough(User::class, AdminNik::class, 'id', 'id', 'last_replier_nik_id', 'user_id');
	}

	public function getUserAssignedTicket()
	{
		return $this->hasOne(User::class, 'id', 'user_assign_id');
	}


	/**
	 * get open tickets
	 *
	 * if need use getAdminActivity method to take all admin's activity in open ticket
	 *
	 * @return Ticket[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getOpenTickets()
	{
		return $this::with(['getStatus', 'getPriority', 'getService', 'getAdmin', 'getUserAssignedTicket'])->
		where('is_closed', 0)->
		orderByDesc('has_deadline')->
		orderBy('lastreply')->
		get();
	}

	/**
	 * get new tickets
	 *
	 * they don't have admin(-s) yet
	 * @return Ticket[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getNewTickets()
	{
		return $this::with(['getStatus', 'getPriority', 'getService', 'getAdmin'])->
		where([['is_closed', '=', 0], ['last_replier_nik_id', '=', 0]])->
//		orderBy('last_is_admin')->
		orderBy('lastreply')->
		get();
	}

	public function getNewTickets4Admin()
	{
		return $this::with(['getStatus', 'getPriority', 'getService', 'getAdmin'])->
		where([['is_closed', '=', 0],
			['user_assign_id', '=', Null]])->
		orWhere([['last_replier_nik_id', '=', 0], ['is_closed', '=', 0]])->
		whereHas('getAdmin', function ($q) {
			$q->where('active', 0);
		})->
		orderBy('lastreply')->
		get();
	}

	/**
	 * get all tickets on service from yesterday
	 *
	 * @param int $service_id
	 * @return mixed
	 */
	public function getAllTicketsFromYesterday(int $service_id)
	{
		return $this::where([['created_at', '>=', Carbon::now()->yesterday()], ['service_id', $service_id]])->get();
	}

	/**
	 * get all tickets on service from month start
	 *
	 * @param int $service_id
	 * @return mixed
	 */
	public function getAllTicketsFromMonth(int $service_id)
	{
		return $this::where([['created_at', '>=', Carbon::now()->startOfMonth()], ['service_id', $service_id]])->get();
	}

	/**
	 * get admin activity in ticket
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function getAdminsActivities()
	{
		return $this->hasMany(SysadminActivity::class, 'ticket_id');
	}

	/**
	 * get all open ticket 4 admin id
	 *
	 * 4 current admin id get from DB all his open tickets
	 * @param $user_id
	 * @return Ticket[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getOpenTickets4CurrAdmin(int $user_id)
	{
		return $this::with(['getStatus', 'getPriority', 'getService', 'getAdmin', 'getUserAssignedTicket'])->
		where([['is_closed', '=', 0],['user_assign_id','=',$user_id]])->
		/*WhereHas('getAdmin', function ($f) use ($user_id) {
			$f->where('user_id', $user_id);
		})->*/
		orderBy('lastreply')->
		get();
	}

	/**
	 * Set Null value inUser_assign_id
	 * @param int $user_id
	 * @return int count of tickets nullabled
	 */
	public function setNullUserAssignId(int $user_id)
	{
		return $this::where('user_assign_id', $user_id)->update(['user_assign_id' => Null]);
	}

	/**
	 * check ticket as closed
	 *
	 * ticket is absent in incoming array then switch to closed
	 * @param int $ticketid
	 * @param int $service_id
	 * @return mixed
	 */
	public function closeTicket(int $ticketid, int $service_id)
	{
		return $this::where(['ticketid'=>$ticketid,'service_id'=>$service_id])->update(['is_closed'=>1]);
	}
}
