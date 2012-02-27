<?php
	require_once("_framework/lib/OlinAD.php");
	require_once("_framework/lib/HTTPManager.php");

	HTTPManager::requireSSL(true);

	function badCredentials($user=null, $baseURL="./"){
		sleep(0.4);
		$user = (!@$user) ? "" : ("&u=".urlencode($user));
		$newURL = "$baseURL?e=badCredentials$user";
		header("Location: $newURL");
		die("Invalid username or password: <a href=\"$newURL\">please try again</a>.");
		}

	@session_start();
	//if user is revisiting (e.g. because passwords dont match), fetch info out of the session variable.
	//get username and password from POST from previous page
	if(@$_POST['username'] && @$_POST['password']){
		$user = $_POST['username'];
		$pass = $_POST['password'];
	}else{
		//missing username/password
		badCredentials(@$_REQUEST['username']);
		}
	$info = OlinAD::getADInfo($user,$pass);
	if(Status::isError($info)){
		//invalid username/password
		if(Status::isCredentialError($info)){
			badCredentials($user);
			}
		}

	include("_framework/header.php");

//##########################################################################
/* An unhandled error occurred; print the error message and die. */
if(Status::isError($info)){
	@session_destroy();
	$errorString = Status::formatError($info,true);
?>
<h2>Olin Account Expiration Checker</h2>
<p class="Error"><?php print htmlspecialchars($errorString); ?></p>

<?php
//##########################################################################
}else{ /*user does not exist yet, offer to create a new one*/
?>

<h2>Olin Account Expiration Checker</h2>
<?php if(@$info['pwdExpires']){ ?>
<p>Your Olin domain password expires on <strong><?php print(@date("F j, Y",$info['pwdExpires'])); ?></strong>.<br />If you'd like, you can <a href="<?php print "ical/?t=$info[pwdExpires]"; ?>" target="_blank">download an iCal reminder</a> to put in your calendar [new window].</p>
<?php }else{ ?>
<p>Something went wrong and we couldn't figure out when your password expires.  Sorry!</p>
<?php } ?>

<?php
	} //end of create new user section
//##########################################################################
@include("$root/_framework/footer.php");
?>
