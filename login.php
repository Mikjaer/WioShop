<?php
$wio_config = yaml_parse(file_get_contents(plugin_dir_path(__FILE__)."/config.yaml"));
if ($_REQUEST["posted"])
{

	$request = new SimpleApiClient();
	$request->endpoint($wio_config["global"]["endpoint"].'customers?filter[]=equals(email,"'.$_REQUEST["username"].'")');

	$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
	$results = $request->perform();
	
	if ((count($results)==1) and (md5($_REQUEST["password"]) == $results[0]["password"]))
	{
		$_SESSION["authed"] = $results[0];	
		print "<script>document.location='/?shop=complete1';</script>";
	} else {
		print "<font color='red'>Unknown username or password.</font><br/><br/>";
	}
}
?>

<form method="post">
<input type=hidden name=posted value=true>
<fieldset>
	<legend>Login</legend>

	<label for=username>Username / E-Mail </label>
	<input type=text name=username id=username>

	<label for=password>Password </label>
	<input type=password name=password id=password>

	<div style="clear: both;"></div>

	<a href="?shop=recover">Reset password</a>
	<input type=submit>

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
