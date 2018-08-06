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
    	return view('boss.pages.logs')->with(['logs'=>file_get_contents($path)]);
    }
}
