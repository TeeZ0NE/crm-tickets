<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{AdminNik,Sysadmin};
use Illuminate\Support\Facades\Redirect;

class RealAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$admin_m = new Sysadmin();
        return view('admins.pages.adminsShowAll')->with([
        	'admins'=>$admin_m->getAdmins(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admins.pages.adminsCreate');
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
		    'name'=>'required|unique:sysadmins|max:120',
	    ]);
	    $admin_name = $request->name;
	    Sysadmin::insert(['name'=>$admin_name]);
	    return redirect(route('index'))->with('msg', "$admin_name add to DB!");
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
			'name'=>'required|unique:sysadmins',
		]);
//    	Sysadmin::find($id)->update(['name'=>$request->admin_name]);
        echo $request->name."==".$id;
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
    	$admin_m = new Sysadmin();
    	$adminNiks_m->where('admin_id',$id)->update(['admin_id'=>0]);
    	$res = $admin_m::find($id)->delete();
    	if($res) return redirect(route('admins.index'))->with('msg',"admin id with $id deleted $res");
    	else return redirect(route('admins.index'))->withErrors(['msg'=>"Admin id $id doesn't delete"]);
    }

	/**
	 * showing a list with real admins's names and a list with own niks
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function nicks()
	{
		$admins_m = new Sysadmin();
		$adminNiks_m = new AdminNik();
		return view('admins.pages.bindNiksWAdmins')->with([
			'adminNiks'=>$adminNiks_m->getNicks(),
			'admins'=>$admins_m->getAdmins(),
			'adminNiksVV' => $admins_m->getAdminsWservicesAndNicks(),
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
			'admin_id'=>'required|numeric',
			'adminNikIds'=>'required|array|min:1'
		]);
		$adminNikIds=(array)$request->adminNikIds;
		$admin_id = $request->admin_id;
		$adminNik_m = new AdminNik();
		$adminNik_m->where('admin_id',$admin_id)->update(['admin_id'=>0]);
		foreach ($adminNikIds as $adminNikId){
			AdminNik::where('admin_nik_id', $adminNikId)->update(['admin_id'=>$admin_id]);
		}
		return redirect(route('admins.nicks'))->with('msg','Binded');
	}
}
