<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Libs\Statistic;

class ServiceStatistic extends Mailable
{
	use Queueable, SerializesModels, Statistic;
	private $bcc_list;

	/**
	 * Create a new message instance.
	 * @param int|string $service
	 * @param string $interval
	 * @param array $bcc_list
	 *
	 * @return void
	 */
	public function __construct($service, $interval, $bcc_list=[])
	{
		$this->setVariable($service, $interval);
		$this->bcc_list = $bcc_list;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		$summary = $this->formSummaryStatistic($this->service_id, $this->interval);
		return $this->markdown('emails.statisticService')->
		subject(sprintf('[Secom] Отчет по тикетам для  %s, за период : %s (%s)',$this->service,$this->interval, $this->interval4humans))->
		priority(2)->
		with([
			'statistics' => $this->formStatistic($this->service_id, $this->interval),
			'summary' => $summary,
			'total4humans' => $this->total4humans($summary),
			'service' => $this->service,
		]);
	}
}
