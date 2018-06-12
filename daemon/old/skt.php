<?
error_reporting(0);

$url = 'https://skt.ru/manager/billmgr?authinfo=techmonitoring:BaEC3LMGci&out=xml&func=tickets&a&sok=ok';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$data = curl_exec($ch);
curl_close($ch);


$obj = SimpleXML_Load_String($data);
$obj3 = json_decode( json_encode($obj), 1 );


$ticketarray=$obj3['elem'];
$num=0;
// PARSE OUT SOME ATTRIBUTES
foreach ($ticketarray as $obj2)
	{
		$timearr=explode("+", $obj2['delay']);
		$timearr2=explode(":", $timearr[1]);
		if ( $timearr2[0] > 2 ) {
			$ahtung="'";
			}
		$num=$num+1;
	}



//echo '<pre>';
//print_r($ticketarray);
//echo '</pre>';

if ($ticketarray["project"] != "")
{
$ticketid=$ticketarray["id"];
//$newtickets["id"]=$value["id"];
$newtickets[$ticketid]["subject"]=$ticketarray["subject"];
$newtickets[$ticketid]["replier"]=$ticketarray["account"];
$newtickets[$ticketid]["datelast"]=$ticketarray["datelast"];
$newtickets[$ticketid]["department"]="SKT";


}
else
{
foreach ($ticketarray as $value) {
$ticketid=$value["id"];
//$newtickets["id"]=$value["id"];
$newtickets[$ticketid]["subject"]=$value["subject"];
$newtickets[$ticketid]["replier"]=$value["account"];
$newtickets[$ticketid]["datelast"]=$value["datelast"];
$newtickets[$ticketid]["department"]="SKT";
}

}

//echo '<pre>';
//print_r($newtickets);
echo json_encode($newtickets);

?>
