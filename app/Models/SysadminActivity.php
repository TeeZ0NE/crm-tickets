<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysadminActivity extends Model
{
    public $timestamps = False;
    protected $fillable = ['admin_nik_id', 'ticket_id', 'replies', 'lastreply'];

	public function getTickets()
	{
		return $this->hasMany(Ticket::class,'id','ticket_id');
    }

	public function getSysadmins()
	{
		return $this->hasManyThrough(Sysadmin::class,AdminNik::class,'admin_nik_id','id','admin_nik_id', 'admin_id');
    }



}
