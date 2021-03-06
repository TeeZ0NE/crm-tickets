<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 22.08.18
 * Time: 12:25
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('LARAVEL_START', microtime(true));
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$compiledPath = __DIR__.'/storage/framework/compiled.php';
if (file_exists($compiledPath))
{
	require $compiledPath;
}
/*
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);
*/
//print_r($_SERVER['REQUEST_METHOD']);
//header('Access-Control-Allow-Origin: *');
header('Pragma: no-cache');
//header('Content-type: text/plain');


echo 'Data received'.PHP_EOL;

$service = $_REQUEST['service']??'Unknown';
if (isset($_REQUEST['service'])){unset($_REQUEST['service']);}
if (!$_REQUEST || $service=='Unknown')die(404);
$get_stat_arr = [$_REQUEST];

class storeStatistics
{
	use \App\Http\TicketBags\StoreStatistic;
}
$storeStatistic = new storeStatistics($service,$get_stat_arr);
$storeStatistic->store();

$curr_date =  date('d.m.Y',time());
file_put_contents(
sprintf('./../storage/stats/%2$s_%1$s.stat.csv',$curr_date,$service),
	implode(';',$get_stat_arr[0]).PHP_EOL,FILE_APPEND|LOCK_EX
);
//echo ob_get_length();
//print_r(get_headers($_SERVER['HTTP_REFERER'],1));
