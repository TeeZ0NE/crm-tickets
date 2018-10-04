<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Interval;

class IntervalTest extends TestCase
{
	public function setUp()
	{
		parent::setUp();
	}
	
	/**
	 * @test
	 */
	public function check_get_intervals()
	{
		$interval_m = new Interval();
		$this->assertNotEmpty($interval_m->getAllIntervals());
	}
}
