<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = False;
    protected $fillable = array('name','compl');

	/**
	 * get all name of  existing services
	 * using in daemon when getting all services 4 recursion
	 * @return array
	 */
    public function getServicesNames(){
    	return $this->pluck('name')->toArray();
    }

	/**
	 * getting all services Ids
	 * @return array
	 */
	public function getServicesIds()
	{
		return $this->pluck('id')->toArray();
    }
}
