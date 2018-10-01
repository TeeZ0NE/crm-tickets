<?php

namespace App\Http\Controllers\Boss;

use App\Mail\ServiceStatistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
	Email, Service, Interval
};
use App\Http\Libs\Statistic;
use Illuminate\Support\Facades\Mail;

class ServicesStatisticController extends Controller
{
	use Statistic;

	public function __construct()
	{
		$this->middleware('auth:boss');//auth
	}

	public function index()
	{
		$service_m = new Service();
		$interval_m = new Interval();
		return view('boss.pages.serviceStatistic')->with([
			'emailList' => $this->getEmails(),
			'interval' => 'today',
			'intervals'=>$interval_m->getAllIntervals(),
			'services' => $service_m->getAllServices(),
		]);
	}

	/**
	 * Generate statistic
	 *
	 * @param Request $request
	 * @return $this View
	 */
	public function getStatistic(Request $request)
	{
		$request->validate([
			'service_id' => 'required|numeric'
		]);
		$service_id = (int)$request->service_id;
		$interval = $request->interval ?? 'today';
		$interval_m = new Interval();
		$this->setVariable($service_id, $interval);
		$service_m = new Service();
		$summary = $this->formSummaryStatistic($this->service_id, $this->interval);
		return view('boss.pages.serviceStatistic')->with([
			'emailList' => $this->getEmails(),
			'interval' => $this->interval,
			'intervals'=> $interval_m->getAllIntervals(),
			'service_id' => $this->service_id,
			'services' => $service_m->getAllServices(),
			'statistics' => $this->formStatistic($this->service_id, $this->interval),
			'summary' => $summary,
			'total4humans' => $this->total4humans($summary),
		]);
	}

	private function getEmails()
	{
		$email_m = new Email();
		return $email_m->getAllEmails();
	}

	public function sendStatisticViaEmail(Request $request,$service,$interval)
	{
		$request->validate([
		'emails'=>'required'
	]);
		$email_m = new Email();
		$email_list = $email_m->getEmailsFromId($request->emails);
		Mail::to($email_list)->send(new ServiceStatistic($service,$interval));
		return redirect()->back()->with('msg','Письмо отправлено!');
	}
}
