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
	private $lists;

	public function __construct()
	{
		$this->setLists();
	}

	/**
	 * Get all lists from mailable
	 *
	 * @return Collection
	 */
	public function getAllLists()
	{
		$mailable_m = new Mailable();
		return $mailable_m->getAllLists();
	}

	/**
	 * Set lists variable
	 */
	private function setLists()
	{
		$this->lists = $this->getAllLists();
	}

	/**
	 * @param Collection $lists
	 * @return \Generator
	 */
	private function recurseLists(Collection $lists)
	{
		foreach ($lists as $list) {
			yield $list;
		}
	}

	/**
	 * Preparing before send
	 *
	 * @return bool|null
	 */
	public function prepareMailing()
	{
		if (($this->lists)->isNotEmpty()) {
			foreach ($this->recurseLists($this->lists) as $list) {
				if ($main_email = $this->getEmail($list->getService)) {
					$interval = $this->getInterval($list->getInterval);
					$service = $this->getService($list->getService);
					$bcc = $this->getEmails($list->getEmails);
					$this->sendStatistic2Email(compact('bcc', 'interval', 'service', 'main_email'));
				} else continue;
			}
			return true;
		} else return null;
	}

	/**
	 * Check existing mail-boxes
	 *
	 * @param $emailsObj
	 * @return bool
	 */
	private function hasEmails($emailsObj)
	{
		return $emailsObj->isNotEmpty();
	}

	/**
	 * Compose bcc 4 sending
	 *
	 * @param $emailsObj
	 * @return array
	 */
	private function getEmails($emailsObj)
	{
		$bcc = [];
		if ($this->hasEmails($emailsObj)) {
			foreach ($emailsObj as $email) {
				array_push($bcc, $email->email);
			}
		}
		return $bcc;
	}

	/**
	 * Getting interval 4 list
	 *
	 * @param $interval
	 * @return mixed
	 */
	private function getInterval($interval)
	{
		return $interval->url_attr;
	}

	/**
	 * Getting service name
	 *
	 * @param $service
	 * @return mixed
	 */
	private function getService($service)
	{
		return $service->name;
	}

	/**
	 * Get main email
	 *
	 * @param $service
	 * @return mixed
	 */
	private function getEmail($service)
	{
		return $service->email;
	}

	/**
	 * Queue mail
	 *
	 * @param array $variables
	 * @return  void
	 */
	private function sendStatistic2Email(array $variables): void
	{
		extract($variables);
		$mail = new Mail();
		$mail::to($main_email)->bcc($bcc)->queue(new ServiceStatistic($service, $interval));
	}
}
