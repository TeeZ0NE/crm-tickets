<?

error_reporting(0);
$url = "https://billing.ua-hosting.company/includes/api.php"; # URL sto WHMCS API file
$username = "secom_vi"; # Admin username goes here
$password = "lDGMboTBTqeL2q3F"; # Admin password goes here




$postfields["username"] = $username;
$postfields["password"] = md5($password);
$postfields["action"] = "gettickets";
$postfields["responsetype"] = "xml";
$postfields["limitnum"] = "500";
$postfields["deptid"]= "2";
$postfields["status"] = 'Awaiting Reply';

$query_string = "";
foreach ($postfields AS $k=>$v) $query_string .= "$k=".urlencode($v)."&";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_SSLVERSION,2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
$xml = curl_exec($ch);
//echo "<pre>";
//echo $xml;

//$obj = SimpleXML_Load_String($xml);
//print_r($obj);
//$obj3 = json_decode( json_encode($obj), 1 );
//$params=$obj["tickets"]["ticket"];
//print_r($params);

$xml2 = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml2);
$array = json_decode($json,TRUE);
//echo "<pre>";
//print_r($array);
//echo $array["tickets"]["ticket"]['status'];
//print_r($array["tickets"]["ticket"]["name"]);
if ($array["tickets"]["ticket"]['status']  == "Open" || $array["tickets"]["ticket"]['status'] == "Customer-Reply" ||  $array["tickets"]["ticket"]['status'] == "In Progress" || $array["tickets"]["ticket"]['status'])
{

                $newDate = strtotime("+3 hours", strtotime($array["tickets"]["ticket"]['lastreply']));
                $newDate = date('Y-m-d H:i:s',$newDate);
$ticketid=$array["tickets"]["ticket"]["id"];
$newtickets[$ticketid]["subject"]=$array["tickets"]["ticket"]["subject"];
//$newtickets[$ticketid]["replier"]=$array["tickets"]["ticket"]["name"];
$newtickets[$ticketid]["datelast"]=$newDate;
$newtickets[$ticketid]["department"]="ua-hosting";

$postfields["username"] = $username;
$postfields["password"] = md5($password);
$postfields["action"] = "getticket";
$postfields["responsetype"] = "xml";
$postfields['ticketid'] = $ticketid;
$query_string = "";
foreach ($postfields AS $k=>$v) $query_string .= "$k=".urlencode($v)."&";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
$xml = curl_exec($ch);
$xml2 = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml2);


$array = json_decode($json,TRUE);

$params=$array["replies"]["reply"];
$end_element = array_pop($params);
$adminname=$end_element["admin"];
if ($params["name"] != "")
{
$newtickets[$ticketid]["replier"]=$params["name"];
}
else
{
        if (is_array($adminname))
        {
        $newtickets[$ticketid]["replier"]=$end_element["name"];
        }
        else
        {
        $newtickets[$ticketid]["replier"]="[".$end_element["admin"]."]";
        }
}

}
else
{
$params=$array["tickets"]["ticket"];
foreach ($params as $params2)
{
//	echo "<pre>";
//        print_r($params2);
        if ($params2['status'] == "Open" || $params2['status'] == "Customer-Reply" || $params2['status'] == "In Progress" || $params2['status'] == "On Hold")
	{
//	if ($params2['FLAG'] != "1")
//print_r($params2);
//                {
                $num=$num+1;
                $newDate = strtotime("+3 hours", strtotime($params2['lastreply']));
		$newDate = date('Y-m-d H:i:s',$newDate);
//print_r($params2);



$ticketid=$params2["id"];
$newtickets[$ticketid]["subject"]=$params2["subject"];
$newtickets[$ticketid]["replier"]=$params2["name"];
$newtickets[$ticketid]["datelast"]=$newDate;
$newtickets[$ticketid]["department"]="ua-hosting";



$postfields["username"] = $username;
$postfields["password"] = md5($password);
$postfields["action"] = "getticket";
$postfields["responsetype"] = "xml";
$postfields['ticketid'] = $params2["id"];
$query_string = "";
foreach ($postfields AS $k=>$v) $query_string .= "$k=".urlencode($v)."&";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
$xml = curl_exec($ch);
$xml2 = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
$json = json_encode($xml2);


$array = json_decode($json,TRUE);

$params=$array["replies"]["reply"];
$end_element = array_pop($params);
$adminname=$end_element["admin"];
if ($params["name"] != "")
{
$newtickets[$ticketid]["replier"]=$params["name"];
}
else
{
        if (is_array($adminname))
        {
        $newtickets[$ticketid]["replier"]=$end_element["name"];
        }
        else
        {
        $newtickets[$ticketid]["replier"]="[".$end_element["admin"]."]";
        }
}
}


}
}
//echo '<pre>';
//print_r($newtickets);
echo json_encode($newtickets);

//                }



//}
?>
