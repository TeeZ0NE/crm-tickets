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

	public function getServiceId(string $service):int
	{
		return $this->where('name',$service)->first()->id;
    }

	public function getCompl(int $service_id):float
	{
		return $this->find($service_id)->first()->compl;
    }
}
