<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNik extends Model
{
    public $timestamps = False;
    protected $fillable = array('c_id','admin_nik');
	protected $table = 'sysadmin_niks';

    public function getRealAdmin(){
    	return $this->hasOne(Sysadmin::class,'id', 'admin_id');
    }
}
