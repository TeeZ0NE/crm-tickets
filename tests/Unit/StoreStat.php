<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 03.09.18
 * Time: 18:13
 */
use PHPUnit\Framework\TestCase;
class storeStatistics
{
	use \App\Http\TicketBags\StoreStatistic;
}
class StoreStatTest extends TestCase{
//	use \App\Http\TicketBags\StoreStatistic;
	/**
	 * @test
	 */
	public function setService()
	{
		$this->assertTrue(true);
		return $service = "foxer1";
}

	/**
	 * @test
	 */
	public function setStatArr()
	{
		$this->assertTrue(true);
		return $get_stat_arr = [
			"lastreply" => "03.09.2018 18:10:37",
			"time_uses" => 5,
			"subject" => "ticket_num_11664",
			"admin" => "Vadim Kovalchuk",
			"ticketid" => 11664
		];
	}
	/**
	 * @depends setService
	 * @depends setStatArr
	 */
	public function testStore($service,$get_stat_arr)
	{

		$this->assertInternalType('string',$service);
//		$this->assertInternalType('array',$get_stat_arr);
//		$this->assertCount(5,$get_stat_arr);
//		$storeStat = new storeStatistics($service,$get_stat_arr);
//		$this->assertInstanceOf(storeStatistics::class,$storeStat);
	}
}