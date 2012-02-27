<?php
	//find site root relative path
	if(!isset($root)){ $root = "."; for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){ if($root=="."){ $root=".."; }else{ $root = "../$root"; } } }
	
	require_once("$root/_framework/conf/Config.php");

	session_start();
	if(!isset($_SESSION['userLoggedIn'])){
		setLoggedIn(false);
		}
	function setLoggedIn($loggedIn=true, $userData=null){
		$_SESSION['userLoggedIn'] = $loggedIn;
		//add or remove session data if logging in or logging out (respectively)
		if($userData==null){
			unset($_SESSION['userData']);
		}else{
			$_SESSION['userData'] = $userData;
			}
		}
	function getLoggedInUser(){
		return @$_SESSION['userData']['uid'];
		}
	function getLoggedInUserFullName(){
		return @$_SESSION['userData']['firstName'].' '.@$_SESSION['userData']['lastName'];
		}
	function isLoggedIn(){
		return @$_SESSION['userLoggedIn'];
		}

	final class Account {
		
		public static function hasPermission($permission){
			switch($permission){
				case null:
				case false:
				case "none":
					return true;
				case "signin":
					return isLoggedIn();
				case "admin":
					return isLoggedIn() && self::isAdminUser(getLoggedInUser());
				case "mobile":
					return isLoggedIn() && self::isMobileUser(getLoggedInUser());
				default:
					return false;
				}
			}
		
		public static function isAdminUser($uid){
			$allowed = strtolower(Config::get('site.admin.uid'));
			$allowed = explode(",",$allowed);
			return in_array(strtolower($uid),$allowed);
			}
		
		public static function isMobileUser($uid){
			$allowed = strtolower(Config::get('site.mobile.allow'));
			if($allowed=="*"){ return true; }
			$allowed = explode(",",$allowed);
			return in_array(strtolower($uid),$allowed);
			}
		
		}
	
	?>