<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = False;
    protected $fillable = array('name');

	/**
	 * get all existing services
	 * @return array
	 */
    public function getServices(){
    	return $this->pluck('name')->toArray();
    }

}
