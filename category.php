<style>
.shop-container {
	text-align: center;
}
.shop-product:hover {
	border: 1px solid #bbb;
}
.shop-product {
	border: 1px solid transparent;
	border: 1px solid #bbb;
	Margin-bottom: 20px;
}
.shop-product img {
	float: left;
	height: 150px;
	width: 150px;
	padding-right: 25px;
	padding-bottom: 25px;
}
.clear {
	clear: both;
}
.productname {
	font-weight: 900;
}
.productdescription {
	font-size: 11px;
}
.productprice {
float: right;
font-weight: 900;
}
</style>
<div id="shop-container">
<?


#$endpoint = "https://mike42.wrtcloud.com/api/v1.0/equipment/?filter[]=equals(categoryID,".$_REQUEST["showcat"].")";

	$wio_config = yaml_parse(file_get_contents(plugin_dir_path(__FILE__)."/config.yaml"));
	$request = new SimpleApiClient();
	$request->endpoint($wio_config["global"]["endpoint"]."equipment/?filter[]=equals(categoryID,".$_REQUEST["showcat"].")");
	$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
	
	$results = $request->perform();

foreach ($results as $product)
{
	if ($product["shopImage"] != "")
	{
	?>
		<div class="shop-product">

			<img src="<?=$product["shopImage"];?>">

			<div class="productname">
				<a href="?shop=product&product=<?=$product["id"]?>">
					<?=$product["equipmentName"];?> 
				</a>
			</div>
			<div class="productdescription">
				<?
					$desc=$product["shopDescription"];
					$desc = preg_replace("/<iframe.+\/iframe>/","",$desc);		
					print $desc;
				?>
			
				<div class="clear"></div>
				<br/>
				<div class="productprice">
					<?=$product["price"];?> DKK
					<br/>
					<input type="button" value="Show product" onclick="document.location='?shop=product&product=<?=$product["id"]?>';">
				</div>
			</div>
			<div class="clear"></div>
			<br/>
		</div>
	<?
	}
}

?>


</div>


