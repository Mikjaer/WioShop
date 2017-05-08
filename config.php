<?
	# Alternate config for legacy-systems
	function wio_config()
	{
		$config["global"]["endpoint"]="https://mike42.wrtcloud.com/api/v1.0/";
			$config["global"]["apikey"]="af60716ca59276bcb48742ed8007b60f";
		$config["global"]["shop_max_days"]="5";
		$config["global"]["date_format"]="mm/dd/yy";
		
		$config["payment"]["paypal"]="payment@kogtiroen.dk";

		$config["postage"]["name"]="Afhetning p&aring; vores lager";
		$config["postage"]["description"]="Afhentning foregår i tidsrummet mellem 19 og 42 i Ringsted.";

		$config["postage"]["zones"][]=array(
				"name"=>"Udbringning, Sj&aelig;lland",
				"description"=>"Vi leverer og afhenter efter nærmere aftale i 3 timers intervaller mellem 9 og 17. Natafhentning kan aftales mod merpris.",
				"price"=>"750",
				"ranges"=>array("1000-4999"));

		$config["postage"]["zones"][]=array(
				"name"=>"Udbringning, fyn",
				"description"=>"Vi leverer og afhenter efter nærmere aftale i 3 timers intervaller mellem 9 og 17. Natafhentning kan aftales mod merpris.",
				"price"=>"1750",
				"ranges"=>array("5000-5999"));


		$config["postage"]["zones"][]=array(
				"name"=>"Udbringning, Jylland",
				"description"=>"Vi leverer og afhenter efter nærmere aftale i 3 timers intervaller mellem 9 og 17. Natafhentning kan aftales mod merpris.",
				"price"=>"1750",
				"ranges"=>array("6100-9900"));


		return $config;
	}


?>
