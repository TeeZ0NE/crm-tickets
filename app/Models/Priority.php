<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
	public $timestamps = False;
	protected $fillable = ['priority'];

	public function setPriorityAttribute($value){
		return $this->attributes['priority']=strtolower($value);
	}
}
