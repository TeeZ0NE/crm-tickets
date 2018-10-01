<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interval extends Model
{
	public $timestamps = False;
	protected $fillable = ['name','url_attr'];

	public function setNameAttribute($value)
	{
		return $this->attributes['name'] = strtolower($value);
	}

	public function setUrlAttrAttribute($value)
	{
		return $this->attributes['url_attr'] = strtolower($value);
	}

	public function getAllIntervals()
	{
		return $this::all();
	}
}
