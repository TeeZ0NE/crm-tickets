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

$whmcs_services = (array)config('services_arr.whmcs_services');
foreach ($whmcs_services as $whmcs_service){
	$Whmcs = new App\Http\TicketBags\Whmcsapi($whmcs_service);
	printf("Service: %s\n",$whmcs_service);
	print_r($Whmcs->getListTikets());
}
