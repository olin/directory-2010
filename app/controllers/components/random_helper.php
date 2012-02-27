<?php
class RandomHelperComponent extends Object {
 
	const base32 = "abcdefghijklmnopqrstuvwxyz234567";
	const alphaNumeric = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	const alphaNumericSymbol = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=!@#\$%^&*()_+~[]{}:<>,./\|\"";
	
/**
 * Random string generator function
 *
 * This function will randomly generate a password from a given set of characters
 *
 * @param int = 8, length of the password you want to generate
 * @param string = 0123456789abcdefghijklmnopqrstuvwxyz all possible values
 * @return string, the password
 */     
	function string($length = 8, $possible = self::base32) {
		// initialize variables
		$password = "";
		$i = 0;
 
		// add random characters to $password until $length is reached
		while ($i < $length) {
			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
 
			// we don't want this character if it's already in the password
			if (!strstr($password, $char)) { 
				$password .= $char;
				$i++;
			}
		}
		return $password;
	}
}
?>
