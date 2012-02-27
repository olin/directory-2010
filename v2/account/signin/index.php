<?php
	header("Location: ../");
	die("Profile editing currently not allowed. Sorry.");

	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
	
	/* dependencies */
	require_once("$root/_framework/lib/account.php");
	require_once("$root/_framework/lib/key3po/Key3PO.php");
	require_once("$root/_framework/lib/UserManager.php");
	require_once("$root/_framework/lib/HTTPManager.php");
	require_once("$root/_framework/lib/Log.php");

	HTTPManager::requireSSL(true);

	/* page template */
	$pageTitle = "Olin Directory &raquo; Sign In";
	$current = "Sign In";
	$accessPermissions = "none";
	
	$customScript = <<<ENDCUSTOMSCRIPT
/*** javascript custom script ***/	
$(document).ready(function(){
	setTimeout(function(){
		if($("#username").val()==''){
			$("#username").focus();
		}else{
			$("#password").focus();
			}
		},20);
	});
/*** end javascript custom script ***/
ENDCUSTOMSCRIPT;
	
	$next = "$root/account/edit/";
	if(isset($_REQUEST['next'])){ //relative return-path
		$next = $_REQUEST['next'];
		if( @$_REQUEST['abs']!='1' && strlen($next)>0 && substr($next,0,1)=="/" ){
			$next = "$root$next";
			}
		}
	
	if(isLoggedIn()){
		header("Location: $next");
		die("You are already logged in.  <a href=\"$next\">Continue</a>");
		}
	
	$invalid = false;
	
	$user = @$_POST['username'];
	$pass = @$_POST['password'];
	
	if($user && $pass){
		$k3poData = Key3PO::signIn($user,$pass);
		if($k3poData){ //signed in?
			//print "<pre>"; print_r($k3poData); die();
			if(!UserManager::userExists($user)){ //setup user in DB if not existing
				$created = UserManager::createUser($k3poData);
				//var_dump($created);
				}
			setLoggedIn(true,$k3poData);
			@Log::info("ACCOUNT",'User '.getLoggedInUser().' ('.getLoggedInUserFullName().') signed IN');
			header("Location: $next");
			//print("<pre>"); print_r($k3poData);
			die("<br />You are signed in.  <a href=\"$next\">Continue</a>");
			}
		$invalid = true;
		}
	
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<?php if($invalid){ ?>
Invalid username or password.<br />
If you forgot your password, please <a href="<?php htmlprint(Key3PO::getForgotLink($user)); ?>" target="_blank">reset it</a>.<br /><br />
<?php } ?>

<?php $rvar = array();
if(isset($_GET['abs'])){ $rvar[] = "abs=".urlencode($_GET['abs']); }
if(isset($_GET['next'])){ $rvar[] = "next=".urlencode($_GET['next']); }
$formArgs = (count($rvar)>0) ? ('?'.join('&',$rvar)) : '';

?>

<form class="Key3PO Login" action="./<?php print $formArgs; ?>" method="post"><div class="AlignLeft">
	<p>Sign in with your <span class="Account">Key3PO</span> account.</p>
	<div class="AlignRight">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php htmlprint($user);?>"/>
		</div>
	<div class="AlignRight">
		<label for="password">Password</label>
		<input type="password" name="password" id="password" />
		</div>
	<div class="AlignRight">
		<input type="hidden" name="next" id="next" value="<?php htmlprint($next);?>" />
		<input class="Submit" type="submit" name="submit" value="Sign In &raquo;" />
		<p class="FloatLeft Reminder"><a href="<?php htmlprint(Key3PO::getForgotLink($user)); ?>" target="_blank">Forgot your password?</a></p>
		</div>
	</div></form>


<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
