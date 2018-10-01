<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Service};
use App\Http\Libs\Statistic;
class ServicesStatisticController extends Controller
{
	use Statistic;
	public function __construct()
	{
		$this->middleware('auth:boss');//auth
	}
	public function index()
	{
		$service_m = new Service();
		return view('boss.pages.serviceStatistic')->with([
			'services'=> $service_m->getAllServices(),
			'interval'=>'today'
		]);
    }

	/**
	 * Generate statistic
	 *
	 * @param Request $request
	 * @return $this View
	 */
	public function getStatistic(Request $request)
	{
		$request->validate([
			'service_id'=>'required|numeric'
		]);
		$service_id = (int)$request->service_id;
		$interval = $request->interval??'today';
		$this->setVariable($service_id,$interval);
		$service_m = new Service();
		$summary = $this->formSummaryStatistic($this->service_id,$this->interval);
		return view('boss.pages.serviceStatistic')->with([
			'interval'=>$this->interval,
			'service_id'=>$this->service_id,
			'services'=> $service_m->getAllServices(),
			'statistics'=> $this->formStatistic($this->service_id,$this->interval),
			'summary'=>$summary,
			'total4humans'=>$this->total4humans($summary),
		]);
    }


}
