<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 28.06.18
 * Time: 11:43
 */

namespace App\Http\TicketBags;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\{
	Priority, Service, Ticket, Status
};
use Exception;
use App\Http\Libs\TicketLibs;

trait MotherWhmcsDaemonLite
{
	use TicketLibs;
	private $whmcs;

	function __construct($service)
	{
		$this->service = $service;
		# choose service settings
		$this->whmcs = new Whmcsapi($service);
		$this->setService_id();
		$this->setTickets();
	}

	function __destruct()
	{
		unset($this->service);
		unset($this->whmcs);
		unset($this->service_id);
		unset($this->tickets);
	}

	protected function getTicketsFromService()
	{
		$service_m = new Service();
//		$msg = '==Get tickets from %5s==';
		$err_msg = '==Get tickets error (or empty) from %s with msg %s==';
		try {
			# try 2 get tickets if error read message
			$getListTickets = $this->whmcs->getListTikets();
			if (!empty($getListTickets) && $this->is_service_available($getListTickets)) {
				$service_m->setServiceAvailable($this->service_id, true);
				$tickets = (array)array_get($getListTickets, 'tickets.ticket');
				if ($tickets == null) throw new Exception($getListTickets['message'] ?? $getListTickets['totalresults']);
				return $tickets;
			} else {
				$service_m->setServiceAvailable($this->service_id, false);
			}
		} catch (Exception $e) {
			Log::warning(sprintf($err_msg, $this->service, $e->getMessage()));
			# return Null if response Error or Empty tickets array
			return Null;
		}
		return null;
	}

	public function getandStoreDataFromTicket(): void
	{
		$ext_flags = $this->getExtFlags();
		foreach ($this->recurseTickets() as $ticket) {
			if (in_array($ticket['flag'], $ext_flags)) continue;
			$ticketid = $this->getTicketid($ticket);
			$lastreply = $this->getLastreply($ticket);
			if ($this->service == 'hostiman' and (
					in_array($ticketid, $this->getListTicketsFilter())
					or $this->isLastReplyLessThenNeedle($lastreply)
				)
			) continue;
			$ticket_m = new Ticket();
			$service_m = new Service();
			$status_m = new Status();
			$priority_m = new Priority();
			$status_id = $status_m->getStatusId($ticket['status']);
			$subject = $this->getSubject($ticket);
			$priority_id = $priority_m->getPriorityId($ticket['priority']);
			$ticket_id = $this->getTicketIdFromDb($ticketid, $this->service_id);
			$is_customer = $this->isCustomerReply($ticket);
			$this->setOuterTicketsIds($ticketid);
			$service_m->setServiceAvailable($this->service_id, true);

			if ($ticket_id) {
				$this->updateTicket(
					$ticket_id, $status_id, $priority_id, $lastreply,
					$this->isAdmin($ticket_m->getIsAdminFromDb($ticket_id), $is_customer)
				);
			} else {
				$ticket_m->storeNewTicket($ticketid, $this->service_id, $subject, $status_id, $priority_id, $lastreply);
			}
		}
		# check existing ticket in DB
		$this->closeTickets($this->service_id);
	}

	public function getTicketid(array $ticket): int
	{
		$ticketid = (int)$ticket['id'];
		return $ticketid;
	}

	public function getSubject(array $ticket): string
	{
		$subject = $ticket['subject'];
		return $subject;
	}

	public function getLastreply(array $ticket): string
	{
		$lastreply = Carbon::parse($ticket['lastreply'])->addSeconds($this->getTimeCorrection())->toDateTimeString();
		return $lastreply;
	}

	protected function isAdmin(bool $is_admin_db, bool $is_customer): int
	{
		$des = 0;
//		$is_admin=(!$is_customer && $is_admin_db)?1:0;
		if ($is_customer) $des = 0;
		elseif ($is_admin_db) $des++;
		return $des;
		/*if (
			/*Carbon::parse($getLastreplyFromDb)->
			between(Carbon::parse($lastreply)->subMinute(), Carbon::parse($lastreply)->addMinute())&& $is_admin_db &&
			!$is_customer) {
			$is_admin = 1;
		}*/
	}

	public function isCustomerReply(array $ticket): bool
	{
		$statuses = ['customer-reply'];
		$is_customer = in_array(strtolower($ticket['status']), $statuses);
		return $is_customer;
	}

	/**
	 * @param array $tickets
	 * @return bool
	 */
	protected function is_service_available(array $tickets): bool
	{
		return key_exists('totalresults', $tickets);
	}

	/**
	 * External flags in ticket
	 * @return array
	 */
	private function getExtFlags(): array
	{
		return config('curl-connection.' . $this->service . '.ext_flags');
	}

	/**
	 * @return int|null
	 */
	private function getExtDays()
	{
		return config('curl-connection.' . $this->service . '.days') ?? null;
	}

	/**
	 * @return array
	 */
	private function getListTicketsFilter()
	{
		return config('curl-connection.' . $this->service . '.ticketids');
	}

	/**
	 * Compare 2 dates before then nnedle
	 *
	 * @param $lastreply
	 * @return bool
	 */
	private function isLastReplyLessThenNeedle($lastreply): bool
	{
		$pastDays = Carbon::now()->subDays($this->getExtDays());
		$lastreply_parse = Carbon::parse($lastreply);
		return $lastreply_parse->lt($pastDays);
	}

	/**
	 * External flags in ticket
	 * @return integer
	 */
	private function getTimeCorrection(): int
	{
		return config('curl-connection.' . $this->service . '.time_correction');
	}
}