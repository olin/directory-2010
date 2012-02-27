<?php

//find site root relative path
if(!isset($root)){ $root = "."; for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){ if($root=="."){ $root=".."; }else{ $root = "../$root"; } } }

require_once("HttpPost.php");
require_once("json/json.php");
require_once("$root/_framework/conf/Config.php");


/* Allows interaction with Key3PO via its API */
class Key3PO {
	
	private static $SIGNIN = "/api/signin/";
	private static $FORGOT = "/account/reset/";
	
	/* generates a URL link for users to go to Key3PO and reset their password */
	public static function getForgotLink($user=null){
		$url = Config::get('key3po.baseurl') . self::$FORGOT;
		if($user){ $url .= "?u=".urlencode($user); }
		return $url;
		}
	
	/* signs the user in, gives null on error, or true/false if user+pass were valid or not */
	public static function signIn($user,$pass){
		//build request manager
		$fp = new HttpPost(Config::get('key3po.baseurl') . self::$SIGNIN);
		$fp->addFields(array("username"=>$user, "password"=>$pass));
		//POST data to sign-in form and get response
		$body = $fp->submit();
		//decode, parse and return boolean result
		$data = json_decode_assoc($body);
		if(!$data || !isset($data['status'])){ return null; }
		if($data['status']!="ok"){ return null; }
		if(!$data['accepted']){
			return false;
		}else if(isset($data['userinfo'])){
			return (array)$data['userinfo'];
		}else{
			return true;
			}
		}
		
	}
	
?>
