<?php
define('LARAVEL_START', microtime(true));

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
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

class WhmcsDaemonLite
{
	use \App\Http\TicketBags\MotherWhmcsDaemonLite;
}
class BillManager{
	use \App\Http\TicketBags\Billmgr;
}

# take array of services and loop it to take data
# For debugging use public method getTicketsFromService;
$whmcs_services = (array)config('services_arr.whmcs_services');
foreach ($whmcs_services as $whmcs_service){
	$WhmcsLite = new WhmcsDaemonLite($whmcs_service);
	$WhmcsLite->getandStoreDataFromTicket();
	flush();
}
$billMgr_services = (array)config('services_arr.billmgr_services');
foreach ($billMgr_services as $billMgr_service){
	$billMgr = new BillManager($billMgr_service);
	$billMgr->getAndStoreDataFromTicket();
	flush();
}

/*
$service = 'hostiman';
$secom = new Whmcsapi($service);
print_r($secom->getListTikets());
//print_r($secom->getTiket());
*/

/*
# test ISPManager
//$url= 'https://my.skt.ru:1500/billmgr?authinfo=r.wayne:eC%!nhp96g&out=json&func=clientticket';

$user = 'r.wayne';//'techmonitoring';
$pass ='eC%!nhp96g'; //'BaEC3LMGci';
$format = 'json';
$url = sprintf('https://my.skt.ru/billmgr?authinfo=%2$s:%3$s&out=%1$s&func=ticket', $format, $user, $pass);

function getData(string $url) {

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$data = curl_exec($ch);
curl_close($ch);

return $data;
}
$elem = json_decode(getData($url),true);
//var_export($elem->doc->elem[0]->name['$']);
//var_export($elem['doc']['elem'][0]);
//var_export(file_get_contents($url));
print_r($elem);*/



