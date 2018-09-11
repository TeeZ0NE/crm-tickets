<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 28.06.18
 * Time: 11:43
 */

namespace App\Http\TicketBags;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\{
	AdminNik, Priority, Service, SysadminActivity, Ticket, Status
};
use Exception;
use App\Http\TicketBags\Whmcsapi;


trait MotherWhmcsDaemonLite
{

	private $service;
	private $whmcs;
	private $service_id;
	private $tickets;
	private $outerTicketsIds = [];

	function __construct($service)
	{
		$this->service = $service;
		# choose service settings
		$this->whmcs = new Whmcsapi($service);
		$this->service_id = $this->getServiceId($service);
		$this->tickets = $this->getTicketsFromService();
	}

	function __destruct()
	{
		unset($this->service);
		unset($this->whmcs);
		unset($this->service_id);
		unset($this->tickets);
	}

	/**
	 * Get tickets from some service
	 *
	 * using another class 4 retrieve array of tickets/ //todo: debug Logging which service is using at the moment
	 * @return array|null
	 */
	function getTicketsFromService()
	{
		$msg = '==Get tickets from %5s==';
		$err_msg = '==Get tickets error from %s with error msg %s==';
		try {
			# try 2 get tickets if error read message
			$tickets = (array)array_get($this->whmcs->getListTikets(), 'tickets.ticket');
			if ($tickets == null) throw new Exception($this->whmcs->getListTikets()['message']);
			return $tickets;
		} catch (Exception $e) {
			$this->setServiceAvailable(false);
			Log::error(sprintf($err_msg,$this->service,$e->getMessage()));
			# return Null if response Error or Empty tickets array
			return Null;
		}
	}

	/**
	 * Getting service id
	 *
	 * @param $service
	 * @return mixed
	 */
	private function getServiceId($service)
	{
		$service_m = new Service();
		$service_id = $service_m::firstOrCreate(['name' => $service])->id;
		return $service_id;
	}

	/**
	 * @return \Generator
	 */
	private function recurseTickets()
	{
		if ($this->tickets) {
			foreach ($this->tickets as $ticket) {
				yield $ticket;
			}
		}
	}

	/**
	 * Main method
	 *
	 * @return void
	 */
	function getandStoreDataFromTicket(): void
	{
		foreach ($this->recurseTickets() as $ticket) {
			$ticketid = $this->getTicketid($ticket);
			$status_id = $this->getStatusId($ticket);
			$subject = $this->getSubject($ticket);
			$priority_id = $this->getPriorityId($ticket);
			$lastreply = $this->getLastreply($ticket);
			$ticket_id = $this->ticketExist($ticketid, $this->service_id);
			$is_customer = $this->isCustomerReply($ticket);
			$this->setOuterTicketsIds($ticketid);
			$this->setServiceAvailable(true);

			if ($ticket_id) {
				$this->updateTicket(
					$ticket_id, $status_id, $priority_id, $lastreply,
					$this->isAdmin($lastreply, $this->getLastreplyFromDb($ticket_id), $this->getIsAdminFromDb($ticket_id),$is_customer));
			} else {
				$this->storeNewTicket($ticketid, $this->service_id, $subject, $status_id, $priority_id, $lastreply);
			}
		}
		# check existing ticket in DB
		$this->closeTickets();
	}

	/**
	 * Get attribute ticketid
	 *
	 * @param array $ticket
	 * @return int
	 */
	private function getTicketid(array $ticket): int
	{
		$ticketid = (int)$ticket['id'];
		return $ticketid;
	}

	/**
	 * Get status id from DB
	 *
	 * @param array $ticket
	 * @return int
	 */
	private function getStatusId(array $ticket): int
	{
		$status_m = new Status();
		$status_id = $status_m::firstOrCreate(['name' => $ticket['status']])->id;
		return $status_id;
	}

	/**
	 * Get attribute status
	 *
	 * @param array $ticket
	 * @return string
	 */
	private function getSubject(array $ticket): string
	{
		$subject = $ticket['subject'];
		return $subject;
	}

	/**
	 * Get priority id from db
	 *
	 * @param array $ticket
	 * @return int
	 */
	private function getPriorityId(array $ticket): int
	{
		$priority_m = new Priority();
		$priority_id = $priority_m::firstOrCreate(['priority' => $ticket['priority']])->id;
		return $priority_id;
	}

	/**
	 * get attribute lastreply
	 *
	 * @param array $ticket
	 * @return string
	 */
	private function getLastreply(array $ticket): string
	{
		$lastreply = $ticket['lastreply'];
		return $lastreply;
	}

	/**
	 * Get ticket_id from db
	 *
	 * if ticket not found (null) return 0 or false
	 *
	 * @param int $ticketid
	 * @param int $service_id
	 * @return int
	 */
	private function ticketExist(int $ticketid, int $service_id): int
	{
		$ticket_m = new Ticket();
		$ticket_exist = $ticket_m->where(['ticketid' => $ticketid, 'service_id' => $service_id])->first();
		$ticket_id = ($ticket_exist) ? $ticket_exist->id : 0;
		return $ticket_id;
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
	private function storeNewTicket(int $ticketid, int $service_id, string $subject, int $status_id, int $priority_id, $lastreply): int
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
	 * Update ticket
	 *
	 * @param int $ticket_id
	 * @param int $status_id
	 * @param int $priority_id
	 * @param string $lastreply
	 * @param int $is_admin
	 * @return void
	 */
	private function updateTicket(int $ticket_id, int $status_id, int $priority_id, string $lastreply, int $is_admin)
	{
		$ticket_m = new Ticket();
		$ticket_m::find($ticket_id)->update([
			'status_id' => $status_id,
			'priority_id' => $priority_id,
			'lastreply' => $lastreply,
			'last_is_admin' => $is_admin,
			'is_closed' => 0,
		]);

		$isActiveUser = $this->isLastReplierActive($this->getLastreplierId($ticket_id));
		if (!$this->getUserAssignId($ticket_id) && $isActiveUser['active'])
			$this->setUserAssignId($ticket_id, $isActiveUser['user_id']);
	}

	/**
	 * get lastreply from db
	 * @param int $ticket_id
	 * @return string
	 */
	private function getLastreplyFromDb(int $ticket_id): string
	{
		$ticket_m = new Ticket();
		$lastreply = $ticket_m::find($ticket_id)->lastreply;
		return $lastreply;
	}

	/**
	 * get is last reply admin
	 *
	 * @param int $ticket_id
	 * @return int
	 */
	private function getIsAdminFromDb(int $ticket_id): int
	{
		$ticket_m = new Ticket();
		$is_admin_db = $ticket_m::find($ticket_id)->last_is_admin;
		return $is_admin_db;
	}

	/**
	 * checking does last reply make admin
	 *
	 * @param string $lastreply
	 * @param string $getLastreplyFromDb
	 * @param bool $is_admin_db
	 * @param bool $is_customer last reply status
	 * @return int
	 */
	private function isAdmin(string $lastreply, string $getLastreplyFromDb, bool $is_admin_db, bool $is_customer): int
	{
		if (Carbon::parse($getLastreplyFromDb)->
			between(Carbon::parse($lastreply)->subMinute(), Carbon::parse($lastreply)->addMinute())
			&& $is_admin_db && !$is_customer) {
			$is_admin = 1;
		}
		else $is_admin = 0;
		return $is_admin;
	}

	/**
	 * Get all tickets ids from curr service
	 * @return array
	 */
	private function getInnerTicketsIds(): array
	{
		$ticket_m = new Ticket();
		$inner_ids = $ticket_m->getTidArray($this->service_id);
		return $inner_ids;
	}

	/**
	 * Set all incoming tickets ids
	 *
	 * @param $ticketid
	 */
	private function setOuterTicketsIds($ticketid): void
	{
		array_push($this->outerTicketsIds, $ticketid);
	}

	/**
	 * Get all incoming tickets ids
	 *
	 * @return array
	 */
	private function getOuterTicketsIds(): array
	{
		return $this->outerTicketsIds;
	}

	/**
	 * Compare inner ids and income ids
	 *
	 * Result array is which ids will close
	 * @param array $inner_ids
	 * @param array $outer_ids
	 * @return array
	 */
	private function getClosedTickets(array $inner_ids, array $outer_ids)
	{
		$absent_ids = array_diff($inner_ids, $outer_ids);
		return $absent_ids;
	}

	/**
	 * Close absent tickets in db
	 *
	 * @return void
	 */
	private function closeTickets(): void
	{
		$absent_ids = $this->getClosedTickets($this->getInnerTicketsIds(), $this->getOuterTicketsIds());
		$ticket_m = new Ticket();
		foreach ($absent_ids as $absent_id) {
			$ticket_m->closeTicket($absent_id, $this->service_id);
		}
	}

	private function getUserAssignId(int $ticket_id)
	{
		return Ticket::find($ticket_id)->user_assign_id;
	}

	private function isLastReplierActive(int $last_replier_nik_id)
	{
		# if user active assign to him his ticket
		$adminNik_m = new AdminNik();
		return $user_active_collection = $adminNik_m->isUserNikIdActive($last_replier_nik_id);

	}

	private function setUserAssignId(int $ticket_id, int $user_id)
	{
		Ticket::find($ticket_id)->update(['user_assign_id' => $user_id]);
	}

	/**
	 * Get last replier 4 curr ticket
	 * @param int $ticket_id
	 * @return int lastreplier_nik_id
	 */
	private function getLastreplierId(int $ticket_id): int
	{
		$lastreplier_nik_id = Ticket::find($ticket_id)->last_replier_nik_id;
		return $lastreplier_nik_id;
	}

	/**
	 * Check status (-es) customer
	 *
	 * if it in customer statuses then customer reply
	 * @param array $ticket
	 * @return bool
	 */
	private function isCustomerReply(array $ticket):bool
	{
		$statuses=['customer-reply'];
		$is_customer =in_array(strtolower($ticket['status']),$statuses);
		return $is_customer;
	}

	/**
	 * Set available service
	 *
	 * @param bool $is_available
	 */
	private function setServiceAvailable(bool $is_available){
		$service_m = new Service();
		$service_m::find($this->service_id)->update(['is_available'=>$is_available]);
	}
}