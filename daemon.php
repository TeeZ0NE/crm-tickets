<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
	$request = Illuminate\Http\Request::capture()
);


use App\Http\TicketBags\Secom;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class Daemon
{
	use \App\Http\TicketBags\MotherDaemon;
}

$pathOfTicketBags = config('services_arr.path');
$service_model = new Service();
# getting all Services which are equivalents 2 classes in app/Http/TicketBags/
try {
	$services = count($service_model->getServices()) ? $service_model->getServices() : array('Secom');
} catch (ModelNotFoundException $mnf) {
	$services = array('Secom');
	Log::error('Services not found!. Using default->Secom');
}
# concatenate path to class and service class

for ($i = 0; $i < count($services); $i++) {
	$Daemon = new Daemon($services[$i]);
	$tickets = $Daemon->getTicketsFromService();
	$Daemon->storeData($tickets);
}

/*
 * 	$serviceClass = $pathOfTicketBags . $services[0];
$secom = new $serviceClass;
try {
	$tickets = $secom->getListTikets();
	if($tickets['result']=='error') throw new Exception($tickets['message']);
	print_r($tickets);
//	$ticket = $secom->getTiket(8530);
//	print_r($ticket);
}
catch(Exception $e) {
	echo "error ".$e->getMessage();
}
*/

