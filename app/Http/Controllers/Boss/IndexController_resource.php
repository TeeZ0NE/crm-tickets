<?php

namespace App\Http\Controllers\Boss;

use App\Models\{
	AdminNik, Service, Sysadmin, Ticket
};
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IndexControllerResource extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$ticket_m = new Ticket();
		$sysadmin_m = new Sysadmin();
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
			return view('admins.pages.index')->with([
					'ticketCounts' => $serviceTicketCounts,
					'openTickets' => $ticket_m->getOpenTickets(),
					'newTickets' => $ticket_m->getNewTickets(),
					'countTicketsAndReplies' => $sysadmin_m->getCountTicketsAndReplies(),
					'showMyTickets' => $ticket_m->getOpenTickets4CurrAdmin(2),
				]
			);
		} else {
			echo '<h2 style="text-align: center">Run <code>daemon.php</code> First!</h2>';
			return Null;
		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}


}
