<?php
session_start();

include("api/SimpleApiClient.php");
include("config.php");
$wio_config = wio_config(); 

switch (@$_REQUEST["action"])
{
	case "validateemail":
		if ($_REQUEST["email"] == $_SESSION["authed"]["email"])
			die("OK");

		if (filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL) === false)
			die("E-mail is not valid.");			// Don't pass invalid input to api
	
		$request = new SimpleApiClient();

		$request->endpoint($wio_config["global"]["endpoint"].'customers?filter[]=equals(email,"'.$_REQUEST["email"].'")');

		$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
		$results = $request->perform();
		
		if (isset($results[0]))
			die("An user account with the given e-mail allready exists.");	

		die("OK");
	case "selectdate":
		$_SESSION["bookingdate"]["from"] = $_REQUEST["from"];
		$_SESSION["bookingdate"]["to"] = $_REQUEST["to"];
		die("OK");
	break;
	case "addtobasket":
		@$_SESSION["cart"][$_REQUEST["product"]] = $_SESSION["cart"][$_REQUEST["product"]] + $_REQUEST["amount"];
		die("OK");
	break;
	default:
		print "My Name is Jax, A-Jax";
}
?>
