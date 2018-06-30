<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 28.06.18
 * Time: 11:43
 */

namespace App\Http\TicketBags;

use Illuminate\Support\Facades\Log;
use App\Models\{
	AdminNik, Priority, Service, SysadminActivity, Ticket, Client, Status
};

trait MotherDaemon
{

	private $service;
	private $pathOfTicketBags;
	private $serviceClass;

	public function __construct($service)
	{
		$this->service = $service;
		$this->pathOfTicketBags = config('services_arr.path');
		$this->serviceClass = $this->pathOfTicketBags . $this->service;
	}

	/**
	 * Get tickets from some service
	 *
	 * using another class 4 retrieve array of tickets/ Logging which service is using at the moment
	 * @return array|null
	 */
	public function getTicketsFromService()
	{
		$serv = new $this->serviceClass;
		$tickets = (array)$serv->getListTikets()['tickets']['ticket'] ?? Null;
		Log::info("Get tickets from $this->service");
		echo "==TICKETS==";print_r($tickets);echo"==/Tickets==\n";
		return $tickets;
	}

	public function getTicketFromService(int $ticketId)
	{
		$serv = new $this->serviceClass;
		$ticket = (array)$serv->getTiket($ticketId)['replies']['reply'];
		Log::info("Get ticket $ticketId from $this->service");
		return $ticket;
	}

	/**
	 * Storing data
	 *
	 * Storing data from tickets array into own tables and logging it
	 * if tickets array is empty logging warn
	 * @param array $tickets
	 */
	public function storeData(Array $tickets)
	{
		if (!$this->isTicketsEmpty($tickets)) {
			# not empty
			# before compare what we have and what income in $tickets array
			$checkedIds = $this->checkId($tickets);
			if (count($checkedIds)) $this->checkAbsentIds($checkedIds); else {
				echo "no absent\n";
			}
			# storin' or updating existing
			echo "new tickets\n";
			foreach ($tickets as $ticket) {
				$ticketID = $ticket['id'];
				# get client's ID
				$clientId = $this->getClientId(array(
						'name' => $ticket['name'],
						'userid' => $ticket['userid'])
				);
				# get priority ID
				$priorityId = $this->getPriorityId(array(
					'priority' => $ticket['priority']
				));
				# get replies count of this ticket
				$replies = (array)$this->getRepliesCount($ticketID);
				# storing ticket and get own ID
				$ticket_id = $this->storeTicket([
						'c_id' => $clientId,
						'ticketid' => $ticketID,
					],[
						'subject' => $ticket['subject'],
						'service_id' => $service_id = $this->getServiceId(),
						'status_id' => $this->getStatusId($ticket['status']),
						'priority_id' => $priorityId,

						'reply_count' => $replies['reply_count'],
						'lastreply_is_admin' => $replies['lastreplyIsAdmin'],

						'lastreply' => $ticket['lastreply'],

					]
				);
				# get admin nik ids if applicable
				if(count($replies['adminNiks'])) {
					$adminNikIdsWithReplies = (array)$this->storeSysadminNiks($clientId, $replies['adminNiks']);
					# storing admin activities in current ticket
					$this->storeAdminActivities($adminNikIdsWithReplies, $ticketID, $clientId, $ticket['lastreply']);
				}
				if ($ticket_id) Log::info('Store ticket', ['ticket_id' => $ticket_id, 'real ticket id' => $ticket['id']]);
			}
		} else {
			# empty
			Log::warning('Tickets are empty');
		}
	}

	/**
	 * Checkong is array empty
	 *
	 *
	 * @param $tickets
	 * @return int|bool
	 */
	private function isTicketsEmpty($tickets)
	{
		$res = (empty($tickets[0]) or empty($tickets)) ? 1 : 0;
		return $res;
	}

	/**
	 * get ID statuse
	 *
	 * getting ID of status from own table or if not exist create new status and return id
	 * @param string $status
	 * @return Int ID status
	 */
	private function getStatusId($status)
	{
		$Status = Status::firstOrNew(['name' => $status]);
		$Status->save();
		return $Status->id;
	}

	/**
	 * getting client inner id
	 *
	 * if client not exist create new storing him
	 * @param array $clientData
	 * @return int ID client
	 */
	private function getClientId(Array $clientData)
	{
		$client = Client::firstOrNew($clientData);
		$client->save();
		return $client->id;
	}

	/**
	 * get service id
	 * @return int ID
	 */
	private function getServiceId()
	{
		return Service::where('name', $this->service)->first()->id;
	}

	private function getPriorityId(array $data)
	{
		$priority = Priority::firstOrNew($data);
		$priority->save();
		return $priority->id;
	}

	/**
	 * store or update ticket table
	 *
	 * if not exist save it
	 * @param array $ticketData
	 * @return int ID ticket
	 */
	private function storeTicket(Array $ticketData, array $updates)
	{
		$res = Ticket::updateOrCreate($ticketData, $updates);
		return $res->id;
	}

	/**
	 * compare Inner and Outer Arrays
	 *
	 * pluck all exists ticketid and compare them with new array from server
	 * which must to remove into closed tickets
	 * @param array $tickets
	 * @return array ticketid
	 */
	private function checkId(array $tickets)
	{
		$id_out = $id_in = $result_arr = array();
		$t = new Ticket();
		$id_in = $t->getTidArray();
		foreach ($tickets as $ticket) {
			array_push($id_out, $ticket['id']);
		}
		$result_arr = array_diff($id_in, $id_out);
		return $result_arr;
	}

	/**
	 * checking what happens with ticketid
	 * @param array $absentIds
	 */
	private function checkAbsentIds(array $absentIds)
	{
		$result_arr = $temp_arr = array();
		$serv = new $this->serviceClass;
		$service_id = Service::where('name', $this->service)->first()->id;
		foreach ($absentIds as $absentId) {
			array_push($temp_arr, $serv->getTiket($absentId));
			$result_arr[] = array('absentId' => $absentId, 'service_id' => $service_id, 'status' => strtolower($temp_arr[0]['result']));
		}
		# push them into model and remove if error or move
		$t = new Ticket();
		return $t->moveRemoveTicketsDecision($result_arr);
	}

	/**
	 * getting reply count and repliers
	 *
	 *
	* [adminNiks] => Array	(
	* [Roman Korolov] => 5
	* [Dmitry Zavertany] => 1
	* )
	*[lastreplyIsAdmin] => 0
	* [reply_count] => 12
	* )
	 * @param int $ticketID
	 * @return array
	 */
	private function getRepliesCount(int $ticketID)
	{
		$lastreplyIsAdmin = 0;
		$all_replies = $replies = array();
		$ticket_full_data = $this->getTicketFromService($ticketID);;
		foreach ($ticket_full_data as $reply) {
			if ($reply['name']==='' AND  $reply['admin'] !== '') {
				$adminName=($reply['admin']!=='')?$reply['admin']:'NoName';
				array_push($all_replies, $adminName);
				$lastreplyIsAdmin = 1;
			} else {
				$lastreplyIsAdmin = 0;
			}
		}
		$replies['adminNiks'] = array_count_values($all_replies);
		$replies['lastreplyIsAdmin']= $lastreplyIsAdmin;
		$replies['reply_count'] = count($ticket_full_data);
		Log::info('Getting reply count',['array'=>$replies]);
		return $replies;
	}

	private function storeSysadminNiks(int $clientId, array  $adminNiks)	{
		$adminNikIdsWithReplies = array();
		foreach ($adminNiks as $adminNik =>$replies) {
			$sysadminNik = AdminNik::firstOrNew(array('admin_nik'=>$adminNik,'c_id'=>$clientId));
			$sysadminNik->save();
			$adminNikIdsWithReplies[$sysadminNik->admin_nik_id] = $replies;
		}
		print_r($adminNikIdsWithReplies);
		return $adminNikIdsWithReplies;
	}

	private function storeAdminActivities(array $adminNikIds, int $ticketID,  int $clientId, $lastreply)
	: void{
		foreach ($adminNikIds as $adminNikId => $reply){
			echo "adminNikId is $adminNikId $ticketID\n";
			$systemadminActivity  = SysadminActivity::updateOrCreate(array(
				'admin_nik_id' =>$adminNikId,
				'ticketid'=>$ticketID,
				'c_id'=>$clientId
			), array(
				'replies'=>$reply,
				'lastreply'=>$lastreply
			));
			if($systemadminActivity->admin_nik_id)
				Log::info("Admin nik id $adminNikId stored in ticket id $ticketID on client $clientId");
			else
				Log::error("Admin nik id $adminNikId not stored in ticket id $ticketID on client $clientId");
		}
	}
}