<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Libs\Statistic;

class StatisticController extends Controller
{
	use Statistic;
	public function __construct()
	{
		$this->middleware('auth:boss');
	}

	public function index()
	{
		return view('boss.pages.statistics')->with([
			'curr_month'=>0,
			'month_count'=>1,
			'statistic4AllAdmins'=>$this->recurseStatistic4AllAdmin(),
			'this_month'=>Carbon::now()->startOfMonth(),
		]);
	}

	/**
	 * Get statistic 4 all services with sub-month
	 * @param Request $request
	 * @return $this
	 */
	public function getStatisticsSubMonth(Request $request)
	{
		request()->validate([
			'month_count'=>'numeric',
		]);
		$month_count = $request->month_count;
		$currMonth = isset($request->curr_month)?1:0;
		if(!$month_count){
			return $this->index();
		}
		$statistics = [];
		foreach (range(0,$month_count) as $month){
			array_push($statistics,$this->recurseStatistic4AllAdmin($month));
		}
		return view('boss.pages.statisticsByMonth')->with([
		'statistic4AllAdminsByMonths'=>array_reverse($statistics),
		'Carbon'=>new Carbon(),
			'month_count'=>$month_count,
			'iterator'=>$month_count,
			'curr_month'=>$currMonth,
	]);
	}
}
