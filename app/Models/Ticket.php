<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
	Model, ModelNotFoundException
};
use Illuminate\Support\Facades\Log;

class Ticket extends Model
{
	protected $fillable = array('c_id', 'ticketid', 'subject', 'service_id', 'status_id', 'priority_id', 'reply_count', 'lastreply', 'lastreply_is_admin', 'deadline_id');

	/**
	 * get' ticket's owner
	 *
	 * get' id and name of client of current ticket
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function getClient()
	{
		return $this->hasOne(Client::class, 'id', 'c_id')->select('id', 'name');
	}

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
	 * getting Service
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function getService()
	{
		return $this->hasOne(Service::class, 'id', 'service_id');
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
			$id = $this::where(array(['ticketid', '=', $tid], ['service_id', '=', $service_id]))->firstOrFail()->id;
		} catch (ModelNotFoundException $mnf) {
			Log::error("Error getting ID from DB where ticketid is $tid and service_id is $service_id");
		}
		return $id;
	}

	/**
	 * get array from DB 2 compare with outer array ticketid tid
	 *
	 * @return array of ticketid
	 */
	public function getTidArray()
	{
		return $this->pluck('ticketid', 'id')->toArray();
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
	{                           return $this->hasManyThrough(AdminNik::class,SysadminActivity::class, 'ticketid','admin_nik_id','ticketid', 'admin_nik_id');

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

	/**
	 * move ticket from Ticket table 2 ClosedTickets table
	 *
	 * $absentId['service_id'] => $absentId['absentId']
	 * @param array $absentTicket
	 */
	private function moveTicket(array $absentTicket) : void
	{
		$service_id = key($absentTicket);
		$id = $this->getTicketIdFromDb($absentTicket[$service_id], $service_id);
		if ($id) {
			$copy = $this::select(['c_id', 'ticketid', 'subject', 'priority_id', 'service_id', 'reply_count', 'compl', 'lastreply'])->find($id)->toArray();
			$res = ClosedTicket::insert($copy);
			if ($res) $this::find($id)->delete();
			Log::info("Ticket id $absentTicket[$service_id] (service id is $service_id) moved to closed", ["result" => $res]);
		}
		else Log::error("Ticket id $absentTicket[$service_id] (service id is $service_id) checked to moved but error occurs, real id $id is not found");
	}
}
