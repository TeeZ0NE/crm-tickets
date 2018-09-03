<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 22.08.18
 * Time: 12:25
 */
define('LARAVEL_START', microtime(true));

require_once __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);

//print_r($_SERVER['REQUEST_METHOD']);
//header('Access-Control-Allow-Origin: *');
header('Pragma: no-cache');
//header('Content-type: text/plain');


echo 'Data received'.PHP_EOL;

$service = $_REQUEST['service']??'Unknown';
if (isset($_REQUEST['service'])){unset($_REQUEST['service']);}
$get_stat_arr = $_REQUEST;
//$curr_date =  date('d.m.Y',time());

/*file_put_contents(
	sprintf('./../storage/stats/%2$s_%1$s.stat.csv',$curr_date,$service),
	implode(';',$obj).PHP_EOL,FILE_APPEND|LOCK_EX
);*/

class storeStatistics
{
	use \App\Http\TicketBags\StoreStatistic;
}
$storeStatistic = new storeStatistics($service,$get_stat_arr);
$storeStatistic->store();

//echo ob_get_length();
//print_r(get_headers($_SERVER['HTTP_REFERER'],1));
