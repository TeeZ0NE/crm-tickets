<?php

namespace App\Http\Controllers\Boss;

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
    public function update(Request $request, $id){
    	$has_deadline = isset($request->has_deadline)?1:0;
    	$msg = 'Ticket ID %d update has_deadline to %d with result %d';
    	$error_msg = 'Ticket ID %d was not update has_deadline';
    	try{
    		$res = Ticket::findOrFail($id)->update(['has_deadline'=>$has_deadline]);
    		Log::info(sprintf($msg,$id,$has_deadline,$res));
    		return redirect()->back()->with('msg',sprintf($msg,$id,$has_deadline,$res));
	    }
	    catch (MNF $mnf){
    		Log::error(sprintf($error_msg,$id));
	    }
    	return redirect()->back()->withErrors(['msg'=>sprintf($error_msg,$id)]);
    }

	public function destroy($id)
	{
		$ticket_m = new Ticket();
		$destroyed = $ticket_m->ticketDestroy($id);
		if ($destroyed) {
			Log::info(sprintf('User %s destroy ticket id %d',Auth::user(),$id));
			return redirect()->back()->with('msg', sprintf('Ticket with id %d deleted',$id));
		}
		return redirect()->back()->withErrors(['msg',sprintf('Ticket id %d doesn\'t delete',$id)]);
    }

	public function index()
	{
		$ticket_m = new Ticket();
		return view('boss.pages.tickets')->with([
			'tickets'=>$ticket_m->getAllTickets(),
		]);
    }
}
