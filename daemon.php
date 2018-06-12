<?php
define('LARAVEL_START', microtime(true));

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';


$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

require_once 'daemon/Secom.php';

$secom = new Secom();

while (true) {
    print_r($secom->getListTikets());

    sleep(10);
}