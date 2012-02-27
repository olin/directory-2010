<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
		
	// dependencies
	require_once("$root/_framework/lib/account.php");
	require_once("$root/_framework/lib/HTTPManager.php");
	require_once("$root/_framework/lib/Mobile.php");
	
	// require login
	HTTPManager::requireSSL(true);
	if(!isLoggedIn()){
		$next = "../account/signin/?next=/mobile/";
		header("Location: $next");
		die("You need to sign in to see this page.  <a href=\"$next\">Continue</a>");
		}
	// obtain current data from DB (for display, change detection)
	$user = getLoggedInUser();

	//now display page
	$pageTitle = "Olin Directory &raquo; Mobile";
	$current = "Mobile";
	$accessPermissions = "mobile";

	$customScriptIncludes = array("interface.js");
	
	$customScript = <<<ENDCUSTOMSCRIPT
$(document).ready(function(){
	//do stuff
	});
ENDCUSTOMSCRIPT;
		
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<form class="Formatted" method="post" style="font-size: 95%;">
<div>
	<span style="font-weight:bold; font-size: 150%;">Mobile Access</span>&ensp;
	<em style="font-size: smaller;">Access OlinDirectory from any mobile phone!</em>
</div>

<p style="width:45em;">You can access OlinDirectory from your phone, just follow these steps!<ol>
	<li style="margin: 0.5em;">Verify one or more email addresses below (<a href="#" class="Reminder" onclick="$('#help_devices').toggle();return false;">Do I have to have an iPhone/BlackBerry/Droid ?</a>)
		<p style="width: 38em; padding: 1em; background: #cfc;" id="help_devices">You can use OlinDirectory Mobile from any device that can send and receive emails, as long as you verify that device (below) ahead of time.<br /><br />Most phones that can send SMS text messages can also send emails.  Just enter any email address in the "To:" field of your message in place of a phone number.  See your phone/carrier's documentation if you have more queestions or problems. <a href="#" onclick="$('#help_devices').hide();return false;">(close)</a></p>
		</li>
	<li style="margin: 0.5em;">From any verified email address send your query to <a href="mailto:OlinDirectory@gmail.com">OlinDirectory@gmail.com</a>.</li>
	<li style="margin: 0.5em;">Soon (typically in under a minute), OlinDirectory will respond with the results of your query.</li></ol></p>
<p style="width:45em;"><strong>Disclaimer:</strong> Although OlinDirectory Mobile is free, your carrier's standard messaging or data fees may apply.  OlinDirectory Mobile will only email you in direct response to your emailed queries.</p>

<div class="Blocks">

<div>
	<div id="existing">
	<h3>Verified Addresses</h3>
		<?php
		$addrs = MobileEmail::getValidatedAddresses($user);
		foreach($addrs as $addr){ ?>
		<p class="Address"><a class="Delete"><img src="delete.png" border="0" align="absmiddle" title="Delete this address" /></a> <?php print htmlspecialchars($addr); ?></p>
		<?php
			}
		if(count($addrs)==0){?><p id="noAddrs" style="width:20em;">To access OlinDirectory by email or txt message, you must verify at least one email address.</p>
		<?php } ?>
		</div>
	</div>
<div>
	<div id="addnew" style="width:20em;">
	<h3>Add New Device<span id="addnew_subtitle"></span></h3>
		<div id="addnew_requesting">
			<p>Contacting server...</p>
			</div>
		<div id="addnew_waiting">
			<p>Using your mobile device, send an email to <a href="mailto:OlinDirectory@gmail.com">OlinDirectory@gmail.com</a> with this code in the message:</p>
			<h2 style="text-align:center;width:100%;font-family:monospace;font-size:220%;" id="confCode">code</h2>
			<div style="float:left; margin:0; padding:0; width:32px; height:32px; margin-right: 10px;"><img src="loader2.gif" width="32" height="32" /></div>
			<p>Waiting to receive your email <em style="font-size: 85%">(may take a 1-2 minutes after you send)</em></p>
			<p style="text-align: right;"><input type="submit" id="btnCancel" value="Cancel" /></p>
			</div>
		<div id="addnew_confirmed">
			<p>We got your email from this address:</p>
			<p style="text-align: center;"><a href="" id="emailConf">email</a></p>
			<p>Now you can use that email account to send your queries to <a href="mailto:OlinDirectory@gmail.com">OlinDirectory@gmail.com</a>.</p>
			<p>OlinDirectory will typically respond to emails within a few minutes!</p>
			</div>
		<div id="addnew_init">
			<input type="submit" id="btnAdd" value="Add a new device..." />
			</div>
		</div>
	</div>

</div>

</form>


<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
