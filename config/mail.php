<?php

return
	array("driver" => "smtp",
		"host" => "atlas.secom.com.ua",
		"port" => 465,
		"encryption" => "ssl",
		"from" => array(
			"address" => "admin-area@secom.com.ua",
			"name" => "Admin admin-area"
		),
		"username" => "admin-area@secom.com.ua",
		"password" => "moqC7Qb2f8616AfZ",
		"sendmail" => "/usr/sbin/sendmail -bs",
		"pretend" => false,
		'markdown' => [
			'theme' => 'default1',
			'paths' => [
				resource_path('views/vendor/mail'),
			],]);
/*array(
"driver" => "smtp",
"host" => "smtp.ukr.net",
"port" => 2525,
"encryption"=>"ssl",
"from" => array(
	"address" => "endnet@ukr.net",
	"name" => "Admin admin-area"
),
"username" => "endnet@ukr.net",
"password" => "ZW5kbmV0QHVrci5uZXQ=",
"sendmail" => "/usr/sbin/sendmail -bs",
"pretend" => false,
'markdown' => [
	'theme' => 'default1',
	'paths' => [
		resource_path('views/vendor/mail'),
	],
],
);*/
/* mailtrap
return array(
	"driver" => "smtp",
	"host" => "smtp.mailtrap.io",
	"port" => 2525,
	"from" => array(
		"address" => "from@example.com",
		"name" => "Example"
	),
	"username" => "02656fffd33958",
	"password" => "2120d5a8b5e125",
	"sendmail" => "/usr/sbin/sendmail -bs",
	"pretend" => false
);
*/
