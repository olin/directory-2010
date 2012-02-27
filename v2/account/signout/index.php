<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
	
	/* dependencies */
	require_once("$root/_framework/lib/account.php");
	require_once("$root/_framework/lib/Log.php");

	/* page template */
	$pageTitle = "Olin Directory &raquo; Sign Out";
	$current = "Sign Out";
	$accessPermissions = "none";
	
	if(isLoggedIn()){
		@Log::info("ACCOUNT",'User '.getLoggedInUser().' ('.getLoggedInUserFullName().') signed OUT');
		setLoggedIn(false);
		}
	header("Location: $root/");
	
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

You have been signed out.

<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
