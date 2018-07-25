<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Models\{AdminNik, User};
use Illuminate\Support\Facades\{Redirect, Hash, Mail};
use App\Http\Controllers\Controller;
use App\Mail\UserAccess;

class RealAdminController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:boss');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user_m = new User();
        return view('boss.pages.adminsShowAll')->with([
        	'admins'=>$user_m->getAdmins(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('boss.pages.adminsCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    request()->validate([
		    'name'=>'required|unique:users|max:120',
		    'email'=>'required|unique:users',
		    'password'=>'required|min:6',
		    'confirm'=>'required|same:password',
	    ]);
	    $name = $request->name;
	    $email = $request->email;
	    $password = $request->password;


	    $user_m = new User();
	    $user_m->name = $name;
	    $user_m->password = Hash::make($request->password);
	    $user_m->email = $email;
	    $user_m->save();
	    if($user_m->id) {
		    Mail::to($request->email)->send(new UserAccess($name, $email, $password));
		    return redirect(route('boss.home'))->with('msg', sprintf('%s добавлено в БД! Отправлено письмо с данными', $name));
	    }
	    else return redirect(route('boss.home'))->withErrors(['msg'=>'Данные не были сохранены']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		request()->validate([
			'name'=>'required|unique:users',
		]);
    	User::find($id)->update(['name'=>$request->name]);
    	return redirect()->back()->with('msg', sprintf('Имя администратора %d обновлено',$id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$adminNiks_m = new AdminNik();
    	$user_m = new User();
    	$adminNiks_m->where('user_id',$id)->update(['user_id'=>0]);
    	$res = $user_m::find($id)->delete();
    	if($res) return redirect(route('boss.home'))->with('msg',sprintf('Администратор с ID %d удален с результатом %s',$id,$res));
    	else return redirect(route('boss.home'))->withErrors(['msg'=>sprintf('Администратор с ID %d не удален',$id)]);
    }

	/**
	 * showing a list with real admins's names and a list with own niks
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function nicks()
	{
		$user_m = new User();
		$adminNiks_m = new AdminNik();
		return view('boss.pages.bindNiksWAdmins')->with([
			'adminNiks'=>$adminNiks_m->getNicks(),
			'admins'=>$user_m->getAdmins(),
			'adminNiksVV' => $user_m->getAdminsWservicesAndNicks(),
		]);
    }

	/**
	 * storing (binding) real admins with his nicknames
	 * @param Request $request
	 * @return Redirect back to bind
	 */
	public function bindNiks(Request $request)
	{
		request()->validate([
			'user_id'=>'required|numeric',
			'adminNikIds'=>'required|array|min:1'
		]);
		$adminNikIds=(array)$request->adminNikIds;
		$user_id = $request->user_id;
		$adminNik_m = new AdminNik();
		$adminNik_m->where('user_id',$user_id)->update(['user_id'=>0]);
		foreach ($adminNikIds as $adminNikId){
			$res = AdminNik::where('id', $adminNikId)->update(['user_id'=>$user_id]);
		}
		return redirect(route('admins.nicks'))->
		with('msg',sprintf('Администратор с ID %d связан со-своими никами с результатом %d',$user_id,$res));
	}
}
