<?php 
	header("Location: ../");
	die("Accounts are not editable at this time.  Sorry!");

	$root = ".";
	for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
		if($root=="."){ $root=".."; }else{ $root = "../$root"; }
		}
		
	// dependencies
	require_once("$root/_framework/lib/account.php");
	require_once("$root/_framework/lib/key3po/Key3PO.php");
	require_once("$root/_framework/lib/UserManager.php");
	require_once("$root/_framework/lib/Debug.php");
	require_once("$root/_framework/lib/HTTPManager.php");
	require_once("$root/_framework/lib/countries.php");
	require_once("$root/_framework/lib/Log.php");
	
	// require login
	HTTPManager::requireSSL(true);
	if(!isLoggedIn()){
		header("Location: ../signin/?next=/account/edit/");
		die("You need to sign in to see this page.  <a href=\"$next\">Continue</a>");
		}
	// obtain current data from DB (for display, change detection)
	$user = getLoggedInUser();
	$currentData = UserManager::getInformation($user);

	$updated = false;

	if(isset($_POST['submit'])){ //user submitted changes
		//list of fields that are not allowed to be updated
		$ignoredFields = array('name_first','name_last','email','year_original','year_expected','uid');
		//figure out which fields the user changed
		$changedFields = array();
		foreach($currentData as $attr=>$oldVal){
			if(in_array($attr,$ignoredFields)){ continue; } //ignored fields cannot be updated
			$newVal = @$_POST[$attr]; //new value (POSTed by user)
			if(in_array($attr,array('year_isaway','away_hide','home_hide','phone_hide','im_hide'))){
				if($newVal==NULL){ $newVal = 0; }
				}
			//if($newVal===false){ $newVal = 0; }
			//if($oldVal=="0"&&$newVal==NULL){ continue; } //didn't actually change
			if($newVal!=$oldVal){ //value changed
				$changedFields[] = $attr; //add field name to "changed fields" array
				$currentData[$attr] = $newVal; //pull new value in to data (to be output to page)
				}	
			}
		
		// build update array (to pass to DB)
		$updates = array();
		foreach($changedFields as $attr){
			$updates[$attr] = $currentData[$attr];
			}
		
		//was any data updated? if so, writeback to database
		$updated = (count($changedFields)!=0);
		if($updated){
			@Log::info("ACCOUNT",'User '.getLoggedInUser().' ('.getLoggedInUserFullName().') updated their profile: '.addslashes(join(",",$changedFields)));
			UserManager::updateInformation($user, $updates);
			}

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
	$pageTitle = "Olin Directory &raquo; My Profile";
	$current = "Profile";
	$accessPermissions = "signin";

	$customScriptIncludes = array(
		"$root/_framework/js/jquery.maskedinput.js",
		"$root/_framework/js/jquery.ajFileUpload.js",
		"interface.js"
		);
	
	$customScript = <<<ENDCUSTOMSCRIPT
$(document).ready(function(){
	$("#updated").hide();
	$("#updated").fadeIn();
	setTimeout(function(){\$("#updated").fadeOut();},3000);
	});
ENDCUSTOMSCRIPT;
	
	if(!$updated){ unset($customScript); }
	
	include("$root/_framework/header.php");
/* ##################################################### */ ?>

<form method="post" action="./" class="Formatted" name="edit_details" id="edit_details" style="font-size: 95%;">
<div>
	<span style="font-weight:bold; font-size: 150%;"><?php pString('name_first')?> <?php pString('name_last'); ?></span>&ensp;
	<em style="font-size: smaller;"><?php pString('year_expected'); ?></em>&emsp;
	<label for="name_nick">Nickname: </label><input type="text" class="Medium" name="name_nick" id="name_nick" maxlength="255" value="<?php pString('name_nick'); ?>" />
	<?php if($updated){ ?><div id="updated" style="position: absolute; color: white; padding: 0.5em; margin-top: 0.2em; background-color: #0C0; width: 20em; border: 6px solid #fff; -moz-border-radius: 10px;"><strong>Changes saved.</strong></div><?php } ?>
</div>

<div class="Blocks">

<div>

	<h3>Current Address</h3>
	<div id="contact">
		<div id="olin">
			<label for="room_bid">Room</label><select class="Medium" id="room_bid" name="room_bid">
				<option>Pick dorm...</option>
				<option value="EH"<?php print pSelected('room_bid','EH');?>>East Hall</option>
				<option value="WH"<?php print pSelected('room_bid','WH');?>>West Hall</option>
				</select>
				<input type="text" name="room_number" id="room_number" maxlength="255" size="6" value="<?php pString('room_number'); ?>" /><br />
			<label for="olin_mbox">Mailbox #</label><input type="text" name="olin_mbox" id="olin_mbox" class="Narrow" maxlength="255" value="<?php pString('olin_mbox'); ?>" /> (Just the number)
			</div>
		<input type="checkbox" value="1" id="year_isaway" name="year_isaway" class="Labeled" <?php pChecked('year_isaway'); ?> /><label for="year_isaway" class="Wide">I am currently away from Olin</label><br />
		<div id="away">
			<div id="away_details" class="Wide">
				<label for="away_street">Street</label><input type="text" name="away_street" id="away_street" maxlength="255" value="<?php pString('away_street'); ?>" /><br />
				<label for="away_apt">Suite/Apt</label><input type="text" name="away_apt" id="away_apt" maxlength="255" value="<?php pString('away_apt'); ?>" /><br />
				<label for="away_city">City</label><input type="text" name="away_city" id="away_city" maxlength="255" value="<?php pString('away_city'); ?>" /><br />
				<label for="away_state">State</label><select id="away_state" name="away_state"><?php print_US_states(pRaw('away_state')); ?></select><br />
				<label for="away_zip">Zip</label><input type="text" name="away_zip" id="away_zip" maxlength="255" value="<?php pString('away_zip'); ?>" /><br />
				<label for="away_country">Country</label><select id="away_country" name="away_country"><?php print_HTML_country_options(pDef(pRaw('away_country'),'US')); ?></select>
				</div>
			<input type="checkbox" value="1" id="away_hide" name="away_hide" class="Labeled" <?php pChecked('away_hide'); ?> /><label for="away_hide" class="Wide">Do not list my current address.</label><br />
			</div>
		</div>	
	
	<div id="home">
	<h3>Home Address</h3>
		<div id="home_details" class="Wide">
				<label for="home_street">Street</label><input type="text" name="home_street" id="home_street" maxlength="255" value="<?php pString('home_street'); ?>" /><br />
				<label for="home_apt">Suite/Apt</label><input type="text" name="home_apt" id="home_apt" maxlength="255" value="<?php pString('home_apt'); ?>" /><br />
				<label for="home_city">City</label><input type="text" name="home_city" id="home_city" maxlength="255" value="<?php pString('home_city'); ?>" /><br />
				<label for="home_state">State</label><select id="home_state" name="home_state"><?php print_US_states(pRaw('home_state')); ?></select><br />
				<label for="home_zip">Zip</label><input type="text" name="home_zip" id="home_zip" maxlength="255" value="<?php pString('home_zip'); ?>" /><br />
				<label for="home_country">Country</label><select id="home_country" name="home_country"><?php print_HTML_country_options(pDef(pRaw('home_country'),'US')); ?></select>
				</div>
			<input type="checkbox" value="1" id="home_hide" name="home_hide" class="Labeled" <?php pChecked('home_hide'); ?> /><label for="home_hide" class="Wide">Do not list my home address.</label><br />
		</div>

</div><div>

	<div id="phone">
	<h3 style="width: 17em">Phone Numbers</h3>
		<div id="phone_details" class="Medium">
			<label for="phone_number" class="Icon phone_number">Phone</label><input type="text" name="phone_number" id="phone_number" maxlength="255" value="<?php pString('phone_number'); ?>" /><br />
			</div>
		<input type="checkbox" value="1" id="phone_hide" name="phone_hide" class="Labeled" <?php pChecked('phone_hide'); ?> /><label for="phone_hide" class="Wide">Do not list my phone number.</label><br />
		<div id="phone_warning">
			<div>Not listing a phone number reduces the usefulness of the Directory.  Please consider entering your phone number.</div>
			</div>
		</div>
	
	<div id="im">
	<h3 style="width: 17em">Instant Messenger</h3>
		<div id="im_details" class="Medium">
			<label for="im_aol" class="Icon IM AIM">AIM</label><input type="text" name="im_aol" id="im_aol" maxlength="255" value="<?php pString('im_aol'); ?>" /><br />
			<label for="im_gtalk" class="Icon IM GTalk">GTalk</label><input type="text" name="im_gtalk" id="im_gtalk" maxlength="255" value="<?php pString('im_gtalk'); ?>" /><br />
			<label for="im_icq" class="Icon IM ICQ">ICQ</label><input type="text" name="im_icq" id="im_icq" maxlength="255" value="<?php pString('im_icq'); ?>" /><br />
			<label for="im_msn" class="Icon IM MSN">MSN</label><input type="text" name="im_msn" id="im_msn" maxlength="255" value="<?php pString('im_msn'); ?>" /><br />
			<label for="im_skype" class="Icon IM Skype">Skype</label><input type="text" name="im_skype" id="im_skype" maxlength="255" value="<?php pString('im_skype'); ?>" />
			</div>
		<input type="checkbox" value="1" id="im_hide" name="im_hide" class="Labeled" <?php pChecked('im_hide'); ?> /><label for="im_hide" class="Wide">Do not list my IM info.</label><br />
		</div>
	
</div><div>
	<h3 style="width: 210px;">Profile Photo</h3>
		<div id="photo_details">
			<img src="<?php print "$root/_framework/ProfilePic.php?u=$user";?>" width="210" height="280" border="0" id="headshot" /><br /><br />
			<a href="#" id="uploadHeadshot">Upload New Photo &raquo;</a>
			<span id="uploadStatus">Uploading Image...</span>
			</div>
		</div>

</div>
<div id="saveme" style="clear: both; text-align: right; float: left; width: 36em; margin-top: 1em;">
	<input type="submit" id="submit" name="submit" value="Save Changes" />
	</div>
</div>


</form>


<?php /* ##################################################### */
	include("$root/_framework/footer.php");
	?>
