<?


	if (!isset($_SESSION["cart"]))
	{
		?>
			<script>
				alert("Cart is empty");
				document.location="?";
			</script>
		<?
	}

	if (!isset($_SESSION["bookingdate"]))
	{
		?>
			<script>
				alert("No bookingdate selected.");
				document.location="?";
			</script>
		<?
	}
	if ($_REQUEST["increment"])
		$_SESSION["cart"][$_REQUEST["increment"]]++;

	if ($_REQUEST["remove"])
		unset($_SESSION["cart"][$_REQUEST["remove"]]);

	if ($_REQUEST["decrement"])
		if ($_SESSION["cart"][$_REQUEST["decrement"]]>1)
			$_SESSION["cart"][$_REQUEST["decrement"]]--;
		elseif ($_SESSION["cart"][$_REQUEST["decrement"]]==1)
			unset($_SESSION["cart"][$_REQUEST["decrement"]]);


?>
<h1>Welcome to cart</h1>
<div id="acart">
<table>
<tr>
	<th>Product</th>
	<th style="text-align: right;">Price &nbsp;</th>
	<th>&nbsp; Amount &nbsp;</th>
	<th>&nbsp;</th>
	<th style="text-align: right;">Subtotal</th>
	<th>&nbsp;</th>
</tr>
<?php
$total = 0;
session_start();
$lines = 0;
foreach ( $_SESSION["cart"] as $productid=>$amount)
{

	global $wio_config;

	$request = new SimpleApiClient();
	$request->endpoint($wio_config["global"]["endpoint"]."equipment/". $productid);

	$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;

	$results = $request->perform();
	$subtotal = $results["price"] * $amount;
	$total=$total+$subtotal;
?>
<tr>
	<td><?=$results["equipmentName"];?></td>
	<td style='text-align: right;'><?=number_format($results["price"],2,".",",");?></td>
	<td style='text-align: center;'> <?=$amount;?> </td>
	<td style='text-align: center;'> <a href='javascript:incrementCart(<?=$results["id"]?>);'>+</a> /  
					 <a href='javascript:decrementCart(<?=$results["id"]?>);'>-</a></td>
	<td style='text-align: right;'><?=number_format($subtotal,2,".",",");?></td>
	<td style='text-align: right;'><a href="javascript:removeCart(<?=$results["id"]?>);">Remove</a></td>
</tr>

<?
$lines++;
}

if ($lines == 0)
{
?>
	<script>
		alert("Cart is empty");
		document.location="?";
	</script>
<?
}
?>
<tr>
	<td><strong>Price per day</strong></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td style='text-align: right;'><?=number_format($total,2,".",",");?></td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td colspan=2>
<?

$days = date_diff(parseDate($_SESSION["bookingdate"]["from"]), parseDate($_SESSION["bookingdate"]["to"]))->days;
	print " Booking dates ".$_SESSION["bookingdate"]["from"]." - ".$_SESSION["bookingdate"]["to"].", ".$days." days.";
?>
	</td>
	<td style='text-align: center;'> <?=$days?> </td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td><strong>Total</strong></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td style='text-align: right;'><?=number_format($total*$days,2,".",",");?></td>
	<td>&nbsp;</td>
</tr>


<tr>
	<td> - hereof 25% vat</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	
	<td>&nbsp;</td>
	<td style='text-align: right;'><?=number_format($days*$total*0.2,2,".",",");?></td>
	<td>&nbsp;</td>
</tr>
</table>
<?
$_SESSION["total"] = $total;
?>
</div> <!-- ACART -->
<div style="clear: both;"></Div>
<script>
function incrementCart(id)
{
	jQuery.ajax({
		url: "?shop=cart&ajax=true&increment="+id,
		cache: false,success: function(response) {
			jQuery("#acart").html(jQuery(response).find("#acart").html());
		}
	});
}

function decrementCart(id)
{
	jQuery.ajax({
		url: "?shop=cart&ajax=true&decrement="+id,
		cache: false,success: function(response) {
			jQuery("#acart").html(jQuery(response).find("#acart").html());
		}
	});
}

function removeCart(id)
{
	jQuery.ajax({
		url: "?shop=cart&ajax=true&remove="+id,
		cache: false,success: function(response) {
			jQuery("#acart").html(jQuery(response).find("#acart").html());
		}
	});

}

</script>


<?


?>
<div style="float: right; text-align: right;">
<input type=submit value="Complete order" onclick='javascript: document.location="?shop=complete1";'>


</div>
