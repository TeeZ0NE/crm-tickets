<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = False;
    protected $fillable = ['name','compl'];

	/**
	 * getting all services Ids
	 * @return array
	 */
	public function getServicesIds():array
	{
		return $this->pluck('id')->toArray();
    }
}
