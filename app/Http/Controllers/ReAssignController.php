<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReAssignController extends Controller
{
    public function __invoke(Request $request, int $ticket_id)
    {
	    $request->validate([
		    'user_id' => 'required|numeric',
	    ]);
	    $err_msg = 'Ticket not found or %s';
	    $msg = 'User %d assign to ticket';
	    try {
		    Ticket::find($ticket_id)->update(['user_assign_id' => $request->user_id]);
		    return redirect()->back()->with(sprintf($msg, $request->user_id));
	    } catch (ModelNotFoundException $me) {
		    Log::error(sprintf($err_msg, $me->getMessage()));
		    return redirect()->back()->withErrors(['msg' => sprintf($err_msg, $me->getMessage())]);
	    }
    }
}
