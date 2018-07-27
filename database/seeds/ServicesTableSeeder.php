<?php

use Illuminate\Database\Seeder;
use App\Models\Service;
class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$services = ['secom','adminvps','hostiman','ua-hosting'];
    	foreach ($services as $service){
		    $service_m = new Service();
		    $service_m->name = $service;
		    $service_m->save();
	    }
    }
}
