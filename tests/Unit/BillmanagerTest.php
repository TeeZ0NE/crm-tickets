<?php

namespace Tests\Unit;

use App\Models\Status;
use App\Models\Ticket;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Http\TicketBags\Billmgr;

class BillManager
{
	use Billmgr;
}

class BillmanagerTest extends TestCase
{
	private $data;
	private $billMgr;

	public function setUp()
	{
		parent::setUp();
		/*$user = 'r.wayne';//'techmonitoring';
		$pass = 'eC%!nhp96g'; //'BaEC3LMGci';
		$format = 'json';
		$url = sprintf('https://my.skt.ru/billmgr?authinfo=%2$s:%3$s&out=%1$s&func=ticket', $format, $user, $pass);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		curl_close($ch);
		$this->data = json_decode($data, true);*/
		$this->billMgr = new BillManager('skt');
	}
	/**
	 * @test
	 */
	public function check_user_name()
	{
		$this->assertEquals('r.wayne',config('curl-connection.skt.identifier'));
	}
	/**
	 * @test
	 */
	public function check_get_data_from_billmgr()
	{
		$data = $this->billMgr->getData();
		$this->assertNotEmpty($data);
		return $data;
	}

	/**
	 * @test
	 * @depends check_get_data_from_billmgr
	 * @param $data
	 */
	public function check_is_service_available($data)
	{
		$this->assertTrue($this->billMgr->is_service_available($data));
	}
	/**
	 * @test
	 * 
	 */
	public function check_get_tickets()
	{
		$tickets = $this->billMgr->tickets;
		$this->assertAttributeNotEmpty('tickets',$this->billMgr);
		return $tickets;
	}
	/**
	 * @test
	 * @depends check_get_tickets
	 * @param array $tickets
	 */
	public function check_get_ticketid($tickets)
	{
		$this->assertEquals(2750,$this->billMgr->getTicketid($tickets[0]));
	}
	/**
	 * @test
	 */
	public function check_service_id()
	{
		$this->assertEquals(7,$this->billMgr->service_id);
	}
	/**
	 * @test
	 * @depends check_get_tickets
	 * @param array $tickets
	 */
	public function check_get_subject($tickets)
	{
		$this->assertEquals('test, dont close',$this->billMgr->getSubject($tickets[0]));
	}
	/**
	 * @test
	 * @depends check_get_tickets
	 * @param array $tickets
	 */
	public function check_get_priority_id($tickets)
	{
		$this->assertEquals(2,$this->billMgr->getPriorityId($tickets[0]));
	}
	/**
	 * @test
	 * @depends check_get_tickets
	 * @param array $tickets
	 * @return string $lastreply
	 */
	public function check_get_lastreply($tickets)
	{
		$lastreply = $this->billMgr->getLastreply($tickets[0]);
		$this->assertThat(
			$lastreply,
			$this->logicalOr(
				$this->equalTo('2018-09-19 11:10:00'),
				$this->equalTo('2018-09-19 11:11:00')
			)
		);
//		$this->assertEquals($lastreply,(string)$this->billMgr->getLastreply($tickets[0]));
		return $lastreply;
	}
	/**
	 * @test
	 * @depends check_get_tickets
	 * @param array $tickets
	 */
	public function is_ticket_in_db_get_own_id($tickets)
	{
		$ticket_m = new Ticket();
		$ticket_id = $ticket_m->getTicketIdFromDb($this->billMgr->getTicketId($tickets[0]),$this->billMgr->service_id);
		$this->assertGreaterThanOrEqual(0,$ticket_id);
//		$this->assertEquals(78,$ticket_id);
	}
	/**
	 * @test
	 */
	public function check_get_status_from_ticket_model()
	{
	$status_m = new Status();
	$this->assertEquals(2,$status_m->getStatusId('customer-reply'));
	}
	/**
	 * @test-
	 */
	public function check_get_lastreply_from_db()
	{
		$ticket_m = new Ticket();
		$lastreply = $ticket_m->getLastreply(77);
		$this->assertNotEmpty($lastreply);
		return $lastreply;
	}
	/**
	 * @test-
	 * @depends check_get_lastreply
	 * @depends check_get_lastreply_from_db
	 * @param string $lastreply
	 * @param string $lastreply_from_db
	 */
	public function check_compare_2_lastreplies($lastreply,$lastreply_from_db)
	{
		$this->assertNotEmpty($lastreply);
		$this->assertNotEmpty($lastreply_from_db);
		$Carbon = new Carbon();
		$ticket_m = new Ticket();
		$is_last_is_admin = $ticket_m->getIsAdminFromDb(77);
		$lastreply_c= $Carbon::parse($lastreply);
		$lastreply_from_db_c1 = $Carbon::parse($lastreply_from_db)->subMinute();
		$lastreply_from_db_c2 = $Carbon::parse($lastreply_from_db)->addMinute();
		# is_customer
		$is_customer = $lastreply_c->between($lastreply_from_db_c1,$lastreply_from_db_c2);
//		$this->assertTrue($res);
		$replier = (!$is_last_is_admin && $is_customer)?'customer':'admin';
		$res2 = ($Carbon::parse($lastreply_from_db)->gt($lastreply_c) or !$is_customer && $is_last_is_admin)?'admin':'customer';
		$this->assertEquals('customer',$res2);
	}
}
