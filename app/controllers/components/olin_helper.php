<?php
class OlinHelperComponent extends Object {
	
	function endsWith($string, $ending){
		if(strlen($ending)>strlen($string)){ return false; }
		return (substr_compare($string, $ending, -strlen($ending), strlen($ending)) !== 0);
	}
	
	//guesses first and last name from provided email address
	//Olin addresses are usually in the form firstname.lastname@(students|alumni).olin.edu
	//Returns a two-element string array(firstName, lastName), or NULL if no guess could be made
	function guessNameFromEmail($email){
		if(!$email){ return null; }
		if(self::endsWith($email,'@alumni.olin.edu')||self::endsWith($email,'@students.olin.edu')){
			//Olin email addresses are in the form of firstname.lastname@...
			//with the exception of aliases, which don't contain a period
			$matches = array();
			preg_match("/^(.+)\.(.+)\@.+$/",$email,$matches);
			if(count($matches)!=3){ return null; }
			return Array('firstName'=>ucfirst($matches[1]),'lastName'=>ucfirst($matches[2]));
		}
		return null;
	}
	
	//converts a wildcard-style (*@*.example.com and so on) pattern
	//into a preg-compatible pattern, including surrounding slashes and ^/$
	function flat2pregex($flat) {
		return '/^'.str_replace(array('.','*'),array('\\.','.*'),$flat).'$/';
	}
	
	//email matches the flat (wildcard) regex
	function wildcard_match($pattern, $subject) {
		return preg_match(self::flat2pregex($pattern), $subject);
	}
	
	//checks registration to see if the email is acceptable for registration
	function canEmailRegister($email){
		Configure::load('OlinDirectory');
		$allowed = Configure::read('join.allowed');
		foreach($allowed as $pattern){
			if(self::wildcard_match($pattern, $email)) return true;
		}
		return false;
	}
}
?>
