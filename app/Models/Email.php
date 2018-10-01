<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
	public $timestamps = False;
	protected $fillable = ['email'];
}
