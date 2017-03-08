<?php

include("api/SimpleApiClient.php");


	$request = new SimpleApiClient();
	
	$payload["pmID"] = 2;
	$payload["bookingID"] = 10378;
	$payload["amount"] = "42.50";
	$payload["text"] = "Paypal payment";
	$payload["paymentDate"] = date("Y-m-d h:i:s",time());
	$payload["createdDate"] = date("Y-m-d h:i:s",time());
	
	$request->payload = json_encode($payload);
	$request->requestTypePost()->endpoint('https://mike42.wrtcloud.com/api/v1.0/payments');
	$results = $request->perform();

print_r($results);

?>
