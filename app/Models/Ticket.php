<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
	Model, ModelNotFoundException
};
use Illuminate\Support\Facades\Log;

class Ticket extends Model
{
	protected $fillable = array('ticketid', 'subject', 'service_id', 'status_id', 'priority_id', 'reply_count','compl', 'lastreply', 'last_replier_nik_id', 'is_closed', 'deadline_id','last_is_admin','is_new');


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
	 * getting DataTime of deadline if exist
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function getDeadline()
	{
		return $this->hasOne(Deadline::class, 'id', 'deadline_id');
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
	 * @return int
	 */
	public function getCountClosedTickets($service_id)
	{
		return $this::where(['is_closed'=>1,'service_id'=>$service_id])->get()->count();
	}

	/**
	 * getting count of open tickets on service
	 * @return int
	 */
	public function getCountOpenTickets($service_id)
	{
		return $this::where(['is_closed'=>0,'service_id'=>$service_id])->get()->count();
	}

	/**
	 * getting summary count of tickets
	 *
	 * @param $service_id
	 * @return mixed
	 */
	public function getSummaryCountTickets($service_id)
	{
		return $this::where('service_id',$service_id)->get()->count();
	}
	/**
	 * get inner id from DB
	 *
	 * @param int $tid
	 * @param int $service_id
	 * @return int
	 */
	public function getTicketIdFromDb(int $tid, int $service_id)
	{
		$id = 0;
		try {
			$id = $this::where([['ticketid', '=', $tid], ['service_id', '=', $service_id]])->firstOrFail()->id;
		} catch (ModelNotFoundException $mnf) {
			Log::error("Error getting ID from DB where ticketid is $tid and service_id is $service_id");
		}
		return $id;
	}

	/**
	 * get array from DB 2 compare with outer array ticketid tid
	 *
	 * @param int $service_id
	 * @return array of ticketid
	 */
	public function getTidArray($service_id)
	{
		return $this->where('service_id',$service_id)->pluck('ticketid', 'id')->toArray();
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
		$removeStatuses = array('error');
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

	public function getAdminNiks()
	{                           return $this->hasManyThrough(AdminNik::class,SysadminActivity::class, 'ticket_id','admin_nik_id','id', 'admin_nik_id');

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
		}
		else Log::error("Ticket id $absentTicket[$service_id] (service id is $service_id) checked as remove from DB because it has had status 4 remove but it doesn't remove, real id $id is not found");
	}

	public function getPriority()
	{
		return $this->hasOne(Priority::class,'id','priority_id');
	}
	/**
	 * sign (check is_closed) when ticket is checked as Closed
	 *
	 * $absentId['service_id'] => $absentId['absentId']
	 * @param array $absentTicket
	 */
	private function moveTicket(array $absentTicket) : void
	{
		$service_id = key($absentTicket);
		$id = $this->getTicketIdFromDb($absentTicket[$service_id], $service_id);
		if ($id) {
			$res = $this::find($id)->update(['is_closed'=>1]);
			Log::info("Ticket id $absentTicket[$service_id] (service id is $service_id) checked as closed", ['result'=>$res]);
		}
		else Log::error("Ticket id $absentTicket[$service_id] (service id is $service_id) checked to moved but error occurs, real id $id is not found");
	}

	/**
	 * get real admins name cross sysadminNiks if applicable
	 * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
	 */
	public function getAdmin()
	{
		return $this->hasManyThrough(Sysadmin::class, AdminNik::class,'admin_nik_id','id','last_replier_nik_id','admin_id');
	}

	public function getAdmins()
	{
		return $this->belongsTo('ticket_id');
	}
}
