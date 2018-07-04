<?php

namespace App\Http\Controllers;

use App\Models\AdminNik;
use App\Models\Service;
use App\Models\Sysadmin;
use App\Models\Ticket;
use Illuminate\Http\Request;

class IndexController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$serviceOpenTicketsCount = array();
		$ticket_m = new Ticket() ;
		foreach (Service::all() as $service){
			echo "$service->id \n";
			$serviceOpenTicketsCount[$service->name]['open_tickets']=$ticket_m->getCountOpenTickets($service->id);
		}
		print_r($serviceOpenTicketsCount);
		return view('admin.pages.index')->with([
			'adminNiks' => AdminNik::with(['getService','getRealAdmin'])->get(),
				'adminNiksVV'=>Sysadmin::with(['getServices','getNiks'])->get(),

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
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
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
