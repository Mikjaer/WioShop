<?php

// Verifying IPN

$wio_config = yaml_parse(file_get_contents(plugin_dir_path(__FILE__)."/config.yaml"));

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://ipnpb.paypal.com/cgi-bin/webscr");
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&".http_build_query($_POST));
curl_setopt($ch,CURLOPT_USERAGENT,'PHP-IPN-VerificationScript');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

if ($server_output == "VERIFIED")
{
	$request = new SimpleApiClient();
	
	$payload["pmID"] = 2;
	$payload["bookingID"] = $_REQUEST["item_number"];;
	$payload["amount"] = $_REQUEST["mc_gross"];
	$payload["text"] = "Paypal payment";
	$payload["paymentDate"] = date("Y-m-d h:i:s",time());
	$payload["createdDate"] = date("Y-m-d h:i:s",time());

	$request->payload = json_encode($payload);
	$request->endpoint($wio_config["global"]["endpoint"].'payments');
	$request->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
	$request->requestTypePost();
	$results = $request->perform();

}
?>
