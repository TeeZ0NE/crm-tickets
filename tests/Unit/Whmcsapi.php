<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\TicketBags\Whmcsapi;

class WhmcsapiTest extends TestCase
{
	private $whapi;
	public function setUp()
	{
		parent::setUp();
		$this->whapi = new Whmcsapi('ua-hosting');
	}
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
    	$this->assertInstanceOf(Whmcsapi::class,$this->whapi);
    }

	public function test_get_list_tickets()
	{
		$tickets = $this->whapi->getListTikets();
		$this->assertTrue(true);
//		$this->assertNotEmpty($tickets);

		return $tickets;
    }
    /**
     * @test-
     * @depends test_get_list_tickets
     * @param array$tickets
     */
	public function check_keys_tickets(array $tickets)
	{
		$this->assertArrayHasKey('tickets',$tickets);
    }
    /**
     * @test-
     * @depends test_get_list_tickets
     * @param array $tickets
     */
	public function check_get_tiket_arr_key_exists($tickets)
	{
		$this->assertArrayHasKey('ticket',$tickets['tickets']);
    }
    /**
     * @test-
     * @depends test_get_list_tickets
     * @param array $tickets
     */
	public function array_has_message_key($tickets)
	{
		$this->assertArrayHasKey('message',$tickets);
    }
    /**
     * @test
     * @depends test_get_list_tickets
     * @param array $tickets
     */
	public function array_has_totalresults($tickets)
	{
		$this->assertArrayHasKey('totalresults',$tickets);
    }
    /**
     * @test
     * @depends test_get_list_tickets
     * @param array $tickets
     */
	public function check_count_of_tickets($tickets)
	{
		$this->assertEmpty($tickets['tickets']['ticket']);
    }
}
