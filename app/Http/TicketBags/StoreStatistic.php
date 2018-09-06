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

//	public function __construct(&$service, &$get_stat_arr)
		public function __construct()
	{
//		$this->get_stat_arr = $get_stat_arr;
//		$this->service = $service;
	}

	public function store(): void
	{
		//todo:: debug
//		print_r($this->get_stat_arr);
		$service_m = new Service();
		$ticket_m = new Ticket();
		$adminNik_m = new AdminNik();
		# service must be created before else it occurs error
		$service_id = $service_m->getServiceId($this->service);
		foreach ($this->recurseGetArr() as $val) {
			if (array_key_exists('ticketid', $val) && (int)$val['ticketid'] > 0) {
				$lastreply = Carbon::createFromTimeString($val['lastreply']);
				$nik_id = $adminNik_m->getAdminNikId($val['admin'], $service_id);
				$ticket_id = $ticket_m->getTicketId((int)$val['ticketid'], $service_id, [
					'last_replier_nik_id' => $nik_id,
					'lastreply' => $lastreply,
					'subject' => $val['subject'],
				]);
						$this->storeAdminActivities($ticket_id, $nik_id, $lastreply, (int)$val['time_uses']);
			} # ticketid not found
			else continue;
		}
	}

	private function storeAdminActivities(int $ticket_id, int $nik_id,$lastreply ,int $time_uses=0 )
	{
		$sysadmnin_act_m = new SysadminActivity();
		$sysadmnin_act_m::firstOrCreate([
			'sysadmin_niks_id'=> $nik_id,
			'ticket_id'=>$ticket_id,
			'lastreply' =>$lastreply,
		],[
			'time_uses'=>$time_uses
		]);
		//todo update ticket with lastreply
			Ticket::find($ticket_id)->update(['last_replier_nik_id'=>$nik_id, 'lastreply'=>$lastreply]);

	}

	private function recurseGetArr()
	{
		foreach ($this->get_stat_arr as $arr) {
			yield $arr;
		}
	}
}