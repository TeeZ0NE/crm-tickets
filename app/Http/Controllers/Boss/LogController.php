<?php

namespace App\Http\Controllers\Boss;

use App\Http\Controllers\Controller;
use http\Env\Request;

class LogController extends Controller
{
	private $log_file = 'tickets.log';
	private $path;

	public function __construct()
	{
		$this->middleware('auth:boss');
		$this->path = storage_path() . '/logs/' . $this->log_file;
	}

	public function index()
	{
		$logs = (file_exists($this->path)) ? file_get_contents($this->path) : 'File not found';
		return view('boss.pages.logs')->with(['logs' => $logs]);
	}

	public function truncate_log()
	{
		$f_p = fopen($this->path, 'r+t');
		ftruncate($f_p, 0);
		fseek($f_p, 0, SEEK_SET);
		fclose($f_p);
		return redirect(route('logs'))->with('msg','Logs cleaned');
	}
}
