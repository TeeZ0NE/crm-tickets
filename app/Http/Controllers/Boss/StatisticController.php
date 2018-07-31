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

	public function __invoke()
	{
		$sa_m = new SysadminActivity();
		return view('boss.pages.statistics')->with([
			'statistic4AllAdmins'=>$sa_m->getStatistic4AllAdmins(),
			'fromStartOfMonth'=>Carbon::now()->startOfMonth(),
		]);
	}
}
