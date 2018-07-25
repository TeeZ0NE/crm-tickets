<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session as Sess;

class Session extends Model
{
    protected $fillable = ['is_boss'];
    public $timestamps = False;


}
