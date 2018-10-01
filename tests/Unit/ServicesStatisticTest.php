<?php

namespace Tests\Unit;

use App\Mail\ServiceStatistic;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Service;
use Illuminate\Support\Facades\Mail;

class ServicesStatisticTest extends TestCase
{
	private $service_m;
	public function setUp()
	{
		parent::setUp();
		$this->service_m = new Service();
	}
    /**
     * @test
     */
	public function check_instance()
	{
		$this->assertInstanceOf(Service::class,$this->service_m);
    }

    /**
     * @test
     */
	public function check_result_statistic_service_by_yesterday()
	{
		$this->assertNotEmpty($this->service_m->getStatisticYesterday(4));
    }
    /**
     * @test
     */
	public function check_get_all_services()
	{
		$this->assertNotEmpty($this->service_m->getAllServices());
    }
    /**
     * @test-
     */
	public function check_get_summ_and_count()
	{
	$this->assertNotEmpty($this->service_m->getCountTicketsAndSumTimeYesterday(4))	;
    }

    /**
     * @test-
     */
	public function check_get_statistic_today()
	{
		$this->assertNotEmpty($this->service_m->getStatisticToday(4));
    }
	/**
	 * @test-
	 */
	public function check_get_summ_and_count_today()
	{
		$this->assertNotEmpty($this->service_m->getCountTicketsAndSumTimeToday(4))	;
	}
	/**
	 * @test-
	 */
	public function check_get_statistic_start_of_month()
	{
		$this->assertNotEmpty($this->service_m->getStatisticstartofmonth(4));
	}
	/**
	 * @test-
	 */
	public function check_get_summ_and_count_startofmonth()
	{
		$this->assertNotEmpty($this->service_m->getCountTicketsAndSumTimestartofmonth(4))	;
	}
	/**
	 * @test-
	 */
	public function check_get_statistic_prev_month()
	{
		$this->assertNotEmpty($this->service_m->getStatisticprevmonth(4));
	}
	/**
	 * @test-
	 */
	public function check_get_summ_and_count_prev_fmonth()
	{
		$this->assertNotEmpty($this->service_m->getCountTicketsAndSumTimeprevmonth(4))	;
	}
	/**
	 * @test
	 */
	public function try_send_email()
	{
			Mail::to('vadim@hyperweb.com.ua')->send(new ServiceStatistic('ua-hosting', 'start_of_month'));
			$this->assertTrue(true);
	}
}
