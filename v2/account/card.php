<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}

	$pageTitle = "Olin Directory &raquo; Card Test Bench";
	$current = "Card Test";
	$accessPermissions = "none";
	
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<?php for($i=0; $i<6; $i++){ ?>

<div class="ContactCard">
    <div class="Name">
    	<span class="FullName">John Q. Example</span>
        <span class="Class">2010</span>
        </div>
	<img src="_headshots/unknown.jpg" width="105" height="140" title="John Q. Example" />
    <div class="ContactInfo">
        <p>
        	14 Example Drive<br />
            Needham, MA  02492
            </p>
        <p>
        	781-555-5555 (Main)<br />
        	781-555-1234 (Mobile)
            </p>
        <p>East Hall 101 F (<a href="#" title="See this room on a map">map</a>)</p>
        </div>
    </div>

<?php } ?>



<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
