<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\{User,Ticket};
class AssignUser2TicketTest extends TestCase
{
	/**
	 * @test
	 */
	public function checkGetActiveUser()
	{
		$user_m = new User();
		$active = $user_m->getActiveAdmins()[0];
		$this->assertNotEmpty($active);
		return $active;
   }
   /**
    * @test
    */
	public function getTicketWithId()
	{
		$id = 2;
		$ticket = Ticket::find($id);
		$this->assertEquals(2,$ticket->id);
		return $ticket;
   }
   /**
    * @test
    * @depends checkGetActiveUser
    * @depends getTicketWithId
    * @param object $active_user
    * @param object $ticket
    */
	public function isUserInTicket($active_user,$ticket)
	{
		$this->assertTrue($ticket->user_assign_id==$active_user->id);
   }
}
