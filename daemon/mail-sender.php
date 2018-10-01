<?php
define('LARAVEL_START', microtime(true));

require_once __DIR__ . './../vendor/autoload.php';
$app = require_once __DIR__ . './../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$compiledPath = __DIR__ . './../storage/framework/compiled.php';
if (file_exists($compiledPath)) {
	require $compiledPath;
}

use Illuminate\Support\Facades\Mail;
use App\Mail\ServiceStatistic;
$mail = new Mail();
$mail::to('vadim@hyperweb.com.ua')->
send(new ServiceStatistic('ua-hosting', 'start_of_month'));

