<?php

//security-related functions that could be useful to multiple classes
class Security {
	const alphaChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	const alphaNumChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	const alphaNumSymbolChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=!@#\$%^&*()_+~[]{}:<>,./\|\"";

	//generates a random mixed-case salt (with only letters) of the specified length
	public static function alphaSalt($length){
		return self::generateSalt($length,self::alphaChars);
		}
	//generates a random mixed-case-alphanumeric-only salt of the specified length
	public static function alphaNumSalt($length){
		return self::generateSalt($length,self::alphaNumChars);
		}
	//generates a random mixed-case-alphanumeric-symbol salt of the specified length
	public static function alphaNumSymbolSalt($length){
		return self::generateSalt($length,self::alphaNumSymbolChars);
		}
	//generates a random salt of the specified length
	public static function generateSalt($length, $saltchars){
		$salt = "";
		for($i=0; $i<$length; $i++){
			$index = rand(0,strlen($saltchars)-1);
			$salt .= $saltchars[$index];
			}
		return $salt;
		}
	//generates a secure hash of the specified password, with a salt if specified
	public static function hash($password,$salt=null){
		if($salt==null){ $salt=""; }
		return sha1($salt.$password);
		}
	//converts hours/minutes/seconds into seconds
	public static function interval($hours=0,$minutes=0,$seconds=0){
		return (($hours*60)+$minutes)*60 + $seconds;
		}
	//provides UNIX timestamp for date/time that is specified distance in future
	public static function futureTimestamp($hours=0,$minutes=0,$seconds=0){
		return time()+self::interval($hours,$minutes,$seconds);
		}
	
	}

?>