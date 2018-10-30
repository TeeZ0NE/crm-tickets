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
