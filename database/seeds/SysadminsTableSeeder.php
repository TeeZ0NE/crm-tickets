<?php

use Illuminate\Database\Seeder;
use App\Models\Sysadmin;

class SysadminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$sysadms = [
    		'Роман Кор', 'Юрий Кирил', 'Сергей Петрук','Дмитр Заверт','Вячеслав Голов','Игорь Рыжук','Никол Демч','Богдан Черн','Влад Гарб',
	    ];
    	foreach ($sysadms as $sysadm){
    		Sysadmin::insert(['name'=>$sysadm]);
	    }
    }
}
