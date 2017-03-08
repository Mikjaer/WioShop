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

	$request = new SimpleApiClient();
	$request->endpoint("https://mike42.wrtcloud.com/api/v1.0/equipment/".$_REQUEST["product"]);

	$request->requestTypeGet();

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
<?
if( $wio_config["global"]["shop_max_days"] > 1)
{
?>
	<div id="datepicker_to" style="float: left;"></div>
	<div id="datepicker_from" style="float: left;"></div>

<label for="from">From</label>
<input type="text" id="from" name="from">
<label for="to">to</label>
<input type="text" id="to" name="to">
<script>
$( function() {
      from = $( "#from" )
        .datepicker({
          dateFormat: "<?=$wio_config["global"]["date_format"]?>",
	  defaultDate: "+1w",
          changeMonth: true,
          numberOfMonths: 1
        })
        .on( "change", function() {
          alert("change"); 
	  $("#to").datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        defaultDate: "+1w",
        onSelect: function() { $(".ui-datepicker a").removeAttr("href"); }, 
        dateFormat: "<?=$wio_config["global"]["date_format"]?>",
        changeMonth: true,
        numberOfMonths: 1 
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
});
</script>	

<?
} else {
?>

		<div id="datepicker" style="float: left;"></div>
    <script>
        $( function() {
	    var dateToday = new Date();
	    $( "#datepicker" ).datepicker({
	    minDate: dateToday,
	    firstDay: 1,
	    onSelect: function(date){
		$.ajax({
			url: "/wp-content/plugins/WioShop/ajax.php?action=selectdate&date="+date,
			context: document.body
		}).done(function(data) {
			if (data != "OK")
			{
				alert("-"+data+"-");
			} else {
				$("#bookingdate").html(date);
			}
		});

	    }
	    });
	      } );
	        </script>
	
<?

}
?>

		<div style="float: right;">
			Price pr. day: 1.200,-<br/>
			Selected date:<span id="bookingdate"> 
			<?php
				if ($_SESSION["bookingdate"])
					print $_SESSION["bookingdate"];
				else 
					print "None";
			?>
			</span>
			<br/>
			<input type=submit id=addtocart value="Add to cart">
		</div>
	
		<div class="clear"></div>
	</div>
	<script>
		$("#addtocart").click(function(){
			$.ajax({
				url: "/wp-content/plugins/WioShop/ajax.php?action=addtobasket&product=<?=$results['id'];?>",
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
	</script>

</div>

<pre>
</pre>


  <script>
	    </script>
	    </head>
	    <body>
	     
	     <div id="dialog" title="Product added to cart">
	       <p>Product has been added to cart..</p>
	       </div>
