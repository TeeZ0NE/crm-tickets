<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
			'subject' => 'ticket_num_11664',
			'admin' => 'Vadim Kovalchuk',
			'ticketid' => 11664]];
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
		$this->assertArrayHasKey('ticketid',$this->request_arr);
	}
}
