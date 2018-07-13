<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);


use App\Http\TicketBags\Secom;
use App\Http\TicketBags\Whmcsapi;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class WhmcsDaemon
{
	use \App\Http\TicketBags\MotherWhmcsDaemon;
}
/*
$pathOfTicketBags = config('services_arr.path');
$service_model = new Service();
# getting all Services which are equivalents 2 classes in app/Http/TicketBags/
try {
	$services = count($service_model->getServicesNames()) ? $service_model->getServicesNames() : array('Secom');
} catch (ModelNotFoundException $mnf) {
	$services = array('Secom');
	Log::error('Services not found!. Using default->Secom');
}
*/

# take array of services and loop it to take data

$whmcs_services = (array)config('services_arr.whmcs_services');
foreach ($whmcs_services as $whmcs_service){
	$WhmcsDaemon = new WhmcsDaemon($whmcs_service);
	$tickets = $WhmcsDaemon->getTicketsFromService();
	if ($tickets==Null)continue;
	$WhmcsDaemon->storeData($tickets);
}

/*
$secom = new Whmcsapi('adminvps');
//print_r($secom->getListTikets());
print_r($secom->getTiket(88535));
*/
//$adminvps = new Whmcsapi('adminvps');
//print_r($adminvps->getListTikets());

//$hostiman = new Whmcsapi('hostiman');
//print_r($hostiman->getListTikets());
//
//$uahosting = new Whmcsapi('ua-hosting');
//print_r($uahosting->getListTikets());