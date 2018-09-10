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
		$services = [
			'secom' => 'https://secom.com.ua/billing/admin/supporttickets.php?action=viewticket&id=',
			'adminvps' => 'https://my.adminvps.ru/admi/supporttickets.php?action=view&id=',
			'hostiman' => 'https://cp.hostiman.ru/admins/supporttickets.php?action=view&id=',
			'ua-hosting' => 'https://billing.ua-hosting.company/admins/supporttickets.php?action=view&id=',
			'skt' => 'https://skt.ru/manager/billmgr?func=desktop&startpage=tickets&startform=tickets.edit&elid=',
			'coopertino' => 'https://coopertino.ru:1500/billmgr?func=desktop&startpage=tickets&startform=tickets.edit&elid='
		];
		foreach ($services as $service => $link) {
			$service_m = new Service();
			$service_m->name = $service;
			$service_m->href_link = $link;
			$service_m->save();
		}
	}
}
