<?php

namespace App\Http\TicketBags;

use App\Http\Libs\TicketLibs;
use Carbon\Carbon;
use App\Models\{
	Priority, Service, Status, Ticket
};
use Illuminate\Support\Facades\Log;

trait Billmgr
{
	use TicketLibs;

	private $url = '';
	private $data;

	/**
	 * Billmgr constructor.
	 * @param string $service name
	 */
	public function __construct($service)
	{
		$user = config('curl-connection.' . $service . '.identifier');//'r.wayne';//'techmonitoring';
		$pass = config('curl-connection.' . $service . '.secret');//'eC%!nhp96g'; //'BaEC3LMGci';
		$format = 'json';
		$this->url = sprintf(config('curl-connection.' . $service . '.url'), $format, $user, $pass);
		$this->service = $service;
		$this->setService_id();
		$this->setData();
		$this->setTickets();
	}

	/**
	 * Get data from service (all)
	 *
	 * @return array
	 */
	private function getData()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = json_decode(curl_exec($ch), true);
		curl_close($ch);
		return $data;
	}

	private function setData()
	{
		$data = $this->getData();
		if (empty($data)) Log::error(sprintf('Service %s not available', $this->service));
		$this->data = $data ?? [];
	}

	protected function getTicketsFromService()
	{
		$tickets = (isset($this->data['doc']['elem'])) ? $this->data['doc']['elem'] : [];
		return $tickets;
	}

	public function getAndStoreDataFromTicket()
	{
		$service_m = new Service();
		$service_m->setServiceAvailable($this->service_id, $this->is_service_available($this->data));
		foreach ($this->recurseTickets() as $ticket) {
			$ticket_m = new Ticket();
			$status_m = new Status();
			$status_id = $status_m->getStatusId('customer-reply');
			$lastreply = $this->getLastreply($ticket);
			$ticketid = $this->getTicketid($ticket);
			$subject = $this->getSubject($ticket);
			$priority_id = $this->getPriorityId($ticket);
			$ticket_id = $ticket_m->getTicketIdFromDb($ticketid, $this->service_id);
			$this->setOuterTicketsIds($ticketid);
			if ($ticket_id) {
				$is_admin = $this->isAdmin(
					$ticket_m->getIsAdminFromDb($ticket_id),
					$this->isCustomerReply($ticket),
					$ticket_m->getLastreplierId($ticket_id),
					$this->getLastreply($ticket),
					$ticket_m->getLastreply($ticket_id)
				);
				if ($is_admin) {
					$lastreply = $ticket_m->getLastreply($ticket_id);
					$status_id = $status_m->getStatusId('in progress');
				}
				# update ticket
				$ticket_m->updateTicket($ticket_id, $status_id, $priority_id, $lastreply, $is_admin);
			} else {
				# store new ticket
				$lastreply = $this->getLastreply($ticket);
				$ticket_m->storeNewTicket($ticketid, $this->service_id, $subject, $status_id, $priority_id, $lastreply);
			}
		}
		# check existing ticket in DB
		$this->closeTickets($this->service_id);
	}

	public function getTicketid(array $ticket): int
	{
		return $ticket['id']['$'];
	}

	public function getSubject(array $ticket): string
	{
		return trim($ticket['name']['$']);
	}

	public function getPriorityId(array $ticket): int
	{
		$priority = 'medium';
		$priority_m = new Priority();
		if (isset($ticket['highpriority']) && $ticket['highpriority']['$'] == 'on') $priority = 'high';
		elseif (isset($ticket['lowpriority']) && $ticket['lowpriority']['$'] == 'on') $priority = 'low';
		$priority_id = $priority_m->getPriorityId($priority);
		return $priority_id;
	}

	public function getLastreply(array $ticket): string
	{
		$Carbon = new Carbon();
		preg_match("/(?<days>\d+)d\+(?<hours>\d{2}):(?<minutes>\d{2})/", $ticket['delay']['$'], $time_arr);
		$lastreply = $Carbon::createFromTime($Carbon::now()->hour, $Carbon::now()->minute, 00)->
		subDays($time_arr['days'])->
		subHours($time_arr['hours'])->
		subMinutes($time_arr['minutes'])->
		format('Y-m-d H:i:s');
		return $lastreply;
	}


	protected function isCustomerReply(array $ticket): bool
	{
		$Carbon = new Carbon();
		$ticket_m = new Ticket();
		$lastreply = $this->getLastreply($ticket);
		$lastreply_from_db = $ticket_m->getLastreply($this->getTicketIdFromDb($this->getTicketid($ticket), $this->service_id));
		$lastreply_c = $Carbon::parse($lastreply);
		$lastreply_from_db_c1 = $Carbon::parse($lastreply_from_db)->subMinute();
		$lastreply_from_db_c2 = $Carbon::parse($lastreply_from_db)->addMinute();
		# is_customer
		$is_customer = $lastreply_c->between($lastreply_from_db_c1, $lastreply_from_db_c2);
		return $is_customer;
	}

	protected function is_service_available(array $tickets = []): bool
	{
		return !empty($tickets);
	}

	protected function isAdmin(bool $is_admin_db, bool $is_customer, $last_replier_nik_id, $lastreply, $lastreply_from_db): int
	{
		$Carbon = new Carbon();
		$lastreply_c = $Carbon::parse($lastreply);
		$is_admin = (($Carbon::parse($lastreply_from_db)->gt($lastreply_c) || !$is_customer) && $is_admin_db && $last_replier_nik_id) ? 1 : 0;
		return $is_admin;
	}
}