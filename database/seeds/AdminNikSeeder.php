<?php

use Illuminate\Database\Seeder;
use App\Models\AdminNik;

class AdminNikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = ['Tolyan'=>['service_id'=>2,'user_id'=>2],'teez0ne'=>['service_id'=>4,'user_id'=>12]];
        foreach ($admins as $admin=>$data){
        	$adminNik_m = new AdminNik();
        	$adminNik_m->admin_nik = $admin;
        	$adminNik_m->service_id = $data['service_id'];
        	$adminNik_m->user_id = $data['user_id'];
        	$adminNik_m->save();
        }
    }
}
