<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 25.07.18
 * Time: 18:54
 */
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);
use Carbon\Carbon;
class getStatistics
{
	use \App\Http\TicketBags\GetStatistics;
}
class storeStatistics
{
	use \App\Http\TicketBags\StoreStatistic;
}

$files = ["adminvps" => 'http://91.235.128.62/adminvps/stat/adminvps_%s.stat.csv'];
$yesterday = Carbon::now()->yesterday()->format('d.m.Y');
foreach ($files as $service => $file) {
	$file_name = sprintf($file, $yesterday);
	$getStatistics = new getStatistics();
	print_r($getStatistics->getStatistic($service,$file_name));
	$storeStatistic = new storeStatistics();
}



