<?php

return [
	'secom' => [
		'ext_flags'=>[],
		'identifier' => '1PqGZbYutTZgTnqYZ8TehVKKQRbtMTeU',
		'secret' => 'XbPhACeqzakJgmlXwJJyxSf73nFzIdv7',
		'time_correction'=>0,
		'url' => 'https://secom.com.ua/billing/includes/api.php',
	],
	'adminvps' => [
		'ext_flags'=>[44,55],
		'identifier' => 'FVZXOH8VNAY5jF0scWH71GMakwQJVYDz',
		'secret' => 'ZfpQgCvDsVAP5igHeWM5xgX1slpzXzmg',
		'time_correction'=>-3600,
		'url' => 'https://my.adminvps.ru/includes/api.php',
	],
	'hostiman' => [
		'days'=>7,
		'ext_flags'=>[4,1,9],
		'identifier' => 'golovov',
		'secret' => md5('lks67FGr56f'),
		'ticketids'=>[73724,73296,73298,73297],
		'time_correction'=>-3600,
		'url' => 'https://cp.hostiman.ru/includes/api.php',
	],
	'ua-hosting' => [
		'ext_flags'=>[],
		'identifier' => 'secom_vi',
		'secret' => md5('lDGMboTBTqeL2q3F'),
		'time_correction'=>0,
		'url' => 'https://billing.ua-hosting.company/includes/api.php',
	],
	'coopertino' => [
		'home' => 'coopertino',
		'identifier' => 'r.wayne',
		'secret' => '7!gL62TS4i',
		'url' => 'https://my.coopertino.ru:1500/billmgr?authinfo=%2$s:%3$s&out=%1$s&func=ticket',
	],
	'skt' => [
		'home' => 'skt',
		'identifier' => 'r.wayne',
		'secret' => 'eC%!nhp96g',
		'url' => 'https://my.skt.ru/billmgr?authinfo=%2$s:%3$s&out=%1$s&func=ticket',
	],
];
