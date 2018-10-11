<?php

namespace App\Http\Controllers\Boss;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as MNF;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:boss');
	}

	/**
	 * Change deadline
	 * @param Request $request
	 * @param $id
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, $id)
	{
		$has_deadline = isset($request->has_deadline) ? 1 : 0;
		$msg = 'Ticket ID %d update has_deadline to %d with result %d';
		$error_msg = 'Ticket ID %d was not update has_deadline';
		try {
			$res = Ticket::findOrFail($id)->update(['has_deadline' => $has_deadline]);
			Log::info(sprintf($msg, $id, $has_deadline, $res));
			return redirect()->back()->with('msg', sprintf($msg, $id, $has_deadline, $res));
		} catch (MNF $mnf) {
			Log::error(sprintf($error_msg, $id));
		}
		return redirect()->back()->withErrors(['msg' => sprintf($error_msg, $id)]);
	}

	public function destroy($id)
	{
		$ticket_m = new Ticket();
		$destroyed = $ticket_m->ticketDestroy($id);
		if ($destroyed) {
			Log::info(sprintf('User %s destroy ticket id %d', Auth::user(), $id));
			return redirect()->back()->with('msg', sprintf('Ticket with id %d deleted', $id));
		}
		return redirect()->back()->withErrors(['msg', sprintf('Ticket id %d doesn\'t delete', $id)]);
		/*<*/
	}

	public function index()
	{
		$ticket_m = new Ticket();
		$tickets = $ticket_m->getAllTickets();
		return view('boss.pages.tickets')->with([
			'links' => $tickets->links(),
			'services' => $this->getAllServices(),
			'tickets' => $tickets,
			'total' => $tickets->total(),
		]);
	}

	public function search(Request $request)
	{
		$service_id = (int)$request->service_id;
		$ticketid = $request->ticketid;
		$ticket_m = new Ticket();
		if ($ticketid) {
			$request->validate([
				'ticketid'=>'numeric'
			]);
			$tickets = $ticket_m->getServiceTicket($service_id,$ticketid);
		} else {
			$tickets = $ticket_m->getServiceTickets($service_id);
		}
		return view('boss.pages.tickets')->with([
			'links' => $tickets->appends(['service_id'=>$service_id,'ticketid'=>$ticketid])->links(),
			'service_id'=>$service_id,
			'services' => $this->getAllServices(),
			'ticketid'=>$ticketid,
			'tickets' => $tickets,
			'total' => $tickets->total(),
		]);
	}

	private function getAllServices()
	{
		$service_m = new Service();
		return $service_m->getAllServices();
	}

	public function show($id)
	{
		$ticket_m = new Ticket();
		$activities = $ticket_m->getTicketAllActivities($id);
		$activity_first = $activities->first();
		if (empty($activity_first)) return redirect()->back()->withErrors(['msg'=>sprintf('Activity on this ticket (id %d) not found',$id)]);
		return view('boss.pages.ticketActivities')->with([
			'activities' => $activities,
			'service' => $activity_first->name,
			'subject' => $activity_first->subject,
			'ticketid' => $activity_first->ticketid,
			'href_link' => $activity_first->href_link,
		]);
	}
}
