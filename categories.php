<style>
.shop-container {
	text-align: center;
}
.shop-category:hover {
	border: 1px solid #bbb;
}
.shop-category {
	border: 1px solid transparent;
	padding: 5px;
	margin-left: 10px;
	margin-right: 10px;
	text-align: center;
	float: left;
}
.shop-category img {
	height: 150px;
	width: 150px;
}
</style>

<div id="shop-container">
<?


$wio_config = yaml_parse(file_get_contents(plugin_dir_path(__FILE__)."/config.yaml"));

if (isset($_REQUEST["parent"]))
	$parent = $_REQUEST["parent"];
else
	$parent = 100;

	$request = new SimpleApiClient();
	$request->endpoint($wio_config["global"]["endpoint"]."categories");
	$request->requestTypeGet()->addHeader("X-API-Auth: ".$wio_config["global"]["apikey"]);;
	
	$request->requestTypeGet();

	$results = $request->perform();

#print_r($results);die();
#$endpoint = "https://mike42.wrtcloud.com/api/v1.0/categories/";
#$results = json_decode($a=file_get_contents($endpoint),true);

foreach ($results as $_)
	if ($_["id"] == $parent)
		print "<h2>".$_["name"]."</h2>";

foreach ($results as $_)
{
if ((($_["parent_id"] == $parent) and ($_["published"] == 1)) and ($_["image"] != "") and ($_["productsCount"] > 0))
	print "<div class='shop-category'><a href='?shop=category&showcat=".$_["id"]."'><img  src='".$_["image"]."'><br/> ".$_["name"]." </a> </div>";
}

?>

</div>

