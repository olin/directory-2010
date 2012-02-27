<?php
$root = ".";
for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
	if($root=="."){ $root=".."; }else{ $root = "../$root"; }
	}

// dependencies
require_once("$root/_framework/lib/account.php");
require_once("$root/_framework/lib/HTTPManager.php");
require_once("$root/_framework/lib/Mobile.php");

function need($args){
	foreach($args as $arg){
		if(!isset($_REQUEST[$arg])){
			die("ERROR Missing $arg");
			}
		}
	return true;
	}

if(!isset($_REQUEST['a'])){
	header("Location: ../");
	die();
	}

switch(@$_REQUEST['a']){
	//Request a new confirmation code for the currently-signed-in user
	case 'req':
		//require login
		HTTPManager::requireSSL(true);
		if(!isLoggedIn()){ die("ERROR please sign in"); }
		// obtain current data from DB (for display, change detection)
		$uid = getLoggedInUser();
		$code = MobileEmail::requestCode($uid);
		die($code);
		break;
	//Check the given code to see what email address has been associated with that code, if any (if non, returns "PENDING")
	case 'chk':
		need(array('c'));
		$code = $_REQUEST['c'];
		$res = MobileEmail::isCodeConfirmed($code);
		die($res!==false ? $res : "PENDING");
		break;
	//Associate the given email with the given code; silently fails if code or email are not valid
	case 'val':
		need(array('c','e'));
		$code = $_REQUEST['c'];
		$email = $_REQUEST['e'];
		MobileEmail::validateCode($code,$email);
		die('CONFIRMED');
		break;
	//Removes active AND pending records with the given code for the currently-signed-in user from the database
	case 'delc':
		//require login
		HTTPManager::requireSSL(true);
		if(!isLoggedIn()){ die("ERROR please sign in"); }
		// obtain current data from DB (for display, change detection)
		$uid = getLoggedInUser();
		need(array('c'));
		$code = $_REQUEST['c'];
		MobileEmail::deleteCode($uid,$code);
		die('DONE');
		break;
	//Removes active AND pending records with the given email for the currently-signed-in user from the database 
	case 'dele':
		//require login
		HTTPManager::requireSSL(true);
		if(!isLoggedIn()){ die("ERROR please sign in"); }
		// obtain current data from DB (for display, change detection)
		$uid = getLoggedInUser();
		need(array('e'));
		$email = $_REQUEST['e'];
		MobileEmail::deleteEmail($uid,$email);
		die('DONE');
		break;
	//Returns ALLOW if the specified email has been verified; returns DENY otherwise
	case 'sec':
		need(array('e'));
		$email = $_REQUEST['e'];
		die(MobileEmail::isEmailConfirmed($email) ? "ALLOW" : "DENY");
		break;
	//Output error message and die
	default:
		die("ERROR unknown ".@$_REQUEST['a']);
	}


?>