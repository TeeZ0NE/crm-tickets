<?php

namespace App\Http\Controllers\Boss;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SysadminActivity as Sact;

class AdminActivityController extends Controller
{
	public function __invoke($id)
	{
		$sact_m = new Sact();
		if ($sact_m->destroyActivity($id)) return redirect()->back()->with('msg','Delete successful');
		return redirect()->back()->withErrors(['msg'=>'Delete not successful']);
    }
}
