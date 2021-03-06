<?php

namespace App\Http\Controllers\Boss;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
	Ticket, Service, Deadline, User
};
use Carbon\Carbon;
use App\Http\Controllers\Boss\DeadlineController as DLC;
//use Illuminate\Support\Facades\Session;
use App\Http\Libs\Statistic;


class IndexController extends Controller
{
	use Statistic;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:boss');//auth
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke()
	{
		$ticket_m = new Ticket();
		$deadline_m = new Deadline();
		$dlc = new DLC();
		$user_m = new User();
		$maxDeadline = $dlc->explodeTime($deadline_m->getMaxDeadline());
		/*$serviceTicketCounts = [];*/
		$service_all = Service::all();
		if (count($service_all)) {
			/*foreach ($service_all as $service) {
				$serviceTicketCounts[$service->name] = [
					'summary_tickets' => $ticket_m->getSummaryCountTickets($service->id),
					'open_tickets' => $ticket_m->getCountOpenTickets($service->id),
					'yesterday' => $ticket_m->getAllTicketsFromYesterday($service->id)->count(),
					'start_month' => $ticket_m->getAllTicketsFromMonth($service->id)->count(),
					'is_available'=>$service->is_available
				];
			}*/
			return view('boss.pages.home')->with([
					'ticketCounts' => $this->getStatisticFromServices(),
					'openTickets' => $ticket_m->getOpenTickets(),
					'newTickets' => $ticket_m->getNewTickets(),
					'Carbon'=>new Carbon(),
					'deadlineList'=>$dlc->getSummaryArrMinutes(),
					'maxDeadline'=>$maxDeadline,
					'active_admins'=>$user_m->getActiveAdmins(),
				]
			);
		} else {
			echo '<h2 style="text-align: center">Run <code>daemon.php</code> First!</h2>';
			return Null;
		}
	}


}
