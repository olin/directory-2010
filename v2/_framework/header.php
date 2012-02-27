<?php
	/* convenience method; equivalent to print(htmlspecialchars($msg)); */
	/* Useful for printing strings w/ HTML special chars escaped (e.g. inside forms, markup, etc) */
	
	@include('lib/BanSubdomain.php');

	function htmlprint($msg){
		print(htmlspecialchars($msg));
		}

	// Check for login; if login is required and not logged in, redirect and die.
	require_once('lib/account.php');
	require_once('conf/Config.php');

	$accessAllowed = Account::hasPermission(@$accessPermissions);
	if(!$accessAllowed){
		if(!isLoggedIn()){
			$redirect = "$root/account/signin/?abs=1&next=".urlencode($_SERVER['PHP_SELF']);
			$redirect = preg_replace("/index.php\$/im","",$redirect);
		}else{
			$redirect = "$root/";
			}
		header("Location: $redirect");
		die("Please <a href=\"$redirect\">Sign In</a> to continue.");
		}


	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="<?php print $root;?>/_framework/style/bluesleuth/main.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $root;?>/_framework/style/key3po/key3po.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $root;?>/_framework/style/forms/forms.css" type="text/css" />
    <link rel="stylesheet" href="<?php print $root;?>/_framework/style/jquery.jgrowl.css" type="text/css" />
	<link rel="search" type="application/opensearchdescription+xml" title="Olin Directory" href="<?php print $root;?>/_framework/opensearch/search.xml" />
    <link rel="shortcut icon" href="<?php print $root;?>/_framework/opensearch/icon.ico" />
	<link rel="apple-touch-icon" href="<?php print $root;?>/img/apple-touch-icon.png" />
	<script src="<?php print $root;?>/_framework/js/json.js" language="JavaScript" type="text/javascript"></script>
	<script src="<?php print $root;?>/_framework/js/jquery.js" language="JavaScript" type="text/javascript"></script>
	<script src="<?php print $root;?>/_framework/js/jquery.ui.js" language="JavaScript" type="text/javascript"></script>
	<script src="<?php print $root;?>/_framework/js/formatters.js" language="JavaScript" type="text/javascript"></script>
    <script src="<?php print $root;?>/_framework/js/jquery.jGrowl.js" language="JavaScript" type="text/javascript"></script>
	<?php if(isset($customScriptIncludes)&&is_array($customScriptIncludes)){ foreach($customScriptIncludes as $scriptPath){ ?>
	<script src="<?php print $scriptPath;?>" language="JavaScript" type="text/javascript"></script>
	<?php } }
	if(isset($loadFocusSelector)){ ?>
    <script language= "JavaScript" type="text/javascript">/*<![CDATA[*/
	$(document).ready(function(){
		$("<?php print $loadFocusSelector; ?>").focus();
		});
	/*]]>*/</script>
<?php } ?>
<?php if(isset($startupScript)){ ?>
    <script language= "JavaScript" type="text/javascript">/*<![CDATA[*/
	$(document).ready(function(){
		<?php print $startupScript; ?>
		});
	/*]]>*/</script>
<?php } ?>
<?php if(isset($customScript)){ ?>
    <script language= "JavaScript" type="text/javascript">/*<![CDATA[*/
	<?php print $customScript; ?>
	/*]]>*/</script>
<?php } ?>

    <title><?php print @$pageTitle; ?></title>
    </head>
<body>

<div id="stripe_main">
	<div id="logo_top"></div>
    </div>
<div id="stripe_menu">
    <ul id="menu" class="Horizontal Menu">
        <li<?php if(@$current=="Search"){ print ' class="ThisPage"'; }?>><a href="<?php print $root;?>/">Search</a></li>
		<?php
		if(!Account::hasPermission("signin")){ ?>
	        <li<?php if(@$current=="Sign In"){ print ' class="ThisPage"'; }?>><a style="display:none;" href="<?php print $root;?>/account/signin/">Sign In</a></li>
			<?php }
		else { ?>
			<!--<li<?php if(@$current=="Profile"){ print ' class="ThisPage"'; }?>><a href="<?php print $root;?>/account/edit/" id="edit">My Profile <span class="Info"> (<?php print getLoggedInUserFullName(); ?>)</span></a></li>-->
			<?php
			if(Account::hasPermission("admin")){?>
				<li<?php if(@$current=="Admin"){ print ' class="ThisPage"'; }?>><a href="<?php print $root;?>/admin/">Admin</a></li>
				<?php
				}
			if(Account::hasPermission("mobile")){?>
				<li<?php if(@$current=="Mobile"){ print ' class="ThisPage"'; }?>><a href="<?php print $root;?>/account/edit/mobile/">Mobile</a></li>
				<?php } ?>
			<li<?php if(@$current=="Sign Out"){ print ' class="ThisPage"'; }?>><a href="<?php print "$root/account/signout/"; ?>">Sign Out</a></li>
			<?php
			} ?>
		<li<?php if(@$current=="Feedback"){ print ' class="ThisPage"'; }?>><a href="<?php print $root;?>/feedback/">Feedback</a></li>
        </ul>
    </div>

<div id="logo_bottom"></div>

<div id="announcement_bar">
	<img src="<?php print $root;?>/img/badge-new.png" width="44" height="44" />
	<a href="<?php print $root;?>/m/" title="Open a light version of the page suitable for mobile browsers.">Now for Mobile Devices!</a>
	</div>

<div id="content">

<p style="background-color:#cfc; display:inline-block;padding:0.5em;border:1px solid #6c6; border-radius: 0.25em;">Note: I'm in the process of propping up a new version.  You can still search, but you won't be able to change your profile information. -Jeff</p>

