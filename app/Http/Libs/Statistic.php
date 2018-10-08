<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 09.08.18
 * Time: 18:15
 */

namespace App\Http\Libs;
use App\Models\{SysadminActivity,Service, Ticket};
use Carbon\CarbonInterval;

trait Statistic
{
	private $service;
	private $service_id;
	private $interval;
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

	private function getStatisticFromServices(bool $all=true)
	{
		$serviceTicketCounts = [];
		$ticket_m = new Ticket();
		$service_all = Service::all();
		$summary_tickets=$open_tickets=$yesterday=$start_month=$is_available = 0;
		if (count($service_all)) {
			foreach ($service_all as $service) {
				if($all){
					$summary_tickets = $ticket_m->getSummaryCountTickets($service->id);
					$yesterday = $ticket_m->getAllTicketsFromYesterday($service->id)->count();
					$start_month = $ticket_m->getAllTicketsFromMonth($service->id)->count();
					$is_available = $service->is_available;
				}
				$open_tickets = $ticket_m->getCountOpenTickets($service->id);
				preg_match("/([-\w.:\/]+)/", $service->href_link, $link_arr);
				$serviceTicketCounts[$service->name] = [
					'summary_tickets' =>$summary_tickets,
					'open_tickets' => $open_tickets,
					'yesterday' =>$yesterday,
					'start_month' =>$start_month,
					'is_available' => $is_available,
					'home_link'=>$link_arr[1]??'#',
				];
			}
		}
		return $serviceTicketCounts;
	}
	/**
	 * Forming statistic 4 service
	 *
	 * Applicable set of one an interval String:
	 * 'today', 'yesterday','start_of_month', 'month'
	 *
	 * @param int $service_id
	 * @param string $interval
	 * @return \Illuminate\Support\Collection|object|string
	 */
	private function formStatistic(int $service_id, string $interval){
		$service_m = new Service();
		$notthig_found_msg = 'Ничего не найдено';
		switch ($interval){
			case 'yesterday': $statistics_db = $service_m->getStatisticYesterday($service_id); break;
			case 'today': $statistics_db=$service_m->getStatisticToday($service_id);break;
			case 'start_of_month': $statistics_db=$service_m->getStatisticStartOfMonth($service_id);break;
			case 'month':$statistics_db=$service_m->getStatisticPrevMonth($service_id);break;
			default: $statistics_db=collect();
		}
		$statistics = ($statistics_db->isEmpty())?$notthig_found_msg:$statistics_db;
		return $statistics;
	}

	/**
	 * Forming summary statistic
	 *
	 * @param int $service_id
	 * @param string $interval
	 * @return \Illuminate\Database\Eloquent\Model|null|object|string|static
	 */
	private function formSummaryStatistic(int $service_id, string $interval){
		$service_m = new Service();
		switch ($interval){
			case 'yesterday': $statistics_db = $service_m->getCountTicketsAndSumTimeYesterday($service_id); break;
			case 'today': $statistics_db=$service_m->getCountTicketsAndSumTimetoday($service_id);break;
			case 'start_of_month':$statistics_db=$service_m->getCountTicketsAndSumTimeStartOfMonth($service_id);break;
			case 'month':$statistics_db=$service_m->getCountTicketsAndSumTimePrevMonth($service_id);break;
			default: $statistics_db=null;
		}
		$statistics = $statistics_db?:$statistics_db;
		return $statistics;
	}

	/**
	 * Compose summary text 4 humans
	 *
	 * @param object|string $summary
	 * @return string
	 */
	private function total4humans($summary)
	{
		return isset($summary->sum_time)
			?CarbonInterval::fromString($summary->sum_time.'m')->cascade()->forHumans()
			:'0';
	}

	private function getService_id(string $service)
	{
		$service_m = new Service();
		return $service_m->getServiceId($service);
	}

	private function getServiceName(int $service_id)
	{
		$service_m = new Service();
		return $service_m->getServiceName($service_id);
	}

	/**
	 * @param int|string $service
	 * @param string $interval
	 */
	private function setVariable($service, $interval='today')
	{
		$this->service_id = is_numeric($service)?$service:$this->getService_id($service);
		$this->service = $this->getServiceName($this->service_id);
		$this->interval = $interval;
	}
}