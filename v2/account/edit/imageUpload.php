<?php
	header("Location: ../");
	die("Profiles are not editable at this time.");

	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
		
	// dependencies
	require_once("$root/_framework/lib/account.php");
	require_once("$root/_framework/lib/ImageProcessing.php");
	require_once("$root/_framework/lib/HTTPManager.php");
	require_once("$root/_framework/lib/Log.php");
	
	// require login
	HTTPManager::requireSSL(true);
	if(!isLoggedIn()){
		header("Location: ../signin/");
		die("You need to log in to see this page.  <a href=\"$next\">Continue</a>");
		}
	$uid = getLoggedInUser();
	
	//handle image
	$ftype = $_FILES["userfile"]["type"];
	$flnm = $_FILES["userfile"]["tmp_name"];
	$fsize = $_FILES["userfile"]["size"];
	$outputfile = "$root/_headshots/uid/$uid.jpg";
	
	//die($ftype);
	
	//resize image according to spec:
	//280px on long side, cropped to have 0.75 aspect ratio
	$result = mkthumb($flnm,$ftype,$outputfile,560,0.75);
	if($result){
		@chmod($outputfile,0664);
		@chown($outputfile,"jstanton");
		@shell_exec("chown jstanton \"$outputfile\"");
		@chgrp($outputfile,"www-data");
		@Log::info("ACCOUNT",'User '.getLoggedInUser().' ('.getLoggedInUserFullName().') updated their photo');
		echo "success";
	} else {
		@Log::error("ACCOUNT",'User '.getLoggedInUser().' ('.getLoggedInUserFullName().') couldn\'t update photo: unknown error resizing image');
		echo "error: $result";
	}
	
	?>
