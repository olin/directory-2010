<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
		
	require_once("$root/_framework/lib/HTTPManager.php");
	HTTPManager::requireSSL(true);

	$badCredentials = (@$_REQUEST['e']=="badCredentials");
	
	include("$root/_framework/header.php");
//##########################################################################
	?>

<h2>Olin Account Expiration Checker</h2>

<p>You probably have no idea when your Olin domain password expires.  Good news!  Just log in to your Olin account here and you'll wonder no longer.  You can even download an iCal reminder!</p>

<form action="verify.php" method="post">
<?php if($badCredentials){ ?>
	<p class="Error">Wrong username or password.</p>
<?php } ?>
	<div class="AlignRight">
		<label style="display: inline-block;width:5em;" for="username"><b>Username</b>: </label> 
		<span style="display: inline-block;width:4em;text-align:right;">olin.edu\</span><input size="15" type="text" name="username" id="username" value="<?php print @$_REQUEST['u']; ?>" />
		</div>
	<div class="AlignRight">
		<label style="display: inline-block;width:5em;" for="password"><b>Password</b>: </label>
		<span style="display:inline-block;width:4em;">&nbsp;</span><input type="password" size="15" name="password" id="password" />
		</div>
	<div class="AlignRight"><br />
		<input class="Submit" type="submit" name="submit" value="When does my password expire?" />
		</div>
	</div></form>
<p><em style="font-size: 75%;">We won't store or misuse your Olin College network credentials.</em></p>

</body>
</html>

<?php
//##########################################################################
	@include("$root/_framework/footer.php");
	?>
