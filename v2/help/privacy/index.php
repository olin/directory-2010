<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}

	$pageTitle = "Olin Directory &raquo; Privacy";
	$current = "Privacy";
	$accessPermissions = "none";
	
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

&laquo; Information about privacy measures goes here &raquo;

<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
