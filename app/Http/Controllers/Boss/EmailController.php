<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
	Email
};

class EmailController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$email_m = new Email();
		return view('boss.pages.emailsShowAll')->with([
			'emails' => $email_m->getAllEmails(),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('boss.pages.emailCreate');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$request->validate([
			'email'=>'required|unique:emails|max:86'
		]);
		$email_m = new Email();
		$email = $request->email;
		$stored = $email_m->createNewRecord($email);
		if ($stored) return redirect(route('emails.index'))->with('msg',sprintf('E-mail %s stored in DB with id %d',$email,$stored));
		return redirect(route('emails.index'))->withErrors(['msg'=>sprintf('%s not stored',$email)]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return redirect()->back();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return redirect()->back();
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
		$request->validate([
			'email'=>'required|max:86'
		]);
		$email_m = new Email();
		$email = $request->email;
		$is_main = $request->is_main?1:0;;
		$updated = $email_m->updateEmail($id,$email,$is_main);
		if($updated)
		return redirect(route('emails.index'))->with('msg',sprintf('e-mail with id %d updated to %s',$id,$email));
		return redirect(route('emails.index'))->withErrors(['msg'=>sprintf('E-mail with id %d doesn\'t updated',$id)]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$email_m = new Email();
		$destroyed = $email_m->destroyEmail($id);
		if($destroyed) return redirect(route('emails.index'))->with('msg',sprintf('E-mail with id %d destroyed', $id));
		return redirect(route('emails.index'))->withErrors(['msg'=>sprintf('E-mail with id %d doesn\'t destroyed',$id)]);
	}
}
