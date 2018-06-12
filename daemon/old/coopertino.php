<?

error_reporting(0);
$url = 'https://coopertino.ru:1500/billmgr?authinfo=techstaff:MkXv9PoJf9EN4LLwv4gy&out=xml&func=tickets&a&sok=ok';


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
if ( $ticketarray[project] == 'Coopertino.ru' )
	{
		$num=1;
	}
	else
	{
foreach ($ticketarray as $obj2)
	{
		$timearr=explode("+", $obj2['delay']);
		$timearr2=explode(":", $timearr[1]);
		if ( $timearr2[0] > 2 ) {
			$ahtung="'";
			}
		$num=$num+1;
	}
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
$newtickets[$ticketid]["department"]="coopertino";
}

$url='https://coopertino.ru:1500/billmgr?authinfo=techstaff:MkXv9PoJf9EN4LLwv4gy&out=xml&func=tickets.edit&elid='.$ticketid.'&sok=ok';


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
preg_match_all("'<div class=\'support-from\'>(From: )(.*?)( - \d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d)</div>'si", $obj3['hist'], $match);
$end_element = array_pop($match['2']);
$newtickets[$ticketid]["replier"]=$end_element;

}
//echo '<pre>';
//print_r($newtickets);
echo json_encode($newtickets);

?>
