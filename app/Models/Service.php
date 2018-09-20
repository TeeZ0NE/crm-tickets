<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = False;
    protected $fillable = ['name','compl','href_link','is_available'];

	/**
	 * getting all services Ids
	 * @return array
	 */
	public function getServicesIds():array
	{
		return $this->orderBy('id')->pluck('id')->toArray();
    }

	public function getServiceId(string $service):int
	{
		return $this::firstOrCreate(['name'=>$service])->id;
    }

	public function getCompl(int $service_id):float
	{
		return $this->find($service_id)->compl;
    }

	public function getServiceName(int $service_id){
		return $this->find($service_id)->name;
	}
	/**
	 * Set available service
	 *
	 * @param int $service_id
	 * @param bool $is_available
	 */
	public function setServiceAvailable(int $service_id, bool $is_available){
		$service_m = new Service();
		$service_m::find($service_id)->update(['is_available'=>$is_available]);
	}
}
