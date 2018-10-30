<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
	Collection, Model, ModelNotFoundException
};
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
	 * if exist return onw ID else 0 as new ticket
	 *
	 * @param int $tid
	 * @param int $service_id
	 * @return int
	 */
	public function getTicketIdFromDb(int $tid, int $service_id): int
	{
		try {
			$id = $this->where([['ticketid', '=', $tid], ['service_id', '=', $service_id]])->firstOrFail()->id;
		} catch (ModelNotFoundException $mnf) {
			$id = 0;
			//Log::error("Error getating ID from DB where ticketid is $tid and service_id is $service_id");
		}
		return $id;
	}

	public function getTicketId(int $ticketid, int $service_id, array $values = [])
	{
		$ticket_m = Ticket::UpdateOrCreate(['service_id' => $service_id, 'ticketid' => $ticketid], $values);
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
		return $this->where('service_id', $service_id)->
		whereBetween('lastreply', [Carbon::now()->startOfMonth(), Carbon::now()])->
		pluck('ticketid', 'id')->toArray();
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
	 * get user id which assign ticket
	 * @param int $ticket_id
	 * @return int
	 */
	public function getUserAssignId(int $ticket_id)
	{
		return $this::find($ticket_id)->user_assign_id;
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
//		return $this::where([['created_at', '>=', Carbon::now()->yesterday()], ['service_id', $service_id]])->get();
		return $this::where('service_id', $service_id)->
		whereBetween('created_at', [Carbon::now()->yesterday()->startOfDay(), Carbon::now()->yesterday()->endOfDay()])->
		get();
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
		where([['is_closed', '=', 0], ['user_assign_id', '=', $user_id]])->
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
		return $this::where(['ticketid' => $ticketid, 'service_id' => $service_id])->update(['is_closed' => 1]);
	}

	/**
	 * Save new ticket
	 *
	 * @param int $ticketid
	 * @param int $service_id
	 * @param string $subject
	 * @param int $status_id
	 * @param int $priority_id
	 * @param $lastreply
	 * @return int ticket_id
	 */
	public function storeNewTicket(int $ticketid, int $service_id, string $subject, int $status_id, int $priority_id, $lastreply): int
	{
		$ticket_m = new Ticket();
		$ticket_m->ticketid = $ticketid;
		$ticket_m->service_id = $service_id;
		$ticket_m->subject = $subject;
		$ticket_m->status_id = $status_id;
		$ticket_m->priority_id = $priority_id;
		$ticket_m->lastreply = $lastreply;
		$ticket_m->save();
		return $ticket_m->id;
	}

	/**
	 * @param int $ticket_id
	 * @param int $status_id
	 * @param int $priority_id
	 * @param string $lastreply
	 * @param int $last_is_admin
	 * @param string $subject
	 */
	public function updateTicket(int $ticket_id, int $status_id, int $priority_id, string $lastreply, int $last_is_admin, string  $subject)
	{
		$this::find($ticket_id)->update([
			'status_id' => $status_id,
			'priority_id' => $priority_id,
			'lastreply' => $lastreply,
			'last_is_admin' => $last_is_admin,
			'is_closed' => 0,
			'subject'=>$subject
		]);
	}

	/**
	 * get is last reply admin
	 *
	 * @param int $ticket_id
	 * @return int
	 */
	public function getIsAdminFromDb(int $ticket_id): int
	{
		return $this::find($ticket_id)->last_is_admin;
	}

	/**
	 * Get last replier 4 curr ticket
	 * @param int $ticket_id
	 * @return int lastreplier_nik_id
	 */
	public function getLastreplierId(int $ticket_id): int
	{
		$lastreplier_nik_id = Ticket::find($ticket_id)->last_replier_nik_id;
		return $lastreplier_nik_id;
	}

	public function setUserAssignId(int $ticket_id, int $user_id)
	{
		Ticket::find($ticket_id)->update(['user_assign_id' => $user_id]);
	}

	public function getLastreply(int $ticket_id)
	{
		return $this::find($ticket_id)->lastreply;
	}

	/**
	 * @param int $pag_count paginate page count
	 * @return Collection
	 */
	public function getAllTickets(int $pag_count = 10)
	{
		return $this::with('getService')->get()->sortBy('service_id')->paginate($pag_count);
	}

	public function getServiceTickets(int $service_id, int $pag_count = 10)
	{
		return $this::with('getService')->where('service_id', $service_id)->get()->paginate($pag_count);
	}

	public function getServiceTicket(int $service_id, int $ticketid, int $pag_count = 10)
	{
		return $this::with('getService')->where(['service_id' => $service_id, 'ticketid' => $ticketid])->get()->paginate($pag_count);
	}

	public function ticketDestroy(int $ticket_id): bool
	{
		$destroyed = false;
		try {
			$destroyed = $this::findOrFail($ticket_id)->delete();
		} catch (ModelNotFoundException $nf) {
			Log::error(sprintf('Ticket with id %d not found', $ticket_id));
		}
		return $destroyed;
	}

	public function getTicketAllActivities(int $id)
	{
		return DB::table('tickets as t')->
		select(DB::raw('t.ticketid, s.name, s.href_link,t.subject, sact.lastreply, sact.id as sact_id, sact.time_uses, snik.admin_nik, u.name as user_name'))->
		RIGHTJOIN('sysadmin_activities as sact', 'sact.ticket_id', '=', 't.id')->
		JOIN('sysadmin_niks as snik', 'snik.id', '=', 'sact.sysadmin_niks_id')->
		LEFTJOIN('users as u', 'snik.user_id', '=', 'u.id')->
		LEFTJOIN('services as s', 's.id', '=', 't.service_id')->
		where('t.id', $id)->
		orderBy('sact.lastreply')->
		get();
	}
}
