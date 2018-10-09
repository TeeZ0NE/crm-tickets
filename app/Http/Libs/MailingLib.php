<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 08.10.18
 * Time: 13:23
 */

namespace App\Http\Libs;

use App\Models\Mailable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\ServiceStatistic;

class MailingLib
{
	public $lists;

	public function __construct()
	{
		$this->setLists();
	}
	public function getAllLists()
	{
		$mailable_m = new Mailable();
		return $mailable_m->getAllLists();
	}

	function setLists()
	{
		$this->lists = $this->getAllLists();
	}
	private function recurseLists(Collection $lists)
	{
		foreach ($lists as $list){
			yield $list;
		}
	}
	
	function prepareMailing(){
		if(($this->lists)->isNotEmpty()) {
			foreach ($this->recurseLists($this->lists) as $list) {
				if($emails = $this->getEmails($list->getEmails)){
					//todo: check interval range
					$interval = $this->getInterval($list->getInterval);
					$service = $this->getService($list->getService);
					$this->sendStatistic2Email(compact('emails','interval','service'));
				}
				else continue;
			}
			return true;
		}else return null;
	}

	function hasEmails($emailsObj)
	{
		return $emailsObj->isNotEmpty();
	}

	function getEmails($emailsObj){
		if ($this->hasEmails($emailsObj)){
			$main = $bcc = [];
			foreach ($emailsObj as $email){
				if($email->is_main and empty($main)) array_push($main,$email->email);
				else array_push($bcc,$email->email);
			}
			if(empty($main)) array_push($main,array_pop($bcc));
			return compact('main','bcc');
		}
		return null;
	}

	function getInterval($interval){
		return $interval->url_attr;
	}

	function getService($service){
		return $service->name;
	}

	function sendStatistic2Email(array $variables){
		extract($variables);
		extract($emails);
		$mail = new Mail();
		$mail::to($main)->bcc($bcc)->queue(new ServiceStatistic($service, $interval));
	}
}