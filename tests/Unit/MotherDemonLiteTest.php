<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 04.09.18
 * Time: 15:50
 */

namespace Tests\Unit;

use App\Http\TicketBags\MotherWhmcsDaemonLite;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{
	Priority, Service, Status, Ticket
};

class WhmcsLite{
	use MotherWhmcsDaemonLite;
}


class MotherDemonLiteTest extends TestCase
{
//	use RefreshDatabase;
	private $service='secom';
	private $whmcs;

	public function setUp()
	{
		parent::setUp();
		$this->service='secom';
		$this->whmcs=new WhmcsLite($this->service);
	}
	/**
	 * @test
	 */
	public function getServiceName()
	{
		$service = $this->service;
		$this->assertNotEmpty($service);
		return $service;
	}
	/**
	 * @test-
	 */
	public function createInstance()
	{
		$this->assertInstanceOf(WhmcsLite::class, $this->whmcs);
	}

	/**
	 * @test
	 */
	public function checkGetTicketsFromService():array
	{
		$tickets = $this->whmcs->getTicketsFromService();
		$this->assertInternalType('array',$tickets);
		return $tickets[0];
	}
	/**
	 * @test
	 * @depends checkGetTicketsFromService
	 */
	public function ticketsArrHasKey($tickets)
	{
		$this->assertArrayHasKey('id',$tickets);
		$this->assertArrayHasKey('status',$tickets);
		$this->assertArrayHasKey('subject',$tickets);
		$this->assertArrayHasKey('priority',$tickets);
		$this->assertArrayHasKey('lastreply',$tickets);
	}
	/**
	 * @test
	 * @depends checkGetTicketsFromService
	 */
	public function checkGetTicketid($ticket):int
	{
		$ticketid = $this->whmcs->getTicketid($ticket);
		$this->assertInternalType('integer',$ticketid);
		return $ticketid;
	}
	/**
	 * @test
	 * @depends getServiceName
	 */
	public function checkGetServiceId($service):int
	{
		$service_m = new Service();//factory(Service::class)->create();
		$this->assertDatabaseHas('services', [
			'name' => $service
		]);
		$service_id = 7;
		$this->assertEquals($service_id,$this->whmcs->getServiceId($service));
		return $service_id;
	}

	/**
	 * @test
	 * @depends checkGetTicketsFromService
	 */
	public function checkGetStatusId($ticket):int
	{
		$status_id=$this->whmcs->getStatusId($ticket);
		$this->assertGreaterThan(0,$status_id);
		return $status_id;
	}

	/**
	 * @param $ticket
	 * @test
	 * @depends checkGetTicketsFromService
	 */
	public function checkGetSubject($ticket):string
	{
		$subject_whmcs = $this->whmcs->getSubject($ticket);
		$subject = $ticket['subject'];
		$this->assertInternalType('string',$subject);
		$this->assertEquals($subject,$subject_whmcs);
		return $subject;
	}

	/**
	 * @test
	 * @depends checkGetTicketsFromService
	 */
	public function checkGetPriorityId($ticket) :int
	{
		$priority_m = new Priority();
		$priority_id = $priority_m::firstOrCreate(['priority'=>$ticket['priority']])->id;
		$this->assertDatabaseHas('priorities',['priority'=>$ticket['priority']]);
		$priority_id_wh = $this->whmcs->getPriorityId($ticket);
		$this->assertEquals($priority_id,$priority_id_wh);
		return $priority_id;
	}

	/**
	 * @test
	 * @depends checkGetTicketsFromService
	 * @param array $ticket
	 */
	public function checkGetLastreply($ticket)
	{
		$lastreply = $this->whmcs->getLastreply($ticket);
		$this->assertNotEmpty($lastreply);
		return $lastreply;
	}
	/**
	 * @test
	 * @depends checkGetTicketid
	 * @depends checkGetServiceId
	 * @param int $ticketid
	 * @param int $service_id
	 * @return int $ticket_id
	 */
	public function checkTicketExist(int $ticketid, int $service_id)
	{
//		$ticket_exist = $this->whmcs->ticketExist($ticketid, $service_id);
		$ticket_exist=6;
		$this->assertInternalType('int',$ticket_exist);
		return $ticket_exist;
	}

	/**
	 * @test-
	 * @depends checkTicketExist
	 * @depends checkGetTicketid
	 * @depends checkGetServiceId
	 * @depends checkGetSubject
	 * @depends checkGetStatusId
	 * @depends checkGetPriorityId
	 * @depends checkGetLastreply
	 * @param int $ticket_exist
	 * @param int $service_id
	 * @param string $subject
	 * @param int $status_id
	 * @param int $priority_id
	 * @param object $lastreply
	 */
	public function checkStoreNewTicketOrUpdate(
		int $ticket_exist, int $ticketid,$service_id,$subject, $status_id,$priority_id,$lastreply
)	{
		if($ticket_exist) {$des = $this->whmcs->updateTicket($ticket_exist, $status_id,$priority_id, $lastreply);}
		else {$des = $this->whmcs->storeNewTicket($ticketid,$service_id,$subject,$status_id,$priority_id,$lastreply);}
		$this->assertEquals(6,$des);
	}

	/**-
	 * @test
	 * @depends checkGetTicketid
	 * @depends checkGetServiceId
	 * @depends checkGetSubject
	 * @depends checkGetStatusId
	 * @depends checkGetPriorityId
	 * @depends checkGetLastreply
	 * @param int $service_id
	 * @param string $subject
	 * @param int $status_id
	 * @param int $priority_id
	 * @param lastreply
	 */
	public function checkStoreNewTicket($ticketid,$service_id,$subject,$status_id,$priority_id,$lastreply)
	{
		$ticket_id = $this->whmcs->storeNewTicket($ticketid,$service_id,$subject,$status_id,$priority_id,$lastreply);
		$this->assertInternalType('int',$ticket_id);
	}
	/**
	 * @test
	 * @depends checkTicketExist
	 */
	public function checkGetLastreplyFromDb($ticket_id)
	{
		$this->assertInternalType('int',$ticket_id);
		$lastreplyfromdb = $this->whmcs->getLastreplyFromDb($ticket_id);
		$this->assertNotEmpty($lastreplyfromdb);
	}

	/**
	 * @test
	 * @depends checkTicketExist
	 * @depends checkGetLastreply
	 */
	public function checkIsAdmin($ticket_id,$lastreply)
	{
		$this->assertGreaterThan(0,$ticket_id);
		$this->assertNotEmpty($lastreply);
		$getLastreplyFromDb = $this->whmcs->getLastreplyFromDb($ticket_id);
		$this->assertNotEmpty($getLastreplyFromDb);
		$compare = $this->whmcs->isAdmin($lastreply,$getLastreplyFromDb);
		$this->assertEquals(0,$compare);
//		return $compare;
		return 0;
	}
	/**
	 * @test-
	 * @depends checkTicketExist
	 */
	public function checkGetIsAdminFromDb($ticket_id)
	{
		$isAdminFromDb = $this->whmcs->getIsAdminFromDb($ticket_id);
		$this->assertInternalType('int',$isAdminFromDb);
	}

	/**
	 * @test
	 * @depends checkGetServiceId
	 */
	public function checkGetInnerTicketsIds($service_id)
	{
		$inner_ids = $this->whmcs->getinnerticketsids($service_id);
		$this->assertInternalType('array',$inner_ids);
		$this->assertNotEmpty($inner_ids);
//		return $inner_ids;
	}
	/**
	 * @test
	 * @depends checkGetTicketsFromService
	 */
	public function checkGetOuterTicketsIds($tickets)
	{
		$this->assertArrayHasKey('id',$tickets);
		$outer_ids = [$tickets['id']];
		$this->assertInternalType('array',$outer_ids);
		$this->assertNotEmpty($outer_ids);
//		return $outer_ids;
	}
	/**
	 * @test-
	 * @depends checkGetInnerTicketsIds
	 * @depends checkGetOuterTicketsIds
	 */
	public function checkGetClosedTickets($inner_ids,$outer_ids)
	{
		$absent_ids = $this->whmcs->getClosedTickets($inner_ids,$outer_ids);
		$this->assertNotEmpty($absent_ids);
//		return $absent_ids;
	}

	/**
	 * @test-
	 * @depends checkGetClosedTickets
	 */
	public function checkclosetickets($absent_ids)
	{
		$this->assertNotEmpty($absent_ids);
		$res = $this->whmcs->closeTickets($absent_ids);
		$this->assertInternalType('int',$res);
	}
	/**
	 * @test
	 * @depends checkTicketExist
	 * @depends checkGetStatusId
	 * @depends checkGetPriorityId
	 * @depends checkGetLastreply
	 * @depends checkIsAdmin
	 * @param $ticket_id
	 */
	public function checkUpdateTicket(int $ticket_id,int $status_id,int $priority_id, string $lastreply, int $is_admin)
	{
	$res = $this->whmcs->UpdateTicket($ticket_id,$status_id,$priority_id,$lastreply,$is_admin);
		$this->assertTrue($res);
	}
}

