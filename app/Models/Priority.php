<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
	public $timestamps = False;
	protected $fillable = ['priority'];

	public function setPriorityAttribute(string $value){
		return $this->attributes['priority']=strtolower($value);
	}

	public function getPriorityId(string $value):int
	{
		return $this::firstOrCreate(['priority'=>$value])->id;
	}
}
