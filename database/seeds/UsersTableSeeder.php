<?php

use Illuminate\Database\Seeder;
use App\Models\{User,Boss};

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $sysadmins = [
		    'Роман Кор', 'Юрий Кирил', 'Сергей Петрук','Дмитр Заверт','Вячеслав Голов','Игорь Рыжук','Никол Демч','Богдан Черн','Влад Гарб',
	    ];
	    $i=1;
	    $email = 'admin_%d@crm.com';
	foreach ($sysadmins as &$sysadmin) {
		$user_sysadm = new User;
		$user_sysadm->name = $sysadmin;
		$user_sysadm->email = sprintf($email,$i++);
		$user_sysadm->password = bcrypt('111111');
		$user_sysadm->save();
	}
		$user_boss = new Boss();
		$user_boss->name = "TeeZ0NE";
		$user_boss->email = "teez0ne@crm.com";
		$user_boss->password = bcrypt('111111');
		$user_boss->save();
    }
}
