<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 26.07.18
 * Time: 10:15
 */

namespace App\Http\TicketBags;

use App\Models\{
	Priority, Service, Status, SysadminActivity, Ticket, AdminNik
};
use Carbon\Carbon;

trait StoreStatistic
{

	private $get_stat_arr;
	private $service;

	public function __construct(string $service, array &$get_stat_arr)
	{
		$this->get_stat_arr = $get_stat_arr;
		$this->service = $service;
	}

	public function store(): void
	{
		# service must be created before else it occurs error
		$service_id = $this->getServiceId($this->service);
		foreach ($this->recurseGetArr() as $val) {
			$ticketid = $this->getTicketIdFromRequest($val['ticketid']);
			if (array_key_exists('ticketid', $val) && $ticketid > 0) {
				$lastreply = $this->getLastreply($val['lastreply']);
				$nik_id = $this->getAdminNikId($val['admin'], $service_id);
				$values = [
					'last_replier_nik_id' => $nik_id,
					'lastreply' => $lastreply,
					'subject' => $this->getSubject($val['subject']),
					'last_is_admin' => 1,
					'priority_id'=>$this->getPriorityDefault(),
					'status_id'=>$this->getStatusDefault(),
				];
				$ticket_id = $this->storeTicketAndGetId($ticketid,$service_id,$values);
				$this->storeAdminActivities($ticket_id, $nik_id, $lastreply, $this->getTimeUses($val['time_uses']));
			} # ticketid not found
			else continue;
		}
	}

	function storeAdminActivities(int $ticket_id, int $nik_id, $lastreply, int $time_uses = 0)
	{
		$sysadmnin_act_m = new SysadminActivity();
		$res = $sysadmnin_act_m::firstOrCreate([
			'sysadmin_niks_id' => $nik_id,
			'ticket_id' => $ticket_id,
			'lastreply' => $lastreply,
		], [
			'time_uses' => $time_uses
		]);
		//todo update ticket with lastreply
//		Ticket::find($ticket_id)->update(['last_replier_nik_id' => $nik_id, 'lastreply' => $lastreply]);
		return $res->id;
	}

	private function recurseGetArr()
	{
		foreach ($this->get_stat_arr as $arr) {
			yield $arr;
		}
	}

	function getServiceId(string $service): int
	{
		$service_m = new Service;
		$service_id = $service_m->getServiceId($service);
		return $service_id;
	}

	function getAdminNikId(string $admin_nik, int $service_id): int
	{
		$adminNik_m = new AdminNik();
		$admin_nik_id = $adminNik_m->getAdminNikId($admin_nik, $service_id);
		return $admin_nik_id;
	}

	function getTicketIdFromRequest($ticketid_req)
	{
		$ticketid = (int)$ticketid_req;
		return $ticketid;
	}

	function getLastreply(string $lastreply_request)
	{
		$lastreply = Carbon::createFromTimeString($lastreply_request);
		return $lastreply;
	}

	function getTimeUses(string $timeFromRequest):int{
		$time_uses = (int)$timeFromRequest;
		return $time_uses;
	}

	function getSubject(string $subjectFromRequest):string{
		$subjTrimmed = trim($subjectFromRequest);
		$subject = (empty($subjTrimmed))?"No subject":$subjTrimmed;
		return $subject;
	}

	function storeTicketandGetId(int $ticketid,int $service_id, array $values){
		$ticket_m = new Ticket();
		$ticket_id = $ticket_m->getTicketId($ticketid,$service_id,$values);
		echo $ticket_id;
		return $ticket_id;
	}
	function getPriorityDefault($priority='n\a'):int{
		$priority_m = new Priority();
		$priority_id = $priority_m::firstOrCreate(['priority'=>$priority]);
		return $priority_id->id;
	}
	function getStatusDefault($status='in progress'){
		$status_m = new Status();
		$status_id = $status_m::firstOrCreate(['name'=>$status])->id;
		return $status_id;
	}
}