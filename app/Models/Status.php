<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Status extends Model
{
    public $timestamps=False;
    protected $fillable = ['name'];

	/**
	 * @param $value
	 * @return string in lower case
	 */
	public function setNameAttribute($value)
	{
		return $this->attributes['name']=strtolower($value);
    }
}
