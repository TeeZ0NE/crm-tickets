<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Libs\MailingLib;

class MailingLibTest extends TestCase
{
	private $ML;

	public function setUp()
	{
		parent::setUp();
		$this->ML = new MailingLib();
		$this->ML->setLists();
	}

	/**
	 * @test
	 */
	public function check_instance()
	{
		$this->assertInstanceOf(MailingLib::class, $this->ML);
	}

	/**
	 * @test
	 */
	public function check_lists_set()
	{
		$this->assertNotEmpty($this->ML->lists);
	}

	/**
	 * @test
	 */
	public function check_does_list_have_emails()
	{
		$this->assertTrue($this->ML->hasemails($this->ML->lists->first()->getEmails));
		$this->assertNotEmpty($this->ML->getEmails($this->ML->lists->first()->getEmails));
	}

	/**
	 * @test
	 */
	public function check_get_interval()
	{
		$this->assertEquals('start_of_month',$this->ML->getInterval($this->ML->lists->first()->getInterval));
	}

	/**
	 * @test
	 */
	public function check_get_service()
	{
		$this->assertEquals('secom',$this->ML->getService($this->ML->lists->first()->getService));
	}

	/**
	 * @test
	 */
	public function check_variables_in_prepare_mailings()
	{
		extract($this->ML->prepareMailing());
		$this->assertEquals('secom',$service);
		$this->assertEquals('start_of_month',$interval);
		extract($emails);
		$this->assertEquals('vadim@hyperweb.com.ua',$main[0]);
		$this->assertEmpty($bcc);
	}
}
