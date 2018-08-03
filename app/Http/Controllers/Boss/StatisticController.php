<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SysadminActivity;
use Carbon\Carbon;

class StatisticController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:boss');
	}

	public function index()
	{
		$sa_m = new SysadminActivity();
		return view('boss.pages.statistics')->with([
			'statistic4AllAdmins'=>$sa_m->getStatistic4AllAdmins(),
			'this_month'=>Carbon::now()->startOfMonth(),
			'curr_month'=>0,
		]);
	}

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
		$sact_m = new SysadminActivity();
		$statistics = [];
		foreach (range(0,$month_count) as $month){
			array_push($statistics,$sact_m->getAllStatistic4AllAdmins($month));
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
