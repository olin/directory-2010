<?php
//$user = @$session->read('Auth.Account.firstName')." ".@$session->read('Auth.Account.lastName');
//$isSignedIn = ($user!==false && $user!==null && $user!==' ');
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="stylesheet" href="<?php print $this->base;?>/css/Page.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $this->base;?>/css/SearchViews.css" type="text/css" />
	<script language="JavaScript" type="text/javascript">
		/* cake-to-js variable pass thru */
		document.cakeBase = "<?php print $this->base; ?>";
	</script>
	<title>OlinDirectory : <?php echo $title_for_layout ?></title>
	<script language="JavaScript" type="text/javascript" src="<?php print $this->base;?>/js/lib/jquery-1.4.3.min.js"></script>
	<link rel="search" type="application/opensearchdescription+xml" title="OlinDirectory" href="<?php print $this->base;?>/opensearch.php" /> 
    <link rel="shortcut icon" href="<?php print $this->base;?>/img/icon.ico" /> 
	<link rel="apple-touch-icon-precomposed" href="<?php print $this->base;?>/img/m/apple-touch-icon.png" /> 
	<?php echo $scripts_for_layout ?>
</head>
<body>

<div class="Header Section">
	<div id="TopBar"><a href="<?php print $this->base;?>/"><img id="mainLogo" src="<?php print $this->base;?>/img/m/logo_small.png" border="0" width="200" height="45" /></a></div>
</div>
<div class="Content Section">
<?php echo $content_for_layout; ?>
</div>

<div class="Footer">
	<p>&copy; 2010 to 2011, Jeffrey Stanton.<br /><?php echo $html->link('credits',array('controller'=>'help','action'=>'index','credits'));?></p>
</div>

</body>
</html>