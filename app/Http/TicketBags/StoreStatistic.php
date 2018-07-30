<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 26.07.18
 * Time: 10:15
 */

namespace App\Http\TicketBags;

use App\Models\{
	Service, SysadminActivity, Ticket, AdminNik
};
use Carbon\Carbon;

trait StoreStatistic
{

	private $get_stat_arr;
	private $service;

	public function __construct(&$service, &$get_stat_arr)
	{
		$this->get_stat_arr = $get_stat_arr;
		$this->service = $service;
	}

	public function store(): void
	{
		//todo:: debug
		print_r($this->get_stat_arr);
		$service_m = new Service();
		$ticket_m = new Ticket();
		$adminNik_m = new AdminNik();
		/*
		 * consist from [ticket_id] => [lastreply => nik_id]
		 */
		$all_replies = [];
		$service_id = $service_m->getServiceId($this->service);
		foreach ($this->recurseGetArr() as $val) {

			if (array_key_exists('ticketid', $val) && (int)$val['ticketid'] > 0) {
				$lastreply = Carbon::createFromTimeString($val['lastreply']);
				$nik_id = $adminNik_m->getAdminNikId($val['admin'], $service_id);
				$ticket_id = $ticket_m->getTicketId((int)$val['ticketid'], $service_id, [
					'last_replier_nik_id' => $nik_id,
					'lastreply' => $lastreply,
					'subject' => $val['subject'],
					'compl' => $service_m->getCompl($service_id),
				]);
				echo sprintf("user id - %d ticket id - %d time uses - %d \n", $nik_id, $ticket_id, (int)$val['time_uses']);
				if (isset($all_replies[$ticket_id][$nik_id]['time_uses'])){
					$all_replies[$ticket_id][$nik_id]['time_uses']+=(int)$val['time_uses'];
				}
				else{
					$all_replies[$ticket_id][$nik_id]['time_uses'] = 0;
				}
					$all_replies[$ticket_id]["$lastreply"] = $nik_id;

			} # ticketid not found
			else continue;
		}
		//todo: uncomment me
//		$this->storeAdminActivities($all_replies);
		print_r($all_replies);
	}

	private function storeAdminActivities(array $all_replies)
	{
		print_r($all_replies);
		foreach ($all_replies as $ticket_id => $lastreplies) {
			#[sysadmin_id]=>reply_count
			$replies = array_count_values($lastreplies);
			foreach ($lastreplies as $lastreply => $sysadmin_id) {
				$last_replier_nik_id = $sysadmin_id;
				$last_reply = $lastreply;
				/*
				SysadminActivity::updateOrCreate([
					'sysadmin_niks_id' => $sysadmin_id,
					'ticket_id' => $ticket_id,],
					['replies' => $replies[$sysadmin_id],
						'lastreply' => $lastreply,
					]);*/
			}
			Ticket::find($ticket_id)->update(['last_replier_nik_id'=>$last_replier_nik_id, 'lastreply'=>$last_reply]);
		}
	}

	private function recurseGetArr()
	{
		foreach ($this->get_stat_arr as $arr) {
			yield $arr;
		}
	}
}