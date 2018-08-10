<?php

namespace App\Http\Controllers\Boss;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Ticket,  Service, Deadline};
use Carbon\Carbon;
use App\Http\Controllers\Boss\DeadlineController as DLC;
//use Illuminate\Support\Facades\Session;


class IndexController extends Controller
{

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
		$maxDeadline = $dlc->explodeTime($deadline_m->getMaxDeadline());
		$serviceTicketCounts = [];
		if (count(Service::all())) {
			foreach (Service::all() as $service) {
				$serviceTicketCounts[$service->name] = [
					'summary_tickets' => $ticket_m->getSummaryCountTickets($service->id),
					'open_tickets' => $ticket_m->getCountOpenTickets($service->id),
					'yesterday' => $ticket_m->getAllTicketsFromYesterday($service->id)->count(),
					'start_month' => $ticket_m->getAllTicketsFromMonth($service->id)->count(),
				];
			}
			return view('boss.pages.home')->with([
					'ticketCounts' => $serviceTicketCounts,
					'openTickets' => $ticket_m->getOpenTickets(),
					'newTickets' => $ticket_m->getNewTickets(),
					'Carbon'=>new Carbon(),
					'deadlineList'=>$dlc->getSummaryArrMinutes(),
					'maxDeadline'=>$maxDeadline,
				]
			);
		} else {
			echo '<h2 style="text-align: center">Run <code>daemon.php</code> First!</h2>';
			return Null;
		}
	}


}
