<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Ticket,User};
use App\Http\Controllers\Controller;

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
        return view('admins.pages.home')->with([
	        'newTickets' => $ticket_m->getNewTickets(),
	        'showMyTickets'=>$ticket_m->getOpenTickets4CurrAdmin(Auth::id()),
        ]);
    }
}
