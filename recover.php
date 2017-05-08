<?php
global $wio_config;
include("genpas.php");
if ($_REQUEST["posted"])
{

	$request = new SimpleApiClient();
	$request->endpoint($wio_config["global"]["endpoint"].'customers?filter[]=equals(email,"'.$_REQUEST["email"].'")');

	$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
	$results = $request->perform();
	
	if (count($results))
	{
		$user = $results[0];

		$newpass = rand_pass();

		$request = new SimpleApiClient();
		$request->payload = json_encode(array("password"=>md5($newpass)));
		$request->requestTypePost()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
		$request->endpoint($wio_config["global"]["endpoint"].'customers/'.$user["id"])->perform();
		
		mail($_REQUEST["email"],"New password for ".$_SERVER['SERVER_NAME'],
		"Dear ".$user["firstname"]." ".$user["lastname"]."\n\nYou're newly generated password is: ".$newpass.
		"\n\nSincerely\n".$_SERVER['SERVER_NAME']." Team");
	} else {
	}
	?>	
	You will receive an e-mail <?=$_REQUEST["email"]?> shortly with further instructions.<br><br>
	Click <a href="?shop=login">here</a> to return to login page.
	
	<script>
		setTimeout(go,10000);
		function go()
		{
			document.location="/?shop=login";
		}
	</script>
	<?
} else {
?>

<form method="post">
<input type=hidden name=posted value=true>
<fieldset>
	<legend>Recover Password</legend>

	<label for=email>E-Mail adress</label>
	<input type=text name=email id=email>

	<div style="clear: both;"></div>

	<a href="?shop=login">Go to login</a>
	<input type=submit value="Reset password">

</fieldset>
</form>
<style>
fieldset {
	border: 1px solid;
	padding: 25px;
}
legend {
	margin-left: 25px;
	border: 1px solid black;
	padding: 5px;
}
label {
	display: block;
	width: 50%;
	float: left;
	padding: 5px;
}
input[type=text], input[type=password] {
	float: left;
	width: 45%
}
input[type=submit] {
	margin-top: 15px;
	float: right;
	padding: 5px;
	font-size: 25px !important;
	margin-right: 10px;
}
fieldset a {
	float: left;
	margin-top: 13px;
}
</style>
<?
}
?>
