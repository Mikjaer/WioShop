  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">



<style>
.product {
}
img {
	float: left;
	width: 200px;
	height: 200px;
}
.details{
	width: 350px;
	float: right;
	
}
.description{
	float: left;
}
.book {
}
.clear {
	clear: both;
}				
</style>
<?php
session_start();
	global $wio_config;
	$request = new SimpleApiClient();
	$request->endpoint($wio_config["global"]["endpoint"]."equipment/".$_REQUEST["product"]);

	$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;

	$results = $request->perform();
global $wio_config;
?>
<div class=product>
	<img src="<?=$results["shopImage"];?>">

	<div class=details>
		<h2> <?=$results["equipmentName"]?> </h2>
<br/>
	<?
		print substr(preg_replace("/<iframe.+\/iframe>/","",$results["shopDescription"]),0,900);
	?> 
	</div>
		<br/>	
		<br/>	

	 
				<div class="clear"></div>
	<br/><br/>
	
	<div class="bottom">
<script>
function moneyFormat(value)
{
	return value.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
}
</script>

<?
if( $wio_config["global"]["shop_max_days"] == 1)
{
?>
<script>
$(function(){
$("#todate").hide();
$("#todatelabel").hide();
$("#totalcostspan").hide();
});
</script>
<?
}
?>
	<div id="datepicker_from" style="float: left;"></div>

<label for="from">From</label>
<input type="text" id="fromdate" name="fromdate">
<label for="to" id="todatelabel">to</label>
<input type="text" id="todate" name="todate">
<script>
$( function() {
      from = $( "#fromdate" )
        .datepicker({
          dateFormat: "<?=$wio_config["global"]["date_format"]?>",
	  defaultDate: "+1w",
          changeMonth: true,
	  minDate: 0,
          numberOfMonths: 1
        })
        .on( "change", function() {
	updateFrom();
	});

     
      $("#todate").click(function(){
      	alert("Please start by selected a from-date.");
	$("#fromdate").focus();
      });
});

    function update()
    {

	// Calculate days
	start = $("#fromdate").datepicker("getDate");
	end = $("#todate").datepicker("getDate");
	diff = new Date(end - start);
	days = diff/1000/60/60/24;

	price = <?=$results["price"];?>;
	if (days == 1)
		$("#days").html(days+ " day");
	else if (days > 1)
		$("#days").html(days+ " days");


	if (days > 0)
		$("#totalprice").html(moneyFormat(price*days));

	if ($("#fromdate").val() != "")
	{
		//alert($("#fromdate").val());
		//alert($("#todate").val());

		$.ajax({
			url: "/wp-content/plugins/WioShop/ajax.php?action=selectdate&from="+$("#fromdate").val()+"&to="+$("#todate").val(),
			context: document.body
		}).done(function(data) {
			if (data != "OK")
			{
				alert("Nogen gik galt:"+data);
			}
		});

	}

	console.log(days+" dage");
    }
    function updateFrom()
    {
	  var from = $("#fromdate").datepicker("getDate");
	  var date1 = $("#fromdate").datepicker("getDate");
	  var date2 = $("#fromdate").datepicker("getDate");

	 to = $( "#todate" ).datepicker({
	        defaultDate: "+1w",
	        dateFormat: "<?=$wio_config["global"]["date_format"]?>",
	        changeMonth: true,
	        numberOfMonths: 1,
		enabled: false
	      })
	      .on( "change", function() {
		update();
	      });
      

      $("#todate").unbind("click");
	
	  date1.setDate(date1.getDate()+1);
	  date2.setDate(date2.getDate()+<?=$wio_config["global"]["shop_max_days"];?>);
	  
	  to.datepicker( "setDate", date1 )
	  to.datepicker( "option", "minDate", date1);
	  to.datepicker( "option", "maxDate", date2 );

    	update();
    }

    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( "<?=$wio_config["global"]["date_format"]?>", element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
</script>	

		<div style="float: right;">
		Price pr. day: <span id=priceperday><script>document.write(moneyFormat(<?=$results["price"];?>));</script></span><br/>
		<span id="totalcostspan">
		Total cost, <span id=days>1 day</span>: <span id=totalprice><script>document.write(moneyFormat(<?=$results["price"];?>));</script></span><br/>
		</span>
		<input type=text value=1 name=amount id=amount style='width: 50px;'>
		<input type=submit id=addtocart value="Add to cart">
	</div>

	<div class="clear"></div>
</div>
<script>
	$("#addtocart").click(function(){
		$.ajax({
			url: "/wp-content/plugins/WioShop/ajax.php?action=addtobasket&product=<?=$results['id'];?>&amount="+$("#amount").val(),
			context: document.body
		}).done(function(data) {
			if (data != "OK")
			{
				alert(data);
			} else {
			
				$( function() { 
					$( "#dialog" ).dialog({
						modal: true,
						buttons: {
						      "Keep shopping": function(){
							$("#dialog").dialog("close");
						      },
						      "Go to cart": function(){
							document.location="?shop=cart";
						      }
					      }
					}); 
				
				} );
			}
		});
	});
	$(function(){
		
	  <? if (isset($_SESSION["bookingdate"]["from"])) { ?>
	  $("#fromdate").datepicker("setDate", "<?=$_SESSION["bookingdate"]["from"]?>");
		updateFrom();
	  <?  } ?>
	  
	  <? if (isset($_SESSION["bookingdate"]["from"])) { ?>
	  $("#todate").datepicker("setDate", "<?=$_SESSION["bookingdate"]["to"]?>");
	  <?  } ?>
		update();
	});
</script>

</div>



  <script>
	    </script>
	    </head>
	    <body>
	     
	     <div id="dialog" title="Product added to cart" style="display: none">
	       <p>Product has been added to cart..</p>
	       </div>
