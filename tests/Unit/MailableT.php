<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rules\In;
use Monolog\Processor\IntrospectionProcessor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{
	Mailable, MailList, Service, Interval, Email
};

class MailableT extends TestCase
{
	public function setUp()
	{
		parent::setUp();
   }

	/**
	 * @test
	 */
	public function check_services()
	{
		$service_m = new Service();
		$this->assertNotEmpty($service_m->getAllServices());
   }
   /**
    * @test
    */
	public function check_get_emails()
	{
		$email_m = new Email();
		$this->assertNotEmpty($email_m->getAllEmails());
   }
   /**
    * @test
    */
	public function check_get_intervals()
	{
		$interval_m = new Interval();
		$this->assertNotEmpty($interval_m->getAllIntervals());
   }

   /**
    * @test
    */
	public function check_get_interval_id()
	{
		$interval_m = new Interval();
		$this->assertEquals(3,$interval_m->getIntervalId('start_of_month'));
   }

   /**
    * @test-
    */
	public function check_get_id_store_new_mailable()
	{
		$mailable_m = new MailableT();
		$this->assertGreaterThan(0,$mailable_m->storeNewInterval(2,3));
   }

   /**
    * @test-
    */
	public function check_store_mail_list()
	{
		$mailList_m = new MailList();
		$this->assertGreaterThan(0,$mailList_m->storeNewEmails(5,6));
   }
   /**
    * @test
    */
	public function check_interval_from_id()
	{
		$interval_m = new Interval();
		$this->assertEquals('start_of_month',$interval_m->getIntervalFromId(3));
   }

   /**
    * @test--
    */
	public function check_edit_mailInterval()
	{
		$mailable_m = new MailableT();
		$mailableInterval = $mailable_m->with(['getInterval','getEmails','getService'])->find(1);
		$this->assertNotEmpty($mailableInterval);
		$this->assertEquals(1,$mailableInterval->service_id);
		$this->assertEquals('today',$mailableInterval->getInterval->url_attr);

   }
   /**
    * @test-
    */
	public function check_get_emails_from_mailables_id()
	{
		$maillist_m = new MailList();
//		$this->assertEquals([1,2],$maillist_m->getEmailsWithMailable(1));
		$this->assertEmpty($maillist_m->getEmailsWithMailable(9));
   }
   /**
    * @test
    */
	public function check_get_mailables_all()
	{
		$mailable_m = new MailableT();
		$mailable_all = $mailable_m->getAllLists();
		$this->assertNotEmpty($mailable_all);
		return $mailable_all->first();
   }

   /**
    * @test
    * @depends check_get_mailables_all
    * @param Collection $mailable_all
    */
	public function check_get_service_name($mailable_all)
	{
		$this->assertEquals('secom',$mailable_all->getService->name);
   }

	/**
	 * @test
	 * @depends check_get_mailables_all
	 * @param Collection $mailable_all
	 */
	public function check_get_interval($mailable_all)
	{
		$this->assertEquals('yesterday',$mailable_all->getInterval->url_attr);
	}
	/**
	 * @test
	 * @depends check_get_mailables_all
	 * @param Collection $mailable_all
	 */
	public function check_get_emails_from_mailable($mailable_all)
	{
		$this->assertNotEmpty($mailable_all->getEmails);
	}

	/**
	 * @test
	 * @depends check_get_mailables_all
	 * @param Collection $mailable_all
	 */
	public function check_get_email_from_mailable($mailable_all)
	{
		$this->assertEquals('vadim@hyperweb.com.ua',$mailable_all->getEmails->first()->email);
	}

	/**
	 * @test
	 * @depends check_get_mailables_all
	 * @param Collection $mailable_all
	 * @return array
	 */
	public function check_get_email_main_and_bcc($mailable_all)
	{
		$emails = $mailable_all->getEmails;
		$this->assertNotEmpty($emails);
		$main = [];
		$bcc = [];
		$this->assertEmpty($main);
		$this->assertEquals(0,$emails->first()->is_main);
		foreach ($emails as $email){
			if($email->is_main and empty($main)) array_push($main,$email->email);
			else array_push($bcc,$email->email);
		}
		$this->assertEmpty($main);
		$this->assertNotEmpty($bcc);
		$this->assertEquals('vadim@hyperweb.com.ua',$bcc[0]);
//		$this->assertEquals('endnet@ukr.net',$main[0]);
	return compact('main','bcc');
	}

	/**
	 * @test
	 * @depends check_get_email_main_and_bcc
	 * @param array $all_emails
	 */
	public function check_make_split_main_and_bss($all_emails)
	{
		extract($all_emails);
		$this->assertInternalType('array',$main);
		$this->assertEmpty($main);
		$this->assertNotEmpty($bcc);
		array_push($main,array_pop($bcc));
		$this->assertNotEmpty($main);
		$this->assertNotEmpty($bcc);
		$this->assertEquals('vadim@hyperweb.com.ua',$bcc[0]);
		$this->assertEquals('endnet@ukr.net',$main[0]);
	}
}
