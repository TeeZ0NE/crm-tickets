<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysadminActivity extends Model
{
    public $timestamps = False;
    protected $fillable = array('admin_nik_id', 'ticketid', 'replies', 'c_id', 'lastreply');

	public function getTicket()
	{
		return $this->hasOne(Ticket::class, 'ticketid', 'ticketid');
	}

	public function getClient()
	{
		return $this->hasOne(Client::class,'id','c_id');
	}

	public function getNik()
	{
		return $this->hasOne(AdminNik::class,'admin_nik_id', 'admin_nik_id');
	}
}
