<?php

namespace App\Http\Controllers;

use App\Models\{
	AdminNik, Service, Sysadmin, Ticket
};
use Illuminate\Http\Request;
use Carbon\Carbon;

class IndexController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$ticket_m = new Ticket();
		$sysadmin_m = new Sysadmin();
		foreach (Service::all() as $service) {
			$serviceTicketCounts[$service->name] = array(
				'summary_tickets' => $ticket_m->getSummaryCountTickets($service->id),
				'open_tickets' => $ticket_m->getCountOpenTickets($service->id),
				'yesterday' => $ticket_m::where([['created_at', '>=', Carbon::now()->yesterday()], ['service_id', $service->id]])->get()->count(),
				'start_month' => $ticket_m::where([['created_at', '>=', Carbon::now()->startOfMonth()], ['service_id', $service->id]])->get()->count(),
				'tickets' => Ticket::with(['getService', 'getPriority', 'getStatus'])->where('is_closed', 0),
			);
		}
		return view('admins.pages.index')->with([
				'adminNiks' => AdminNik::with(['getService', 'getAdmin'])->get(),

				'ticketCounts' => $serviceTicketCounts,

				'openTickets' => Ticket::with(['getStatus', 'getDeadline', 'getPriority', 'getService', 'getAdmin'])->
				where('is_closed', 0)->
				orderBy('last_is_admin')->
				orderBy('lastreply')->
				get(),
				'newTickets'=>Ticket::with(['getStatus', 'getDeadline', 'getPriority', 'getService', 'getAdmin'])->
				where([['is_closed','=',0],['is_new','=',1]])->
				orderBy('last_is_admin')->
				orderBy('lastreply')->
				get(),
				'countOfClosedAndReplies' => $sysadmin_m->getCountOfClosedTicketsAndReplies(),
			]
		);
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
