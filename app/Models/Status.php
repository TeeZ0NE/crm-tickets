<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Status extends Model
{
    public $timestamps=False;
    protected $fillable = array('name');

	/**
	 * @param string $status
	 * @return int|Null ID
	 */
	/*public function getId(string $status)
	{
		try{
			$res = $this->where('name',strtolower($status))->firstOrfail();
			return $res->id;
		}
		catch (ModelNotFoundException $mnf){
			return Null;
		}
    }*/

	/**
	 * @param $value
	 * @return string in lower case
	 */
	public function setNameAttribute($value)
	{
		return $this->attributes['name']=strtolower($value);
    }
}
