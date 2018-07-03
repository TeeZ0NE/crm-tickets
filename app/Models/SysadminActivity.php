<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysadminActivity extends Model
{
    public $timestamps = False;
    protected $fillable = array('admin_nik_id', 'ticket_id', 'replies', 'lastreply');
}
