<?php

	if(!isset($root)){
		$root = ".";
		for($n=0; $n<100 && !@file_exists("$root/_framework"); $n++ ){
			if($root=="."){ $root=".."; }else{ $root = "../$root"; }
			}
		}
	
	require_once("$root/_framework/conf/Config.php");

	function getCurrentAddress(){
		$uri = dirname($_SERVER['SCRIPT_NAME']).'/change.php';
		return "http://".$_SERVER['SERVER_NAME'].$uri;
		}	

	function email($from,$to,$subject,$message,$ishtml=false){
		@require_once('Mail.php');
			//Pear::Mail package, see http://pear.php.net/package/Mail
			//this means you must also install php-pear (NOT php5-pear)
			//then just run: sudo pear install --alldeps Mail
			//and you are good to go
		$headers = array(
			'Date' => date("r"), //current date
			"Return-Path" => $from, //use this instead of From:
			"From" => $from,
			"To" => $to,
			"Subject" => $subject //subject is set here 	
			);
		if($ishtml){
			$headers['MIME-Version'] = '1.0';
			$headers['Content-type'] = 'text/html; charset=iso-8859-1';
			$message = "<html><body>\n$message\n</body></html>";
			}
		$mr =& Mail::factory(  Config::get('email.backend'), array('host'=>Config::get('email.host')) );
		$mr -> send( $to, $headers, $message );
		return true;
		};
	
	?>
