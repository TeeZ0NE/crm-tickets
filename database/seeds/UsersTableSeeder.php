<?php

use Illuminate\Database\Seeder;
use App\Models\{
	User, Boss
};

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
			'Королев Р.' => ['email' => 'roma@secom.com.ua', 'password' => 'bCqwZmWWpzVK'],
			'Петрук С.' => ['email' => 'sergiy.petruk@secom.com.ua', 'password' => 'I8gIX4DDgJhh'],
			'Кирилюк Ю.' => ['email' => 'y.kurulyuk@secom.com.ua', 'password' => '0fQqzVm1BHFZ'],
			'Демчук Н.' => ['email' => 'n.demchuk@secom.com.ua', 'password' => 'BTXvNI0lK65i'],
			'Завертаный Д.' => ['email' => 'd.zavertany@secom.com.ua', 'password' => '1nnKsaVjQxW9'],
			'Гарболинский В.' => ['email' => 'v.harbo@secom.com.ua', 'password' => 'WLdwOPJvEWVx'],
			'Рыжук И.' => ['email' => 'i.ryzhuk@secom.com.ua', 'password' => '3LF9KqU3AKMd'],
			'Чемерыс Б.' => ['email' => 'bogdan@secom.com.ua', 'password' => 'AWItkITsjvsV'],
			'Василенко С.' => ['email' => 'sergiy.v@secom.com.ua', 'password' => 'LAz6V2DLmf7c'],
			'Матущак Д.' => ['email' => 'd.matuschak@secom.com.ua', 'password' => 'ezMhDbnwBzmZ'],
			'Дединщук В.' => ['email' => 'viktor.d@secom.com.ua', 'password' => 'nl6slDyAedsh'],
			'Лашин А.' => ['email' => 'aristarh@secom.com.ua', 'password' => 'dkLLEsSmfloM'],
		];
		$bosses = [
			'Teez0ne' => ['email' => 'vadim@hyperweb.com.ua', 'password' => 'TEEZ0NE'],
			'Королев Р.' => ['email' => 'roma@secom.com.ua', 'password' => 'bCqwZmWWpzVK'],
			'Петрук С.' => ['email' => 'sergiy.petruk@secom.com.ua', 'password' => 'I8gIX4DDgJhh'],
		];
		foreach ($sysadmins as $sysadmin => $data) {
			$user_sysadm = new User;
			$user_sysadm->name = $sysadmin;
			$user_sysadm->email = $data['email'];
			$user_sysadm->password = bcrypt($data['password']);
			$user_sysadm->save();
		}
		foreach ($bosses as $boss => $data) {
			$user_boss = new Boss();
			$user_boss->name = $boss;
			$user_boss->email = $data['email'];
			$user_boss->password = bcrypt($data['password']);
			$user_boss->save();
		}
	}
}
