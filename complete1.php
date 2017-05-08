<?
if ($_SESSION["authed"])
{
print "Welcome back ".$_SESSION["authed"]["firstname"]." ".$_SESSION["authed"]["lastname"]." (".$_SESSION["authed"]["email"]."), ";
print "Not you? <a href='?shop=complete1&action=logout'>Click here to logout!</a><br/><br/>";
#print "<pre>";
#print_r($_SESSION);
}
else
{
?>
<a href="?shop=login">Allready got an account? Click here to login.</a><br/><br/>
<?
}
?>
<form method="post">
<input type=hidden name=posted value=true>
<h2>Create account - business details</h2>
<fieldset>
<?
	$fields[] = array("text"=>"Firstname: *", "id"=>"fornavn", "db"=>"firstname");
	$fields[] = array("text"=>"Lastname: *", "id"=>"efternavn", "db"=>"lastname");
	$fields[] = array("text"=>"E-Mail: *", "id"=>"email", "db"=>"email");
	$fields[] = array("text"=>"Phone: *", "id"=>"tlf", "db"=>"phone");
	$fields[] = array("text"=>"Cell:", "id"=>"cell", "db"=>"mobile");
	$fields[] = array("text"=>"Adresselinie 1.: *", "id"=>"adresse1", "db"=>"address1");
	$fields[] = array("text"=>"Adresselinie 2.:", "id"=>"adresse2", "db"=>"address2");
	$fields[] = array("text"=>"Zip-code: *", "id"=>"postnummer", "db"=>"zip");
	$fields[] = array("text"=>"City: *", "id"=>"by", "db"=>"city");
	
	$db = "";
	foreach ($fields as $field)
	{
		if ($_SESSION["authed"])
			$db = " readonly value='".$_SESSION["authed"][$field["db"]]."'";

		print "<label for='".$field["id"]."'>".$field["text"]."</label><input type=text id=".$field["id"]." name=".$field["id"].$db.">";
	}
?>

</fieldset>
<br/>
<h2>Create account - shipping details</h2>
<fieldset>
<?
unset($fields);
	$fields[] = array("text"=>"Name: *", "id"=>"deliveryName", "db"=>"deliveryName");
	$fields[] = array("text"=>"Adresselinie 1.: *", "id"=>"deliveryAddress1", "db"=>"deliveryAddress");
	$fields[] = array("text"=>"Adresselinie 2.:", "id"=>"deliveryAddress2", "db"=>"deliveryAddress1");
	$fields[] = array("text"=>"Zip-code: *", "id"=>"deliveryZip", "db"=>"deliveryZip");
	$fields[] = array("text"=>"City: *", "id"=>"deliveryCity", "db"=>"deliveryCity");
	$db = "";
	foreach ($fields as $field)
	{
		if ($_SESSION["authed"])
			$db = " readonly value='".$_SESSION["authed"][$field["db"]]."'";

		print "<label for='".$field["id"]."'>".$field["text"]."</label><input type=text id=".$field["id"]." name=".$field["id"].$db.">";
	}
?>

</fieldset>
<br/>
<div id=fragt>
</div>
<br/>
<h2>Vælg betalingsmetode</h2>
<table border=1>
<tr><td valign=middle><input type=radio name=payment value=bank id=bank style='float: left; width: 5px;'></td>
<td><label for=bank style='width: 400px;'><strong>Bankoverførsel</strong><br/>Beløbet overføres til vores konto før levering.</label></td></tr>
				

<tr><td valign=middle><input type=radio name=payment value=paypal id=paypal style='float: left; width: 5px;'></td>
<td><label for=paypal style='width: 400px;'><strong>Paypal</strong><br/>Sikker kreditkort betaling via PayPal.</label></td></tr>
</table>

<input type=hidden name=shop value=complete2>
<label for=submit>&nbsp;</label> <input type=submit id=submit value="Go to payment">

<style>
label {
width: 200px;
}
input {
width: 370px;
}
label,input {
float: left;
display: block;
}
input[type=submit] {
	width: 125px;
	margin-left:250px;
	margin-top: 25px;
}
</style>

</form>
<script>

function fail(field,msg)
{
	
	jQuery(field).select().focus();
	jQuery("html,body").animate({
		scrollTop: (jQuery(field).offset().top - 300)
	},1500);
	alert("aaa:"+msg);

	return false;
}

function validateEmail(email)
{
	var retval = false;
	jQuery.ajax({
		async: false,
		url: "/wp-content/plugins/WioShop/ajax.php?action=validateemail&email="+email,
		success: function(data)
		{
		if (data != "OK")
		{
			alert(data);
			retval = false;
			jQuery("#email").focus();
		}
		else {
			retval = true;
		}
		}
	});
	return retval;
}


jQuery("input[id=submit]").click(function(){
	if (jQuery("#fornavn").val() == "")
		return fail("#fornavn","Indtast venligst dit fornavn");

	if (jQuery("#efternavn").val() == "")
		return fail("#efternavn","Indtast venligst dit efternavn");

	if (jQuery("#email").val() == "")
		return fail("#email","Indtast venligst dit E-Mail");

	if (jQuery("#email").val() == "")
		return fail("#email","Indtast venligst dit E-Mail");

	if (!validateEmail(jQuery("#email").val()))
		return false;
	
	if (jQuery("#tlf").val() == "")
		return fail("#tlf","Indtast venligst dit telefonnummer");

	if (jQuery("#adresse1").val() == "")
		return fail("#adresse1","Indtast venligst din adresse");
	
	
	if (jQuery("#postnummer").val() == "")
		return fail("#postnummer","Indtast venligst dit postnummer");

	if (jQuery("#by").val() == "")
		return fail("#by","Indtast venligst dit by");


	if (jQuery("#deliveryName").val() == "") return fail("#deliveryName","Indtast venligst dit navn");

	if (jQuery("#deliveryAddress1").val() == "") return fail("#deliveryAddress1","Indtast venligst din adresse");
	
	if (jQuery("#deliveryZip").val() == "") return fail("#deliveryZip","Indtast venligst dit postnummer");

	if (jQuery("#deliveryCity").val() == "") return fail("#deliveryCity","Indtast venligst dit by");





	if (!((jQuery("#levering").is(":checked")) || (jQuery("#afhentning").is(":checked"))))
	{
		alert("Du skal vælge en fragtmetode");
		return false
	}

	if (!((jQuery("#bank").is(":checked")) || (jQuery("#paypal").is(":checked"))))
	{
		alert("Du skal vælge en betalingsmetode");
		return false
	}

	


});
jQuery("input[name=deliveryZip]").change(function(){
	postnr = jQuery("input[name=deliveryZip]").val();
	if ((postnr > 999) && (postnr < 9999))
	{
		jQuery("body").trigger("update");
	}
	else
	{
		jQuery("input[name=deliveryZip]").focus().select();
		alert("Ugyldigt postnummer");
	}
});


jQuery("body").on("update", function(){
	jQuery("#fragt").load("?shop=complete1&ajax=fragt&zip="+jQuery("#deliveryZip").val());
});

jQuery("body").trigger("update");
</script>
