<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Ticket,User, SysadminActivity, Deadline};
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Controllers\Boss\DeadlineController as DLC;

class HomeController extends Controller
{
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
    public function __invoke()
    {
	    User::where('id',Auth::id())->update(['active'=>1]);
	    $ticket_m = new Ticket();
	    $sa_m = new SysadminActivity();
	    $user_id = Auth::id();
	    $statistic = $sa_m->getStatistic4Admin($user_id);
	    $deadline_m = new Deadline();
	    $dlc = new DLC();
	    $maxDeadline = $dlc->explodeTime($deadline_m->getMaxDeadline());
	    if (count((array) $statistic)) {
		    $tickets_count = $statistic->tickets_count ?? 0;
		    $replies_count = $statistic->replies_count ?? 0;
		    $using_time = sprintf('%02d:%02d', floor($statistic->time_sum / 60), $statistic->time_sum % 60);
		    $compl = $statistic->compl;
	    }
	    else{
	    	$tickets_count = $replies_count = $using_time = $compl = 0;
	    }
        return view('admins.pages.home')->with([
	        'newTickets' => $ticket_m->getNewTickets4Admin(),
	        'showMyTickets'=>$ticket_m->getOpenTickets4CurrAdmin($user_id),
	        'showMyStatistic'=>compact("tickets_count","replies_count","using_time","compl"),
	        'Carbon'=>new Carbon(),
	        'user_id'=>$user_id,
	        'statistic4AllAdmins'=>$sa_m->getStatistic4AllAdmins(),
	        'openTickets' => $ticket_m->getOpenTickets(),
	        'fromStartOfMonth'=>Carbon::now()->startOfMonth(),
	        'deadlineList'=>$dlc->getSummaryArrMinutes(),
	        'maxDeadline'=>$maxDeadline,
        ]);
    }
}
