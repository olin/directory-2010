<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}

	$pageTitle = "Olin Directory &raquo; Admin";
	$current = "Admin";
	$accessPermissions = "admin";
	
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<h2><?php print $pageTitle; ?></h2>
<ul>
	<li><a href="logs/">Event Logs</a></li>
	</ul>

<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>


