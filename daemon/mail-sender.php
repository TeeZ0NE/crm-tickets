<?php
define('LARAVEL_START', microtime(true));

require_once __DIR__ . './../vendor/autoload.php';
$app = require_once __DIR__ . './../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$compiledPath = __DIR__ . './../storage/framework/compiled.php';
if (file_exists($compiledPath)) {
	require $compiledPath;
}

use App\Http\Libs\MailingLib;

$ml = new MailingLib();
$ml->prepareMailing();

