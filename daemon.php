<?php
define('LARAVEL_START', microtime(true));
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);


use App\Http\TicketBags\Whmcsapi;

class WhmcsDaemon
{
	use \App\Http\TicketBags\MotherWhmcsDaemon;
}

# take array of services and loop it to take data
/*
$whmcs_services = (array)config('services_arr.whmcs_services');
foreach ($whmcs_services as $whmcs_service){
	$WhmcsDaemon = new WhmcsDaemon($whmcs_service);
	$tickets = $WhmcsDaemon->getTicketsFromService();
	if ($tickets==Null)continue;
	$WhmcsDaemon->storeData($tickets);
}
*/

$service = 'secom';
printf("sirvice %s\n",$service);
$secom = new Whmcsapi($service);
print_r($secom->getListTikets());
//print_r($secom->getTiket());

//$adminvps = new Whmcsapi('adminvps');
//print_r($adminvps->getListTikets());

//$hostiman = new Whmcsapi('hostiman');
//print_r($hostiman->getListTikets());
//
//$uahosting = new Whmcsapi('ua-hosting');
//print_r($uahosting->getListTikets());

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
var_export($elem['doc']['elem'][0]);
//var_export(file_get_contents($url));
*/
