<?php
	global $wio_config;
	$request = new SimpleApiClient();
	$request->endpoint($wio_config["global"]["endpoint"]. 'customers?filter[]=equals(email,"'.$_REQUEST["email"].'")');
	$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
	
	$results = $request->perform();
	if (isset($results[0]))
	{
#		print "Account with ".$_REQUEST["email"]." allready exists\n";
		if (!$results[0]["id"] == $_SESSION["authed"]["id"])
		{
	#		die("Security error");
			// Existing user but not logged in, should not happend 
		} else {
#			print "Login verified";
		}
	} else {
#		print "Create customer<br>";

		$request = new SimpleApiClient();
		
		
		$payload["type"] = "person";
		$payload["paymentMethodID"] = "1";
		$payload["firstname"] = $_REQUEST["fornavn"];
		$payload["lastname"] = $_REQUEST["efternavn"];
		$payload["address"] = $_REQUEST["adresse1"]."  ".$_REQUEST["adresse2"];
		$payload["address1"] = $_REQUEST["adresse1"];
		$payload["address2"] = $_REQUEST["adresse2"];
		$payload["zip"] = $_REQUEST["postnummer"];
		$payload["city"] = $_REQUEST["by"];
		
		$payload["email"] = $_REQUEST["email"];
		$payload["invoice_email"] = $_REQUEST["email"];
		$payload["phone"] = $_REQUEST["tlf"];

		if ($_REQUEST["cell"] == "")
			$_REQUEST["cell"] = $_REQUEST["tlf"];

		$payload["mobile"] = $_REQUEST["cell"];
	

		$payload["deliveryName"] = $_REQUEST["deliveryName"];
	
		$payload["deliveryAddress"] = $_REQUEST["deliveryAddress1"];
		$payload["deliveryAddress1"] = $_REQUEST["deliveryAddress2"];
		$payload["deliveryZip"] = $_REQUEST["deliveryZip"];
		$payload["deliveryCity"] = $_REQUEST["deliveryCity"];
		$request->payload = json_encode($payload);
		$request->requestTypePost();
		$request->endpoint($wio_config["global"]["endpoint"]. 'customers/');
		$request->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
		$r=$request->perform();
		
		// Login

		$request = new SimpleApiClient();
		$request->endpoint($wio_config["global"]["endpoint"]. 'customers?filter[]=equals(email,"'.$_REQUEST["email"].'")');

		$request->requestTypeGet();
		$request->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
		$results = $request->perform();

		$_SESSION["authed"] = $results[0];	

		// Perform order
	}

// bookingStart 12:00 bookingEnd 11:00
	global $wio_config;
	#print_r($_SESSION);

	$request = new SimpleApiClient();
	
	$payload["customerID"] = $_SESSION["authed"]["id"];
	$payload["deliveryName"] = $_SESSION["authed"]["deliveryName"];;
	$payload["deliveryAddress"] = "A:".$_SESSION["authed"]["deliveryAddress"];
	$payload["deliveryAddress1"] = "B:".$_SESSION["authed"]["deliveryAddress1"];
	$payload["deliveryZip"] = $_SESSION["authed"]["deliveryZip"];
	$payload["deliveryCity"] = $_SESSION["authed"]["deliveryCity"];
	$payload["bookingTypeID"] = 1;
	$payload["contractID"] = 1;
	$payload["pmID"] = 1;
	$payload["statusID"] = "6";
	
#	$payload["priceIN"] = 3750;
#	$payload["priceVAT"] = 750;
#	$payload["priceEX"] = 3000;

	if ($wio_config["global"]["date_format"] == "mm/dd/yy")
	{
		preg_match("/([0-9]+)\/([0-9]+)\/([0-9]+)/",$_SESSION["bookingdate"]["from"],$matches);
		list ( $date, $month, $day, $year ) = $matches;
		$payload["bookingStart"] = "$year-$month-$day 12:00:00";

		
		preg_match("/([0-9]+)\/([0-9]+)\/([0-9]+)/",$_SESSION["bookingdate"]["to"],$matches);
		list ( $date, $month, $day, $year ) = $matches;
		$payload["bookingEnd"] = "$year-$month-$day 11:00:00";
	}

#	$payload["bookingStart"] = "2020-12-12 12:00:00";

	$payload["cart"] = $_SESSION["cart"];
#	$payload["amountDue"] = 3750;
	// $payload["amountPaid"] = 3750;

	if ($_REQUEST["fragt"] == "levering")
		$payload["collectionMethod"] = $payload["deliveryMethod"] = "us"; 
	else
		$payload["collectionMethod"] = $payload["deliveryMethod"] = "customer"; 
	$request->payload = json_encode($payload);
	$request->requestTypePost();
	$request->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
	$request->endpoint($wio_config["global"]["endpoint"]. 'bookings');
	$results = $request->perform();

if ($_REQUEST["payment"] == "bank")
{
print "Thanks for your order, it has been assignet order #".$results["rowId"].", you will shortly receive and e-mail containing further details about payment and shipment.";
}
else
{

print "Thanks for your order, it has been assignet order #".$results["rowId"].", you will shortly receive and e-mail containing further details. Click <a href='javascript:alert(\"Not yet ready\");'>here</a> to pay through Paypall..";

global $wio_config;
?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="business" value="<?=$wio_config["payment"]["paypal"]?>">
  <input type="hidden" name="item_name" value="Webshop order <?=$results[$rowId];?>">
  <input type="hidden" name="item_number" value="<?=$results["rowId"]?>">
<?
  #print '  <input type="hidden" name="amount" value="'.$_SESSION["total"].'">'."\n";
  print '  <input type="hidden" name="amount" value="2.0">'."\n";
?>
  <input type="hidden" name="notify_url" value="<?=$_SERVER["HTTP_ORIGIN"]?>/?shop=ipn">
  <input type="hidden" name="tax" value="0">
  <input type="hidden" name="quantity" value="1">
  <input type="hidden" name="currency_code" value="DKK">

  <input type="image" name="submit"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
</form>
<?
}


#print_r($results);
#print_r($_REQUEST);
#print_r($_SESSION);
?>
