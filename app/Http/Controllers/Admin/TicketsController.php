<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\{Ticket};

class TicketsController extends Controller
{
	/**
	 * User assign ticket to him self
	 * @param $user_id
	 * @param $ticket_id
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function assignTicket($user_id,$ticket_id)
	{
		$ticket_m = new Ticket();
		$res = $ticket_m->find($ticket_id)->update(['user_assign_id'=>$user_id]);
		if($res) return redirect(route('home'))->with('msg','Добавлено');
		return redirect()->back()->withErrors(['msg'=>'Невозможно добавить']);
	}
}
