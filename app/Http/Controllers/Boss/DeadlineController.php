<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Deadline;
use Illuminate\Database\Eloquent\ModelNotFoundException as MNF;
use Illuminate\Support\Facades\Log;
use Exception;

class DeadlineController extends Controller
{
	/**
	 * @var Array
	 */
	private 	$deadlineList;

	public function __construct()
	{
		$this->middleware('auth:boss');
		$this->deadlineList = $this->getListOfDeadlines();
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('boss.pages.deadlineAll')->with([
        	'deadlines'=>Deadline::orderByDesc('deadline')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('boss.pages.deadlineAdd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
        	'hour'=>'numeric|required',
	        'minutes'=>'numeric|required|max:59'
        ]);
        $hour = $request->hour ?? 0;
        $minutes = $request->minutes ?? 0;
        $msg = 'Deadline with ID %d stored';
        $error_msg = 'Deadline not stored';
        $time = $this->convert2Time($hour,$minutes);
        $deadline_id = Deadline::firstOrCreate(['deadline'=>$time]);
        if($deadline_id) return redirect(route('deadline.index'))->with('msg',sprintf($msg,$deadline_id->id));
        else return redirect()->back()->withErrors(['msg'=>$error_msg]);
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
    	$error_msg = 'Deadline with ID %d not found';
    	try{
    	$time = explode(":",Deadline::findOrFail($id)->deadline);
    	}
    	catch(MNF $mnf){
    		Log::error(sprintf($error_msg,$id));
    		return redirect()->back()->withErrors(['msg'=>sprintf($error_msg,$id)]);
	    }
        return view('boss.pages.deadlineEdit')->with([
        	'hour'=>$time[0],
	        'minutes'=>$time[1],
	        'id'=>$id,
        ]);
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
    		'hour'=> 'numeric|required|max:850',
		    'minutes'=>'numeric|required|max:59'
	    ]);
    	$error_msg = 'Deadline with ID %d not found';
    	$msg = 'Deadline with ID %d updated';
    	$time = $this->convert2Time($request->hour,$request->minutes);
    	try {
    		Deadline::findOrFail($id)->update(['deadline'=>$time]);
    		Log::info(sprintf($msg,$id));
	    }
	    catch (MNF $mnf){
    		return redirect()->back()->withErrors(['msg'=>sprintf($error_msg,$id)]);
	    }
        return redirect(route('deadline.index'))->with('msg',sprintf($msg,$id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$res = Deadline::find($id)->delete();
    	$msg = 'Deadline with ID %d deleted';
    	$error_msg = 'Deadline with ID %d was not deleted';
    	if ($res) {
    		Log::info(sprintf($msg,$id));
    		return redirect(route('deadline.index'))->with('msg',sprintf($msg,$id));
	    }
	    Log::error(sprintf($error_msg,$id));
        return redirect()->back()->withErrors(['msg'=>sprintf($error_msg,$id)]);
    }

	/**
	 * Convert variables 2 Time type
	 * @param int $hour
	 * @param int $minutes
	 * @return string
	 */
    private function convert2Time(int $hour,int $minutes)  {
	    $time = Carbon::createFromFormat('H:i',sprintf('%1$02d:%2$02d', $hour, $minutes))->toTimeString();
	    return $time;
    }

	/**
	 * Convert to minutes
	 * @param $time
	 * @return int
	 */
    public function explodeTime( $time) : int {
    	try{
    		$explode_time = explode(':',$time);
    		$hour = $explode_time[0];
    		$minutes = $explode_time[1];
    		$h2min = $hour*60;
    		$exploded_time = $minutes+$h2min;
	    }
	    catch(Exception $e){
		    $exploded_time = 0;
	    }
	    return $exploded_time;
    }

	/**
	 * Getting list of deadlines from DB
	 * @return array
	 */
	public function getListOfDeadlines():array
	{
		return Deadline::orderBy('deadline')->pluck('deadline')->toArray();
	}

	/**
	 * @return \Generator
	 */
	private function convertArray2Min(){
		foreach ($this->deadlineList as $deadline){
			yield $deadline;
		}
	}

	/**
	 * Convert list with deadline 2 minutes
	 * @return array
	 */
	public function getSummaryArrMinutes(){
		$timeArr=[];
		foreach ($this->convertArray2Min() as $element){
			array_push($timeArr,$this->explodeTime($element));
		}
		return $timeArr;
	}
}
