<?php

namespace App\Http\Controllers\Boss;

use App\Http\Controllers\Controller;

class LogController extends Controller
{
	private $log_file = 'tickets.log';
	public function __construct()
	{
		$this->middleware('auth:boss');
	}
    public function __invoke(){
    	$path = storage_path().'/logs/'.$this->log_file;
    	$logs =(file_exists($path))?file_get_contents($path):'File not found';
    	return view('boss.pages.logs')->with(['logs'=>$logs]);
    }
}
