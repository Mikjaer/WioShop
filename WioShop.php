<?php
/*
 Plugin Name: Wio Shop
 Description: Reference Implementation of Wio Soft Api 
 Version: 1.0 
 Author: Mike Mikjaer 
 License: GPL 2.0
 Text Domain: Wio Shop 
 */
session_start();

if (!function_exists("yaml_parse"))
	$wio_config = yaml_parse(file_get_contents(plugin_dir_path(__FILE__)."/config.yaml"));
else 
	include("config.php");

include(plugin_dir_path( __FILE__ )."/api/SimpleApiClient.php");
wp_enqueue_script("jquery");


if ($_REQUEST["shop"] == "ipn")
	die(include(plugin_dir_path(__FILE__)."ipn.php"));

$wio_pages["category"]		= array("file"=>"category.php", "title"=>"Category");
$wio_pages["categories"]	= array("file"=>"categories.php", "title"=>"Categories");
$wio_pages["product"]		= array("file"=>"product.php", "title"=>"Product");
$wio_pages["cart"]		= array("file"=>"cart.php", "title"=>"Cart");
$wio_pages["login"]		= array("file"=>"login.php", "title"=>"Login");
$wio_pages["recover"]		= array("file"=>"recover.php", "title"=>"Recover Password");
$wio_pages["complete1"]		= array("file"=>"complete1.php", "title"=>"Complete Order - Details");
$wio_pages["complete2"]		= array("file"=>"complete2.php", "title"=>"Complete Order - Payment");
function parseDate($date)
{
	global $wio_config;
	if ($wio_config["global"]["date_format"] == "mm/dd/yy")
		return date_create_from_format("m/d/Y",$date);
	else
		return date_create_from_format("d/m/Y",$date);
}


function shop_render ( $atts ){

#	if ($_REQUEST["posted"])
#	{
#		print "<pre>";
#		print_r($_REQUEST);

		
#		die();
#	}


	global $wio_pages;
	
	if ($_REQUEST["action"] == "logout")
		unset($_SESSION["authed"]);
	
	if (isset($wio_pages[$_REQUEST["shop"]]))
	{
		include(plugin_dir_path(__FILE__).$wio_pages[$_REQUEST["shop"]]["file"]); 
	}
	else
		print "<script>document.location='?shop=categories';</script>";
}

add_shortcode( 'shop', 'shop_render' );

function title_filter($data)
{
	global $wio_pages;

	if ((in_the_loop()) and (isset($wio_pages[$_REQUEST["shop"]])))
		return $wio_pages[$_REQUEST["shop"]]["title"];
	else
		return $data;
}


add_filter("the_title", "title_filter",100000000000000000000000);

if ($_REQUEST["ajax"] == "fragt")
{
	print "<h2>V&aelig;lg fragt</h2>";

	if (($_REQUEST["zip"] > 999) and ($_REQUEST["zip"] < 10000))
	{
		foreach ($wio_config["postage"]["zones"] as $zone)
		{
			foreach ($zone["ranges"] as $range)
			{
				list($from, $to) = explode("-",$range);
				if (($_REQUEST["zip"] >= $from) and ($_REQUEST["zip"] <= $to))
				{

					print "<table>";
					print "<tr><td><input name=fragt value=levering id=levering type=radio style='float: left; width: 5px;'></td>";
					print "<td><strong><label for=levering style='width: 500px;'>".$zone["name"]."</strong><br/>".$zone["description"]."</label></td></tr>";
				

					print "<tr><td><input name=fragt value=afhentning id=afhentning type=radio style='float: left; width: 5px;'></td>";
					print "<td><label for=afhentning style='width: 600px;'><strong>".$wio_config["postage"]["name"].
						"</strong><br/>".$wio_config["postage"]["description"]."</label></td></tr>";
					print "</table>";
					
					die();
				}
			}
		}
	}
	else
	{
	print "Start med at udfylde din adresse.";
	}
	die();
}

