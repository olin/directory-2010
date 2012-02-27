<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
		
	// dependencies
	require_once("$root/_framework/lib/account.php");
	require_once("$root/_framework/lib/UserManager.php");
	require_once("$root/_framework/lib/Debug.php");
	require_once("$root/_framework/lib/email.php");
	require_once("$root/_framework/lib/Log.php");
	require_once("$root/_framework/conf/Config.php");
	
	$admin = Config::get('site.admin.email');
	
	$name = @$_REQUEST['name'];
	$from = @$_REQUEST['email'];
	$comments = @$_REQUEST['comments'];
	if(!$name || !$from || !$comments){ header("Location: ./"); die(); }
	
	@Log::info("FEEDBK",'Got feedback from '.addslashes($name).' <'.addslashes($from).'>');
	
	email($from,$admin,"OlinDirectory Feedback from $name","= OlinDirectory Feedback =\n\nFrom: $name <$from>\nWhen: ".date ("Y-m-d H:i:s").": \n\n$comments",false);
	@Log::info("FEEDBK",'Feedback dispatched via email to site admin');
	
	//now display page
	$pageTitle = "Olin Directory &raquo; Feedback &amp; Problems";
	$current = "Feedback";
	$accessPermissions = "none";
		
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<h2>Feedback &amp; Problems</h2>

<p>Thanks for taking the time to give feedback or report a problem!<br />I will read your email and respond as quickly as possible.</p>
<p>- Jeffrey Stanton</p>


<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
