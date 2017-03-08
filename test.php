<?php
	$f = file_get_contents("config.yaml");
	print_r(yaml_parse($f));
?>
