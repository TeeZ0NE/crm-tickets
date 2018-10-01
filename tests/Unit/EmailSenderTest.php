<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Email;

class EmailSenderTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * @test
	 */
	public function check_get_list_of_all_emails()
	{
		$email_m = new Email();
		$this->assertNotEmpty($email_m->getAllEmails());
	}
	/**
	 * @test
	 */
	public function check_get_emails_from_own_ids_array()
	{
		$email_m = new Email();
		$this->assertNotEmpty($email_m->getEmailsFromId([1,2]));
		$this->assertEquals('vadim@hyperweb.com.ua',$email_m->getEmailsFromId([1])[0]);
	}

	/**
	 * @test-
	 */
	public function check_store_new_email()
	{
		$email_m = new Email();
		$this->assertGreaterThan(3,$email_m->createNewRecord('borjomu@fd.do'));
	}
	/**
	 * @test-
	 */
	public function check_delete_email()
	{
		$email_m = new Email();
		$this->assertTrue($email_m->destroyEmail(3));
	}
}
