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
        $user_sysadm = new User;
		$user_sysadm->name = 'Юрий Кирил';
		$user_sysadm->email = 'yura_kiril@crm.com';
		$user_sysadm->password = bcrypt('111111');
		$user_sysadm->save();

		$user_boss = new Boss();
		$user_boss->name = "TeeZ0NE";
		$user_boss->email = "teez0ne@crm.com";
		$user_boss->password = bcrypt('111111');
		$user_boss->save();
    }
}
