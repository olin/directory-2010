<?php
$c = strtolower($this->params['controller']);
$a = strtolower($this->params['action']);
$ca = "$c/$a";
$isIndex = $a=='index';
$isAdmin = isset($this->params['admin']) || ($c=='admin' && $isIndex);
$isAdminUser = @$isAdminUser;
$tp = ' class="ThisPage"';
$user = @$session->read('Auth.Account.firstName')." ".@$session->read('Auth.Account.lastName');
$isSignedIn = ($user!==false && $user!==null && $user!==' ');
$showAdminSubmenu = ($isAdmin && $isAdminUser);

if($isAdmin && $isAdminUser && isset($_GET['dump'])){
	echo '<pre>';
	print(h(print_r($this,true)));
	die('</pre>');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?php print $this->base;?>/css/Main.css" type="text/css" />
	<script language="JavaScript" type="text/javascript">
		/* cake-to-js variable pass thru */
		document.cakeBase = "<?php print $this->base; ?>";
	</script>
	<title>OlinDirectory<?php if($isAdmin){echo '(ADMIN)';}?> : <?php echo $title_for_layout ?></title>
	<script language="JavaScript" type="text/javascript" src="<?php print $this->base;?>/js/lib/jquery-1.4.3.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php print $this->base;?>/js/lib/jquery.jgrowl/jquery.jgrowl_compressed.js"></script>
	<link rel="search" type="application/opensearchdescription+xml" title="OlinDirectory" href="<?php print $this->base;?>/opensearch.php" /> 
    <link rel="shortcut icon" href="<?php print $this->base;?>/img/icon.ico" /> 
	<link rel="apple-touch-icon-precomposed" href="<?php print $this->base;?>/img/m/apple-touch-icon.png" /> 
	<?php echo $scripts_for_layout ?>
</head>
<body>


<?php

function menuItem($isCurrent, $base, $url, $text, $cssClass=null){
	if($cssClass==null){ $cssClass=''; }
	if($isCurrent){ $cssClass = $cssClass.'Current'.(strlen($cssClass)==0?'':' '); }
	print(
		'<a href="'.htmlspecialchars($base.$url) . '" ' .
		(strlen($cssClass)>0?'class="'.htmlspecialchars($cssClass).'" ':'') . '>' .
		htmlspecialchars($text).'</a>'
	);
}

?>

<div class="Header Section">
	<div id="TopBar"><a href="<?php print $this->base;?>/"><img id="mainLogo" src="<?php print $this->base;?>/img/logo.png" border="0" width="354" height="80" /></a></div>
	<div id="MenuBar"<?php if($showAdminSubmenu){ print ' class="NoBorder"'; }?>>
	<?php
		menuItem($c=='search',$this->base,'/','home');
		menuItem($ca=='search/mobile',$this->base,'/m/','mobile');
		if(!$isSignedIn){
			menuItem($ca=='accounts/signin',$this->base,'/account/signin','sign in');
			menuItem($c=='join_requests',$this->base,'/join','join');
		}else{
			if($isAdminUser){
				menuItem($isAdmin,$this->base,'/admin/','admin');
			}
			menuItem($ca=='user_details/edit',$this->base,'/account/edit',$user);
			menuItem($ca=='accounts/signout',$this->base,'/account/signout','sign out');
		}
		
		print('<div id="HelpMenu">');
		menuItem($c=='help',$this->base,'/help','help');
		menuItem($c=='feedbacks',$this->base,'/feedbacks','feedback');
		menuItem($c=='api',$this->base,'/api','api');
		print('</div>');
		?>
	</div>

<?php if($showAdminSubmenu){ ?>
<div id="MenuBar"<?php if($showAdminSubmenu){ print ' class="SubMenu"'; }?>>
<?php
	menuItem($c=='accounts',$this->base,'/admin/Accounts','Accounts');
	menuItem($c=='userdetails',$this->base,'/admin/UserDetails','User Details');
	menuItem($c=='joinrequests',$this->base,'/admin/JoinRequests','Join');
	menuItem($c=='resetrequests',$this->base,'/admin/ResetRequests','Reset');
	menuItem($c=='buildings',$this->base,'/admin/Buildings','Buildings');
	menuItem($c=='feedbacks',$this->base,'/admin/Feedbacks','Feedback');
?>
</div>
<?php } ?>
</div>
<?php
$flash = $session->flash();
if($flash){
?>
<script language="JavaScript" type="text/javascript">
$.jGrowl('<?php echo addslashes($flash);?>',{ life: 600 });
</script>
<?php } ?>
<div class="Content Section">
<?php echo $content_for_layout; ?>
</div>

<div class="DetailsView">
	<div class="Lightbox"></div>
	<div class="LightboxCloser">x</div>
</div>

<div class="Footer">
	<p>OlinDirectory is copyright &copy; 2010-2011 Jeffrey Stanton.  Built with <?php echo $html->link('great third-party libraries',array('controller'=>'help','action'=>'index','#credits'));?></p>
	<p>Profile images uploaded are property of their respective owners and are not for re-use.</p>
</div>

</body>
</html>