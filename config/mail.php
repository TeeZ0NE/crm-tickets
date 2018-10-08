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