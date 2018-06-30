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
	$services = count($service_model->getServices())?$service_model->getServices():array('Secom');
} catch (ModelNotFoundException $mnf) {
	$services = array('Secom');
	Log::error('Services not found!. Using default->Secom');
}
# concatenate path to class and service class
$serviceClass = $pathOfTicketBags . $services[0];
/*
$Daemon = new Daemon($services[0]);
$tickets = $Daemon->getTicketsFromService();
$Daemon->storeData($tickets);
*/

$secom = new $serviceClass;
print_r($secom->getListTikets());
$ticket = $secom->getTiket(8530);
print_r($ticket);

