<?php

namespace Tests\Unit;

use App\Models\Ticket;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use App\Models\{Service};
class StoreStat{
 use \App\Http\TicketBags\StoreStatistic;
}
class StoreStatTest extends TestCase
{
	private $request_arr;
	private $storeStat;

	public function setUp()
	{
		parent::setUp();
		$this->request_arr = [[
			'lastreply' => '06.09.2018 18:35:29',
			'time_uses' => 8,
			'subject' => 'Бэкап',
			'admin' => 'Trigger Petrov',
			'ticketid' => '9114']];
		$this->storeStat = new StoreStat('secom',$this->request_arr);
	}

	/**
	 * @test
	 */
	public function isArr()
	{
		$this->assertInternalType('array', $this->request_arr);
	}
	/**
	 * @test
	 */
	public function arrhaskeys()
	{
		$this->assertArrayHasKey('ticketid',$this->request_arr[0]);
	}

	/**
	 * @test
	 */
	public function check_get_service_id()
	{
		$service_id = $this->storeStat->getServiceId('secom');
		$this->assertEquals(1,$service_id);
		return $service_id;
	}
	/**
	 * @test
	 * @depends check_get_service_id
	 */
	public function check_get_admin_nik_id($service_id)
	{
		$admin_nik_id = $this->storeStat->getAdminNikId($this->request_arr[0]['admin'],$service_id);
		$this->assertInternalType('int',$admin_nik_id);
		$this->assertEquals(3,$admin_nik_id);
		return $admin_nik_id;
	}
	/**
	 * @test
	 */
	public function check_get_ticketid()
	{
		$ticketid = $this->storeStat->getTicketIdfromrequest($this->request_arr[0]['ticketid']);
		$this->assertEquals(9114,$ticketid);
		return $ticketid;
	}
	/**
	 * @test
	 */
	public function check_get_lastreply()
	{
		$lastreply = $this->storeStat->getLastreply($this->request_arr[0]['lastreply']);
		$this->assertEquals('2018-09-06 18:35:29',$lastreply->toDateTimeString());
		return $lastreply;
	}
	/**
	 * @test
	 */
	public function check_get_time_uses()
	{
		$time_uses = $this->storeStat->getTimeUses($this->request_arr[0]['time_uses']);
		$this->assertEquals(8,$time_uses);
		return $time_uses;
	}
	/**
	 * @test
	 */
	public function check_get_subject()
	{
		$this->assertNotEmpty($this->storeStat->getSubject($this->request_arr[0]['subject']));
		$this->assertEquals('Бэкап',$this->storeStat->getSubject($this->request_arr[0]['subject']));
	}
	/**
	 * @test
	 * @depends check_get_ticketid
	 * @depends check_get_service_id
	 * @depends check_get_admin_nik_id
	 * @depends check_get_lastreply
	 */
	public function check_get_ticket_id($ticketid,$service_id,$admin_nik_id,$lastreply)
	{
		$values = ['last_replier_nik_id'=>$admin_nik_id,
			'lastreply'=>$lastreply,
			'subject'=>$this->request_arr[0]['subject'],
			'last_is_admin'=>1,
			'priority_id'=>3];
		$ticket_id = $this->storeStat->storeticketandgetid($ticketid,$service_id,$values);
		$this->assertEquals(2,$ticket_id);
		return $ticket_id;
	}

	/**
	 * @test
	 * @depends check_get_ticket_id
	 * @depends check_get_admin_nik_id
	 * @depends check_get_lastreply
	 * @depends check_get_time_uses
	 */
	public function check_store_admin_activities($ticket_id,$nik_id,$lastreply,$time_uses)
	{
		$res = $this->storeStat->storeAdminActivities($ticket_id,$nik_id,$lastreply,$time_uses);
		$this->assertInternalType('int',$res);
	}
	/**
	 * @test
	 */
	public function check_get_default_priority()
	{
		$this->assertGreaterThan(0,$this->storeStat->getPriorityDefault());
	}
	/**
	 * @test
	 */
	public function check_get_status_in_progress()
	{
		$this->assertGreaterThan(0,$this->storeStat->getStatusDefault());
	}
}
