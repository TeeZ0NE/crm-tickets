<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClosedTicket extends Model
{
    protected $fillable = array('c_id', 'ticket_id','subject','service_id', 'status_id', 'priority_id', 'reply_count', 'compl', 'lastreply','lastreply_is_admin');
}
