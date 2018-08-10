<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 09.08.18
 * Time: 18:15
 */

namespace App\Http\Libs;
use App\Models\{SysadminActivity,Service, Ticket};

trait Statistic
{
	/**
	 * @return \Generator
	 */
	private function recurseServices(){
		$service_m = new Service();
		foreach ($service_m->getServicesIds() as $service_id){
			yield $service_id;
		}
	}

	/**
	 * get recursively statistic 4 all services
	 * @param int $sub_month
	 * @return array
	 */
	public function recurseStatistic4AllAdmin(int $sub_month=0){
		$sact_m = new SysadminActivity();
		$serv_m = new Service();
		$arr = [];
		foreach ($this->recurseServices() as $service_id){
			if($this->isEmptyService($service_id)) continue;
			$arr[$serv_m->getServiceName($service_id)]=$sact_m->getAllStatistic4AllAdmins($service_id,$sub_month);
		}
		return $arr;
	}
	/**
	 * get recursively statistic 4 all services
	 * @param int $user_id
	 * @param int $sub_month
	 * @return array
	 */
	public function recurseStatistic4Admin(int $user_id,int $sub_month=0){
		$sact_m = new SysadminActivity();
		$serv_m = new Service();
		$arr = [];
		foreach ($this->recurseServices() as $service_id){
			if($this->isEmptyService($service_id)) continue;
			$arr[$serv_m->getServiceName($service_id)]=$sact_m->getStatistic4Admin($service_id,$user_id,$sub_month);
		}
		return $arr;
	}
	/**
	 * Does service has any (one) ticket(-s)
	 * @param int $service_id
	 * @return bool
	 */
	private function isEmptyService(int $service_id)
	{
		$ticket_m = new Ticket();
		return empty($ticket_m->select('id')->where('service_id',$service_id)->first());
	}
}