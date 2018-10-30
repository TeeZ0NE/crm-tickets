<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 18.09.18
 * Time: 18:01
 */

namespace App\Http\Libs;

use App\Models\{
	AdminNik, Service, Ticket
};

trait TicketLibs
{
	private $outerTicketsIds = [];
	private $service;
	private $service_id;
	private $tickets;

	/**
	 * Check is user active
	 * @param int $last_replier_nik_id
	 * @return array
	 */
	private function isLastReplierActive(int $last_replier_nik_id)
	{
		# if user active assign to him his ticket
		$adminNik_m = new AdminNik();
		return $user_active_collection = $adminNik_m->isUserNikIdActive($last_replier_nik_id);
	}

	/**
	 * Update ticket
	 *
	 * @param int $ticket_id
	 * @param int $status_id
	 * @param int $priority_id
	 * @param string|object $lastreply
	 * @param int $last_is_admin
	 * @param string subject
	 * @return void
	 */
	private function updateTicket(int $ticket_id, int $status_id, int $priority_id, $lastreply, int $last_is_admin, string $subject)
	{
		$ticket_m = new Ticket();
		$ticket_m->updateTicket($ticket_id, $status_id, $priority_id, $lastreply, $last_is_admin, $subject);

		$isActiveUser = $this->isLastReplierActive($ticket_m->getLastreplierId($ticket_id));
		if (!$ticket_m->getUserAssignId($ticket_id) && $isActiveUser['active'])
			$ticket_m->setUserAssignId($ticket_id, $isActiveUser['user_id']);
	}

	/**
	 * Get all tickets ids from curr service
	 * @param int $service_id
	 * @return array
	 */
	private function getInnerTicketsIds(int $service_id): array
	{
		$ticket_m = new Ticket();
		$inner_ids = $ticket_m->getTidArray($service_id);
		return $inner_ids;
	}

	/**
	 * Set all incoming tickets ids
	 *
	 * @param int $ticketid
	 */
	private function setOuterTicketsIds(int $ticketid): void
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
	 * use setOuterTicketsIds(int $ticketid) method instead
	 * @param  int $service_id
	 * @return void
	 */
	private function closeTickets(int $service_id): void
	{
		$absent_ids = $this->getClosedTickets($this->getInnerTicketsIds($service_id), $this->getOuterTicketsIds());
		$ticket_m = new Ticket();
		foreach ($absent_ids as $absent_id) {
			$ticket_m->closeTicket($absent_id, $this->service_id);
		}
	}

	/**
	 * Get tickets from some service
	 *
	 * @return array|null
	 */
	abstract protected function getTicketsFromService();


	private function setTickets()
	{
		$this->tickets = $this->getTicketsFromService();
	}

	private function setService_id()
	{
		$service_m = new Service();
		$this->service_id = $service_m->getServiceId($this->service);
	}

	/**
	 * @return \Generator
	 */
	protected function recurseTickets()
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
	abstract function getAndStoreDataFromTicket();

	/**
	 * Get attribute ticketid
	 *
	 * @param array $ticket
	 * @return int
	 */
	abstract function getTicketid(array $ticket): int;

	/**
	 * Get attribute status
	 *
	 * @param array $ticket
	 * @return string
	 */
	abstract function getSubject(array $ticket): string;

	/**
	 * get attribute lastreply
	 *
	 * @param array $ticket
	 * @return string|object
	 */
	abstract public function getLastreply(array $ticket): string;

	/**
	 * checking does last reply make admin
	 *
	 * @param bool $is_admin_db
	 * @param bool $is_customer last reply status
	 * @param int $last_replier_nik_id
	 * @param string|object $lastreply
	 * @param string|object $lastreply_from_db
	 * @return int
	 */
	abstract protected function isAdmin(bool $is_admin_db, bool $is_customer, int $last_replier_nik_id=0,$lastreply='',$lastreply_from_db=''): int;

	/**
	 * Check status (-es) customer
	 *
	 * if it in customer statuses then customer reply
	 * @param array $ticket
	 * @return bool
	 */
	abstract protected function isCustomerReply(array $ticket = []): bool;

	/**
	 * Is total results key exists
	 * @param array $tickets
	 * @return bool
	 */
	abstract protected function is_service_available(array $tickets = []): bool;

	/**
	 * @param int $ticketid
	 * @param int $service_id
	 * @return int
	 */
	private function getTicketIdFromDb(int $ticketid, int $service_id):int
	{
		$ticket_m = new Ticket();
		$ticket_id = $ticket_m->getTicketIdFromDb($ticketid,$service_id);
		return $ticket_id;
	}
}