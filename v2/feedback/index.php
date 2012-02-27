<?php
	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
		
	// dependencies
	require_once("$root/_framework/lib/account.php");
	require_once("$root/_framework/lib/UserManager.php");
	require_once("$root/_framework/lib/Debug.php");
	
	// require login
	$currentData = array();
	if(isLoggedIn()){
		$user = getLoggedInUser();
		$currentData = UserManager::getInformation($user);
		}
	
		//utility function for printing values
	function pRaw($field){
		global $currentData;
		return @$currentData[$field];
		}
	function pBool($field){
		return (bool)pRaw($field);
		}
	function pChecked($field){
		print pBool($field)?' checked ':'';
		}
	function pSelected($field,$val){
		print (pRaw($field)==$val)?' selected ':'';
		}
	function pString($field){
		print htmlspecialchars(pRaw($field));
		}
	function pDef($val,$alt){
		if($val==NULL||$val==""){ return $alt; }
		return $val;
		}
	
	//now display page
	$pageTitle = "Olin Directory &raquo; Feedback &amp; Problems";
	$current = "Feedback";
	$accessPermissions = "none";
	$loadFocusSelector = isLoggedIn() ? "#comments" : "#name";
		
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<h2>Feedback &amp; Problems</h2>

<form method="post" action="send.php" class="Formatted" name="give_feedback" id="give_feedback" style="font-size: 95%;">
<div>
	<div class="Wider">
		<strong>Your Contact Info</strong><br /><br />
		<label for="name">Name</label><input type="text" name="name" id="name" maxlength="255" value="<?php if(isLoggedIn()){pString('name_first'); print(" "); pString('name_last');} ?>" /><br />
		<label for="email">Email</label><input type="text" name="email" id="email" maxlength="255" value="<?php pString('email'); ?>" /><br /><br />
		</div>
	<div>
		<strong>Comments &amp; Suggestions:</strong><br /><br />
		<textarea name="comments" id="comments" cols="60" rows="10"></textarea><br /><br />
		<input type="submit" id="submit" name="submit" value="Send Feedback &raquo;" />
		</div>
</div>
</form>


<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
