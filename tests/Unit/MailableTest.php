<?php

namespace Tests\Unit;

use Illuminate\Validation\Rules\In;
use Monolog\Processor\IntrospectionProcessor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{
	Mailable, MailList, Service, Interval, Email
};

class MailableTest extends TestCase
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
		$mailable_m = new Mailable();
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
    * @test
    */
	public function check_edit_mailInterval()
	{
		$mailable_m = new Mailable();
		$mailableInterval = $mailable_m->with(['getInterval','getEmails','getService'])->find(1);
		$this->assertNotEmpty($mailableInterval);
		$this->assertEquals(1,$mailableInterval->service_id);
		$this->assertEquals('today',$mailableInterval->getInterval->url_attr);

   }
   /**
    * @test
    */
	public function check_get_emails_from_mailables_id()
	{
		$maillist_m = new MailList();
//		$this->assertEquals([1,2],$maillist_m->getEmailsWithMailable(1));
		$this->assertEmpty($maillist_m->getEmailsWithMailable(9));
   }
}
