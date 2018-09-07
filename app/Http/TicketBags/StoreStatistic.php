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

	/**
	 * Storing admin activity in current ticket
	 *
	 * @param int $ticket_id
	 * @param int $nik_id
	 * @param $lastreply
	 * @param int $time_uses
	 */
	private function storeAdminActivities(int $ticket_id, int $nik_id, $lastreply, int $time_uses = 0):void
	{
		$sysadmnin_act_m = new SysadminActivity();
		$sysadmnin_act_m::firstOrCreate([
			'sysadmin_niks_id' => $nik_id,
			'ticket_id' => $ticket_id,
			'lastreply' => $lastreply,
		], [
			'time_uses' => $time_uses
		]);
	}

	/**
	 * Iterator
	 * @return \Generator
	 */
	private function recurseGetArr()
	{
		foreach ($this->get_stat_arr as $arr) {
			yield $arr;
		}
	}

	/**
	 * Get service id from db
	 *
	 * @param string $service
	 * @return int
	 */
	private function getServiceId(string $service): int
	{
		$service_m = new Service;
		$service_id = $service_m->getServiceId($service);
		return $service_id;
	}

	/**
	 * Get admin nik id
	 *
	 * @param string $admin_nik
	 * @param int $service_id
	 * @return int
	 */
	private function getAdminNikId(string $admin_nik, int $service_id): int
	{
		$adminNik_m = new AdminNik();
		$admin_nik_id = $adminNik_m->getAdminNikId($admin_nik, $service_id);
		return $admin_nik_id;
	}

	/**
	 * get andreturn ticket id from request
	 *
	 * @param $ticketid_req
	 * @return int
	 */
	private function getTicketIdFromRequest($ticketid_req):int
	{
		$ticketid = (int)$ticketid_req;
		return $ticketid;
	}

	/**
	 * Get lastreply from request
	 *
	 * @param string $lastreply_request
	 * @return object
	 */
	private function getLastreply(string $lastreply_request):object
	{
		$lastreply = Carbon::createFromTimeString($lastreply_request);
		return $lastreply;
	}

	/**
	 * Get time using 2 answer client
	 *
	 * @param string $timeFromRequest
	 * @return int
	 */
	private function getTimeUses(string $timeFromRequest):int{
		$time_uses = (int)$timeFromRequest;
		return $time_uses;
	}

	/**
	 * Get subject from request
	 *
	 * if it empty return 'No subject'
	 * @param string $subjectFromRequest
	 * @return string
	 */
	private function getSubject(string $subjectFromRequest):string{
		$subjTrimmed = trim($subjectFromRequest);
		$subject = (empty($subjTrimmed))?"No subject":$subjTrimmed;
		return $subject;
	}

	/**
	 * Storing ticket 2 db
	 *
	 * @param int $ticketid
	 * @param int $service_id
	 * @param array $values
	 * @return mixed
	 */
	private function storeTicketandGetId(int $ticketid,int $service_id, array $values){
		$ticket_m = new Ticket();
		$ticket_id = $ticket_m->getTicketId($ticketid,$service_id,$values);
		echo $ticket_id;
		return $ticket_id;
	}

	/**
	 * Get default priority
	 *
	 * or create one if doesn't exist
	 * @param string $priority
	 * @return int
	 */
	private function getPriorityDefault($priority='n\a'):int{
		$priority_m = new Priority();
		$priority_id = $priority_m::firstOrCreate(['priority'=>$priority]);
		return $priority_id->id;
	}

	/**
	 * Get default status
	 *
	 * or create one if doesn't exist
	 * @param string $status
	 * @return mixed
	 */
	private function getStatusDefault($status='in progress'){
		$status_m = new Status();
		$status_id = $status_m::firstOrCreate(['name'=>$status])->id;
		return $status_id;
	}
}