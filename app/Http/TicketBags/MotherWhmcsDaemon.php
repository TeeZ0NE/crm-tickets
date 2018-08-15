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
	AdminNik, Priority, Service, SysadminActivity, Ticket, Status
};
use Exception;

//use App\Http\TicketBags\Whmcsapi;

trait MotherWhmcsDaemon
{

	private $service;
	private $whmcs;
	private $service_id;

	public function __construct($service)
	{
		$this->service = $service;
		# choose service settings
		$this->whmcs = new Whmcsapi($service);
		$this->service_id = $this->getServiceId();
	}
	public function __destruct()
	{
		unset($this->service);
		unset($this->whmcs);
		unset($this->service_id);
	}
	/**
	 * Get tickets from some service
	 *
	 * using another class 4 retrieve array of tickets/ Logging which service is using at the moment
	 * @return array|null
	 */
	public function getTicketsFromService()
	{
		$msg = '==Get tickets from %5s==';
		$err_msg = '==Get tickets error from %s with error msg %s==';
		try {
			Log::info(sprintf($msg,$this->service));
			# try 2 get tickets if error read message
			$tickets =(array)array_get($this->whmcs->getListTikets(),'tickets.ticket');
			if ($tickets == null) throw new Exception($this->whmcs->getListTikets()['message']);
			return $tickets;
		} catch (Exception $e) {
			Log::error(sprintf($err_msg,$this->service,$e->getMessage()));
			# return Null if response Error or Empty tickets array
			return Null;
		}
	}

	/**
	 * Getting ticket from service
	 *
	 * Get just one ticket 4 getting replies and who answered
	 *
	 * @param int $ticketId
	 * @return array|null
	 */
	public function getTicketFromService(int $ticketId)
	{
		$err_msg = '"Get ticket $ticketId from %s with error msg %s';
		try {
//			Log::info("Get ticket $ticketId from $this->service");
			$ticket = (array_key_exists('replies', $this->whmcs->getTiket($ticketId)))
				? (array)$this->whmcs->getTiket($ticketId)['replies']['reply']
				: Null;
			if ($ticket == Null) throw new Exception($ticket['message']);
			return $ticket;
		} catch (Exception $e) {
			Log::error(sprintf($err_msg,$this->service,$e->getMessage()));
			return Null;
		}
	}

	/**
	 * Storing data
	 *
	 * Storing data from tickets array into own tables and logging it
	 * ticketID is ticketid which comes from service
	 * ticket_id is AI from DB after storing ticket data in DB
	 * if tickets array is empty logging warn
	 * @param array $tickets
	 */
	public function storeData(Array $tickets) :void
	{
		if (!$this->isTicketsEmpty($tickets)) {
			$adminNikIdsWithReplies = [];
			# not empty
			# before compare what we have and what income in $tickets array
			$checkedIds = $this->checkId($tickets);
			if (count($checkedIds)) $this->checkAbsentIds($checkedIds);
			else Log::info("Absent tickets not found. Continue...");
			# storin' or updating existing
			foreach ($tickets as $ticket) {
				$ticketID = $ticket['id'];

				# get priority ID
				$priorityId = $this->getPriorityId(array(
					'priority' => $ticket['priority']
				));
				# get replies count of this ticket
				$replies = (array)$this->getRepliesCount($ticketID);
				$sav_l_repl_id = $this->getLastReplierNickId($ticketID);
				# get admins nik ids if applicable
				if (count($replies['adminNiks'])) {
					$adminNikIdsWithReplies = (array)$this->storeSysadminNiks($this->service_id, $replies['adminNiks'], $replies['dates'], $replies['lastReplyAdmin']);
				}
				else {
					# if is set wrong id storing
					unset($adminNikIdsWithReplies['last_replier_nik_id']);
				}
				# getting last replier  nick id if applicable
				$last_replier_nik_id = $adminNikIdsWithReplies['last_replier_nik_id'] ?? $sav_l_repl_id;
//				$user_assign_id

				$store_data = [
					'subject' => $ticket['subject'],
					'service_id' => $this->service_id,
					'status_id' => $this->getStatusId($ticket['status']),
					'priority_id' => $priorityId,

					'last_replier_nik_id' => $last_replier_nik_id,
					'last_is_admin' => $replies['last_is_admin'],
					'lastreply' => $ticket['lastreply'],
//					'user_assign_id' => ($last_replier_nik_id)?Null:Null,
				];

//				if($user_active_collection['active'])  $store_data['user_assign_id']=$user_active_collection['user_id'];
				# storing ticket and get own ID
				$ticket_id = $this->storeTicket([
					'ticketid' => $ticketID,
				], $store_data);
				# if user active and nobody assigned yet assign to him his ticket
				$isActiveUser = $this->isLastReplierActive($last_replier_nik_id);
				if(!$this->getUserAssignId($ticket_id) && $isActiveUser['active'])
					$this->setUserAssignId($ticket_id,$isActiveUser['user_id']);
				# storing admins activities in current ticket
				if (count($replies['adminNiks'])) {
					$this->storeAdminActivities($adminNikIdsWithReplies, $ticket_id);
				}
				if (!$ticket_id) Log::error('Ticket doesn\'t store', ['ticket_id' => $ticket_id, 'real ticket id' => $ticketID]);
			}
			Log::info("==/Get tickets from $this->service==");
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
	private function isTicketsEmpty($tickets) :bool
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
	private function getStatusId($status) :int
	{
		$Status = Status::firstOrNew(['name' => $status]);
		$Status->save();
		return $Status->id;
	}

	/**
	 * get service id
	 * @return int ID
	 */
	private function getServiceId() :int
	{
		$service = new Service();
		try {
			return $service::firstOrCreate(['name' => $this->service])->id;
		} catch (Exception $e) {
			Log::error("Service not found. Please add it first");
			die('Service id error. See log');
		}
	}

	/**
	 * get priority id
	 * @param array $data
	 * @return int
	 */
	private function getPriorityId(array $data):int
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
	 * @param  array $updates
	 * @return int ID ticket
	 */
	private function storeTicket(Array $ticketData, array $updates) :int
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
	private function checkId(array $tickets):array
	{
		$id_out = $id_in = $result_arr = [];
		$t = new Ticket();
		# get all ticketsids from db 4 compare
		$id_in = $t->getTidArray($this->service_id);
		foreach ($tickets as $ticket) {
			array_push($id_out, $ticket['id']);
		}
		$result_arr = array_diff($id_in, $id_out);
		return $result_arr;
	}

	/**
	 * checking what happens with ticketid
	 * @param array $absentIds
	 * @return mixed
	 */
	private function checkAbsentIds(array $absentIds)
	{
		$result_arr = $temp_arr = [];
		foreach ($absentIds as $absentId) {
			array_push($temp_arr, $this->whmcs->getTiket($absentId));
			$result_arr[] = [
				'absentId' => $absentId,
				'service_id' => $this->service_id,
				'status' => strtolower($temp_arr[0]['result']),
			];
		}
		# push them into model and remove if error or move
		$t = new Ticket;
		return $t->moveRemoveTicketsDecision($result_arr);
	}

	/**
	 * getting reply count and repliers
	 *
	 *Array
	 * (
	 * [adminNiks] => Array
	 * (
	 * [Roman Korolov] => 3
	 * [Yuri Kurulyuk] => 1
	 * )
	 * [dates] => Array
	 * (
	 * [Roman Korolov] => 2018-07-03 23:22:47
	 * [Yuri Kurulyuk] => 2018-07-05 10:18:12
	 * )
	 * [lastreplyIsAdmin] => 0
	 * [reply_count] => 10
	 * )
	 * @param int $ticketID
	 * @return array
	 */
	private function getRepliesCount(int $ticketID) :array
	{
		$adminName = '';
//		$carbon = new Carbon();
		$last_is_admin = 0;
		$all_replies = $replies = $dates = [];
		$ticket_full_data = $this->getTicketFromService($ticketID);
		foreach ($ticket_full_data as $reply) {
			if ($reply['name'] === ''
				AND $reply['admin'] !== ''
//				AND $carbon::createFromTimeStamp(strtotime($reply['date']))->isCurrentMonth()
			){
				$adminName = &$reply['admin'];
				$dates[$reply['admin']] = $reply['date'];
				array_push($all_replies, $adminName);
				$last_is_admin = 1;
			} else {
				$last_is_admin = 0;
			}
		}
		$replies['adminNiks'] = array_count_values($all_replies);
		$replies['dates'] = $dates;
		$replies['lastReplyAdmin'] = $adminName;
		$replies['last_is_admin'] = $last_is_admin;
		return $replies;
	}

	/**
	 * storing admin_niks and getting back own id's
	 *
	 * IReturning array where [admin_nik_id] = count his replies on this ticket and client
	 * @param int $service_id
	 * @param array $adminNiks and replies
	 * @param $lastReplyAdmin admins's id
	 * @param array $dates
	 * @return array
	 */
	private function storeSysadminNiks(int $service_id, array $adminNiks, array $dates, $lastReplyAdmin) :array
	{
		$adminNikIdsWithReplies = $dateOfLastReply = $repliesCounts = [];
		$lastReplyAdminNikId = 0;
		foreach ($adminNiks as $adminNik => $replies) {
			$sysadminNik = AdminNik::firstOrCreate(['admin_nik' => $adminNik, 'service_id' => $service_id]);
			$adminNikId = $sysadminNik->id;
			if ($lastReplyAdmin == $adminNik) $lastReplyAdminNikId = $adminNikId;
			if (array_key_exists($adminNik, $dates)) $dateOfLastReply[$adminNikId] = $dates[$adminNik];
			$repliesCounts[$adminNikId] = $replies;
			$adminNikIdsWithReplies['replies_count'] = $repliesCounts;//array($adminNikId => $replies);
			$adminNikIdsWithReplies['last_reply'] = $dateOfLastReply;
		}
		$adminNikIdsWithReplies['last_replier_nik_id'] = $lastReplyAdminNikId;
		return $adminNikIdsWithReplies;
	}

	/**
	 * Storing activity in specific ticket and client with lastreply dateTime
	 *
	 * replies are count of admins reply
	 * @param array $adminNikIdsWithReplies
	 * @param int $ticket_id
	 */
	private function storeAdminActivities(array $adminNikIdsWithReplies, int $ticket_id): void
	{
		$err_msg = 'Admin nik id %d not stored in ticket id %d on service %s';
		foreach ($adminNikIdsWithReplies['replies_count'] as $adminNikId => $reply) {
			$sysadminActivity = SysadminActivity::firstOrCreate(array(
				'ticket_id' => $ticket_id,
				'sysadmin_niks_id' => $adminNikId,
			), array(
				'replies' => $reply,
				'lastreply' => $adminNikIdsWithReplies['last_reply'][$adminNikId],
			));
			$activity_id = $sysadminActivity->sysadmin_niks_id ?? $sysadminActivity->id;
			if (!$activity_id)
				Log::error(sprintf($err_msg,$adminNikId,$ticket_id, $this->service));
		}
	}

	/**
	 * get last replier nik id
	 *
	 * @param int $ticketID
	 * @return int
	 */
	public function getLastReplierNickId(int $ticketID) :int
	{
		$last_replier_nik_id = 0;
# trying to get last reply admin nik id
		$ticket_m = Ticket::where([
			['ticketid', '=', $ticketID],
			['service_id', '=', $this->service_id]
		])->first();
		if ($ticket_m) $last_replier_nik_id = $ticket_m->last_replier_nik_id;
		return $last_replier_nik_id;
	}

	private function getUserAssignId(int $ticket_id)
	{
		return Ticket::find($ticket_id)->user_assign_id;
	}

	private function isLastReplierActive(int $last_replier_nik_id){
		# if user active assign to him his ticket
		$adminNik_m = new AdminNik();
		return $user_active_collection = $adminNik_m->isUserNikIdActive($last_replier_nik_id);

	}

	private function setUserAssignId(int $ticket_id, int $user_id)
	{
		Ticket::find($ticket_id)->update(['user_assign_id'=>$user_id]);
	}
}