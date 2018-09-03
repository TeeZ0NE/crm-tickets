<?php
/**
 * Created by PhpStorm.
 * User: teez0ne
 * Date: 22.08.18
 * Time: 12:25
 */

//print_r($_SERVER['REQUEST_METHOD']);
//header('Access-Control-Allow-Origin: *');
header('Pragma: no-cache');
header('Content-type: text/plain');
$service = $_REQUEST['service']??'test';
//if (isset($_REQUEST['service])){unset($_REQUEST['service']);}
$obj = $_REQUEST;
$curr_date =  date('d.m.Y',time());

file_put_contents(
	sprintf('./../storage/stats/%2$s_%1$s.stat.csv',$curr_date,$service),
	implode(';',$obj).PHP_EOL,FILE_APPEND|LOCK_EX
);
echo "ok skript";
printf('service %s',$service);
print_r($obj);
//echo ob_get_length();
//print_r(get_headers($_SERVER['HTTP_REFERER'],1));
