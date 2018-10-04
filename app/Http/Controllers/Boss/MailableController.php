<?php

namespace App\Http\Controllers\Boss;

use App\Models\{
	Email, Interval, Mailable, MailList, Service
};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MailableController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$mailable_m = new Mailable();
		return view('boss.pages.emailMailableLists')->with([
			'mailables' => $mailable_m->getAllLists(),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$service_m = new Service();
		$email_m = new Email();
		$interval_m = new Interval();
		return view('boss.pages.mailingListsCreate')->with([
			'emails' => $email_m->getAllEmails(),
			'intervals' => $interval_m->getAllIntervals(),
			'services' => $service_m->getAllServices(),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$service_id = $request->service_id;
		$interval_id = $this->getIntervalId($request->interval);
		$emails = $request->emails ?? [];
		$mailable_id = $this->storeNewInterval($service_id, $interval_id);
		if (!empty($emails)) $this->storeEmailList($mailable_id, $emails);
		return redirect(route('email-lists.index'))->with('msg', 'Данные внесены');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return void
	 */
	public function show($id)
	{
		$this->edit($id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$service_m= new Service();
		$email_m = new Email();
		$mailable_m = new Mailable();
		$interval_m = new Interval();
		$mailInterval = $mailable_m->with(['getInterval','getService'])->find($id);
		$service_id = $mailInterval->service_id;
		$interval = $mailInterval->getInterval->url_attr;
		return view('boss.pages.mailingListsEdit')->with([
			'emails' => $email_m->getAllEmails(),
			'emails_ids'=>$this->getEmailsIds($id),
			'interval'=>$interval,
			'intervals' => $interval_m->getAllIntervals(),
			'mailable_id'=>$id,
			'service_id' => $service_id,
			'services' => $service_m->getAllServices(),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$mailable_m= new Mailable();
		$maillist_m = new MailList();
		$service_id = $request->service_id;
		$interval_id = $this->getIntervalId($request->interval);
		$mailable_upd = $mailable_m->updateMailable($id,$service_id,$interval_id);
		$emails = $request->emails ?? [];
		$maillist_m->deleteEmailsId4mailable($id);
		if(!empty($emails)) $this->storeEmailList($id,$emails);
		if($mailable_upd) return redirect(route('email-lists.index'))->with('msg','Обновлено');
		return redirect()->back()->withErrors(['msg'=>"Не обновлено"]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$mailable_m = new Mailable();
		$destroyed = $mailable_m->destroyMailable($id);
		if($destroyed) return redirect(route('email-lists.index'))->with('msg',sprintf('Список рассылки №%d удален',$id));
		return redirect(route('email-lists.index'))->withErrors(['msg'=>sprintf('Список рассылки №%d не удален',$id)]);
	}

	/**
	 * Store new interval 4 service
	 *
	 * @param int $service_id
	 * @param int $interval_id
	 * @return int mailable_id
	 */
	private function storeNewInterval(int $service_id, int $interval_id)
	{
		$mailable_m = new Mailable();
		return $mailable_m->storeNewInterval($service_id, $interval_id);
	}

	/**
	 * Store mails and mailable_id in MailList
	 * @param int $mailable_id
	 * @param array $emails
	 */
	private function storeEmailList(int $mailable_id, array $emails): void
	{
		$mailList_m = new MailList();
		foreach ($emails as $email) {
			$mailList_m->insert(['mailable_id' => $mailable_id, 'email_id' => $email]);
		}
	}

	/**
	 * Get emails array of ids
	 * @param int $mailable_id
	 * @return array
	 */
	private function getEmailsIds(int $mailable_id)
	{
		$mailList = new MailList();
		return $mailList->getEmailsWithMailable($mailable_id);
	}

	/**
	 * @param string $interval
	 * @return int
	 */
	private function getIntervalId(string $interval):int
	{
		$interval_m = new Interval();
		$interval_id = $interval_m->getIntervalId($interval);
		return $interval_id;
	}
}
