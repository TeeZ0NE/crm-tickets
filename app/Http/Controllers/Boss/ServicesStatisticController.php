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

	public function index()
	{
		$service_m = new Service();
		$interval_m = new Interval();
		return view('boss.pages.serviceStatistic')->with([
			'emailList' => $this->getEmails(),
			'interval' => 'today',
			'intervals' => $interval_m->getAllIntervals(),
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
			'emails' => $this->getEmails(),
			'interval' => $this->interval,
			'intervals' => $interval_m->getAllIntervals(),
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

	public function sendStatisticViaEmail($service_id, $interval)
	{
		if ($main_email = $this->getMainEmail($service_id)) {
			Mail::to($main_email)->send(new ServiceStatistic($service_id, $interval));
			return redirect()->back()->with('msg', 'Письмо отправлено!');
		} else return redirect()->back()->withErrors(['msg' => sprintf('Email for this service (%d) is empty, set it first', $service_id)]);
	}

	private function getMainEmail(int $service_id)
	{
		$service_m = new Service();
		$main_email = $service_m::find($service_id)->email;
		return $main_email;
	}
}
