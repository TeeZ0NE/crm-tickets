<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysadminActivity extends Model
{
    public $timestamps = False;
    protected $fillable = ['sysadmin_niks_id', 'ticket_id', 'replies', 'lastreply'];

	public function getTickets()
	{
		return $this->hasMany(Ticket::class,'id','ticket_id');
    }

	public function getSysadmins()
	{
		return $this->hasManyThrough(User::class,AdminNik::class,'sysadmin_niks_id','id','sysadmin_niks_id', 'user_id');
    }

	public function getSysAdminsPivot()
	{
		return $this->belongsToMany(User::class,'sysadmin_niks','user_id','sysadmin_niks_id');
    }


}
