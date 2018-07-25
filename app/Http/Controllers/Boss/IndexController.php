<?php

namespace App\Http\Controllers\Boss;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Ticket, User, Service};
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
	public function index()
	{
		$ticket_m = new Ticket();
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
				]
			);
		} else {
			echo '<h2 style="text-align: center">Run <code>daemon.php</code> First!</h2>';
			return Null;
		}
	}
}
