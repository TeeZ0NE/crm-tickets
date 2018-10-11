<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Ticket;
class ServiceSearchTest extends TestCase
{
	private $ticket_m;
	public function setUp()
	{
		parent::setUp();
		$this->ticket_m = new Ticket();
	}

	/**
	 * @test-
	 */
	public function check_get_all_services()
	{
		$this->assertNotEmpty($this->ticket_m->getAllServices());
	}

	/**
	 * @test
	 */
	public function check_get_only_one_service()
	{
		$this->assertNotEmpty($this->ticket_m->getServiceTickets(1)->first());
	}

	/**
	 * @test
	 */
	public function check_get_ticket_from_service()
	{
		$this->assertEmpty($this->ticket_m->getServiceTicket(1,1234)->first());
	}

	/**
	 * @test
	 */
	public function get_all_activ()
	{
		$this->assertEmpty($this->ticket_m->getTicketallactivities(2));
	}
}
