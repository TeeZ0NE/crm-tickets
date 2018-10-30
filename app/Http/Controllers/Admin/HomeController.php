<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\{Ticket,User, Deadline};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Controllers\Boss\DeadlineController as DLC;
use App\Http\Libs\Statistic;

class HomeController extends Controller
{
	use Statistic;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $user_id = Auth::id();
	    $user_m = new User();
	    User::where('id',$user_id)->update(['active'=>1]);
	    $ticket_m = new Ticket();
	    $deadline_m = new Deadline();
	    $dlc = new DLC();
	    $maxDeadline = $dlc->explodeTime($deadline_m->getMaxDeadline());
        return view('admins.pages.home')->with([
	        'newTickets' => $ticket_m->getNewTickets4Admin(),
	        'showMyTickets'=>$ticket_m->getOpenTickets4CurrAdmin($user_id),
	        'statistic4Admin'=>$this->recurseStatistic4Admin($user_id),
	        'Carbon'=>new Carbon(),
	        'user_id'=>$user_id,
	        'statistic4AllAdmins'=>$this->recurseStatistic4AllAdmin(),
	        'openTickets' => $ticket_m->getOpenTickets(),
	        'this_month'=>Carbon::now()->startOfMonth(),
	        'deadlineList'=>$dlc->getSummaryArrMinutes(),
	        'maxDeadline'=>$maxDeadline,
	        'serviceCountOpenTickets'=>$this->getStatisticFromServices(false),
	        'active_admins'=>$user_m->getActiveAdmins(),
        ]);
    }

	/**
	 * Showing statistics from last month to current
	 * @return $this
	 */
    public function statistic(){
    	$statistics=[];
	    foreach (range(0,1) as $month){
		    array_push($statistics,$this->recurseStatistic4AllAdmin($month));
	    }
	    return view('admins.pages.statisticsByMonth')->with([
		    'statistic4AllAdminsByMonths'=>array_reverse($statistics),
		    'Carbon'=>new Carbon(),
		    'iterator'=>1,
	    ]);
    }
}
