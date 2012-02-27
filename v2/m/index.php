<?php
if(!isset($root)){ $root = "."; for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){ if($root=="."){ $root=".."; }else{ $root = "../$root"; } } }

require_once("$root/_framework/lib/UserManager.php");
require_once("$root/_framework/lib/Status.php");
require_once("$root/_framework/lib/Log.php");
require_once("$root/_framework/lib/account.php");
/*
$accessAllowed = Account::hasPermission("mobile");
if(!$accessAllowed){
	die("Access to this feature is not available yet.");
}
*/

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0" />
<title>OlinDirectory Mobile</title>
<link rel="shortcut icon" href="<?php print $root;?>/_framework/opensearch/icon.ico" />
<link rel="apple-touch-icon" href="<?php print $root;?>/img/apple-touch-icon.png" />
<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<?php

//======================================================

$q = @$_REQUEST['q'];
$matches = array();
$error = false;
$hasQuery = ($q!==false && $q!="");

if($hasQuery){
	@Log::info("MQUERY","/m/ query for \"".addslashes($q)."\"");
	$matches = UserManager::getMatches($q);

	if(Status::isError($matches)){
		@Log::error("MQUERY",'/m/ query for "'.addslashes($q).'" : Cannot fetch matches: '.$matches['message']);
		$error = $matches;
		$matches = array();
		}

	$numMatches = count($matches);

	//post-process matches for essential fields
	for($i=0; $i<$numMatches; $i++){
		$e = $matches[$i];
		$e = DataManager::maskFields($e); //remove data hidden by user preferences
		$hsfile = "$root/_headshots/uid/".$e['uid'].".jpg";
		if(file_exists($hsfile)){ $e['photo_path'] = "_headshots/uid/".$e['uid'].".jpg";
			}else{ $e['photo_path'] = "_headshots/unknown.jpg";
			}
		$matches[$i] = $e;
		}

	}

?>


<body onload="document.getElementById('q').focus();">

<div class="Header"><a href="../"><img src="logo.png" width="150" height="48" /></a></div>
<div class="Contents">
	Now for mobile phones, too!<br />
	<form action="./" method="post" name="query" style="text-align: right">
	<input type="text" class="FullWidth" id="q" name="q" value="" /><br />
	<input type="submit" value="Search" />
	</form>
	</div>
<?php

if($hasQuery){
	if($error!==false){
?>
<div class="Search Error">A problem occurred while searching.  Please try again later.</div>
<?php
		}
	if($numMatches==0){
?>
<div class="Search Warning">No matches for "<?php print htmlspecialchars($q); ?>"</div>
<?php
	}else if(false){
?>
<div class="Search"><?php print $numMatches.($numMatches==1?" match":" matches"); ?> for "<?php print htmlspecialchars($q); ?>"</div>
<?php
		}
	}
?>

<?php

foreach($matches as $match){
	$fullname = @$match['name_first'].' '.@$match['name_last'];
	$phone = @$match['phone_number'];
	$phoneshort = @preg_replace('/[^0-9]+/','',$phone);

	$roombid = @$match['room_bid'];
	$roomnum = @$match['room_number'];

	$room = "";
	if($roombid){ $room = $roombid; }
	if($roomnum){
		if($roombid){ $room .= " "; }
		$room .= $roomnum;
		}

	$year = @$match['year_expected'];

	$line2 = "";
	if($phone){ $line2 = '<a href="tel:'.$phoneshort.'">'.htmlspecialchars($phone).'</a>'; }
	if($room){
		if($phone){ $line2 .= ", "; }
		$line2 .= $room;
		}
	if($line2==""){ $line2 = "<em>No info listed</em>"; }


?>
<div class="Result">
	<h3><?php print $fullname; ?></h3><?php if($year){ ?><em><?php print $year; ?></em><?php } ?><br />
	<?php print $line2; ?>
	</div>
<?php
}
?>

<div class="Result"></div>
<p style="clear: both;font-size: 75%; margin-left: 20px;">Copyright &copy; 2009-2010 <a href="./?q=jstanton">Jeffrey Stanton</a><br /><br /><br /><br /></p>


</body>
</html>
