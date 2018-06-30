<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysadminActivity extends Model
{
    public $timestamps = False;
    protected $fillable = array('admin_nik_id', 'ticketid', 'replies', 'c_id', 'lastreply');
}
