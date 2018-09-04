<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException as MNFound;
use Exception;
use Illuminate\Support\Facades\Lang;

class ServicesController extends Controller
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
        return view('boss.pages.services')->with([
        	'services'=>Service::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    	$serv_m = new Service;
    	$request->validate([
    		'name'=>'required|max:80|unique:services',
		    'compl'=>'required|numeric'
	    ]);
		$serv_m->name = $request->name;
		$serv_m->compl = $request->compl;
		$id = $serv_m->save();
		if($id) return redirect(route('services.index'))->with('msg','Клиент создан');
		else return redirect()->back()->withErrors(['msg'=>'Сервис не записан']);
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
    	$request->validate([
    		'name'=>'required|max:75|unique:services',
		    'compl'=>'required|numeric',
	    ]);
    	$name = $request->name;
    	$compl=$request->compl;
    	$service_m = new Service();
    	$res = $service_m->find($id)->update(['name'=>$name,'compl'=>$compl]);
        printf("update id %d name %s compl %.1f", $id,$name,$compl);
        if($res){
	        Log::info(sprintf('Service %2$s with id %1$d updated',$id,$name));
        	return redirect(route('services.index'))->with('msg',sprintf("update id %d name %s compl %.1f", $id,$name,$compl));
        }
	    Log::error(sprintf('Service %2$s with id %1$d doesn\'t update',$id,$name));
	    return redirect()->back()->withErrors('msg',sprintf("Error updating id %d name %s compl %.1f", $id,$name,$compl));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$service_m = new Service();
    	$msg = 'Service %d deleted with result %d';
    	$error_msg = 'Service %d deleted with result %d msg %s';
    	$res = 0;
    	try{
    		$res = $service_m::findOrFail($id)->delete();
    	    Log::info(sprintf($msg,$id,$res));
	    }
	    catch(MNFound | Exception $mnf){
    		Log::error(sprintf($error_msg,$id,$res,$mnf->getMessage()));
    		return redirect(route('services.index'))->withErrors(['msg'=>sprintf($error_msg,$id,$res,Lang::get('errors.using foreign keys'))]);
	    }
	    return redirect(route('services.index'))->with('msg',sprintf($msg,$id,$res));
    }

	public function serviceCreate()
	{
		return view('boss.pages.serviceCreate');
    }
}
