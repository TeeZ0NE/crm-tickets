<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    public $timestamps = False;
    protected $fillable = ['deadline'];

    public function getMaxDeadline(){
    	return $this->all()->max('deadline');
    }
}
