<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException as MNF;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:boss');
    }

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
}
