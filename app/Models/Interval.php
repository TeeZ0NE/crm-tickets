<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interval extends Model
{
	public $timestamps = False;
	protected $fillable = ['name','url_attr'];
}
