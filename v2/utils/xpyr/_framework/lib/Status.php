<?php

class Status {
	
	private static function getStatus($info,$message=null,$status){
		if(!is_array($info)){ $info = array("message"=>$info); }
		if($message!=null){ $info["message"] = $message; }
		$info["status"] = $status;
		return $info;
		}
	
	public static function formatError($info=null, $returnIt=true){
		if(!self::isError($info)){ return false; }
		$errno = @$info['errno'];
		$errsrc = @$info['errsrc'];
		$message = @$info['message'];
		$text = "Encountered error $errno in \"$errsrc\": $message";
		if($returnIt){ return $text; }else{ die($text); }
		}
		
	
	public static function error($info=null,$message=null){
		return(self::getStatus($info,$message,"error"));
		}
		
	public static function ok($info=null,$message=null){
		return(self::getStatus($info,$message,"ok"));
		}
	
	public static function isError($info){
		if( !is_array($info) || !isset($info['status']) ){ return false; }
		if($info['status']=="error"){ return true; }
		return false;
		}
	
	public static function isOK($info){
		if( !is_array($info) || !isset($info['status']) ){ return false; }
		if($info['status']=="ok"){ return true; }
		return false;
		}
	
	public static function isCredentialError($info){
		if( !self::isError($info) || !isset($info['message']) ){ return false; }
		if($info['message']=="Invalid credentials"){ return true; }
		return false;
		}
	
	}

class JSONStatus {
	
	public static function jsonDie($info){
		die(json_encode($info));
		}
	
	public static function error($info,$message=null){
		self::jsonDie(Status::error($info,$message));
		}
		
	public static function ok($info,$message=null){
		self::jsonDie(Status::ok($info,$message));
		}
	
	}

?>