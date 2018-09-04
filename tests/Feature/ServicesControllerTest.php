<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 04.09.18
 * Time: 10:45
 */

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Boss\ServicesController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServicesControllerTest extends TestCase
{

	public function ptestCreate()
	{
		$response = $this->get('boss/services/create');
		$response->assertStatus(200);
	}

	public function testServiceCreate()
	{

	}
}
