<?php

require_once('Status.php');

class HTTPManager {

	/* sends 401 Unauthorized header and closes the connection */
	public static function denyAccess(){
		header('HTTP/1.0 401 Unauthorized');
		return Status::error("Access to this resource is not permitted.");
		}

	/* requires developer to log in to API using Basic HTTP authentication */
	public static function requireBasicAuth($realm="Olin College Student-Run App"){
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			header('WWW-Authenticate: Basic realm="'.addslashes($realm).'"');
			self::denyAccess();
			}
		}

	/* requires developer login via HTTP Basic Auth */
	public static function requireAPILogin(){
		self::requireBasicAuth();
		$u = $_SERVER['PHP_AUTH_USER'];
		$p = $_SERVER['PHP_AUTH_PW'];
		if($u!='test'||$p!='test'){
			self::denyAccess();
			}
		}

	/* requires page to be accessed via HTTPS
	 * if $redirectToSSL is False and SSL is not in use, an error in JSON format will be emitted.
	 * if $redircetToSSL is True and SSL is not in use, the page will be redirected.
	 * IMPORTANT: Redirecting POST requests may not preserve all POST data
	 */
	public static function requireSSL($redirectToSSL=true){
		//TODO: Parameterize if we are in test mode or not
		if(@$_SERVER['HTTPS']=="on"){ return Status::ok(); }
		//build an error message
		if($redirectToSSL){
		   header("HTTP/1.1 307 Temporary Redirect"); //necessary to make browsers re-POST form data
		   header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		   die();
		}else{
			return Status::error("The specified action can only be performed over an SSL-encrypted session (HTTPS)");
			}
		return Status::ok();
		}

	//requires all values in the array to be provided as parameters to the request
	public static function requireParameters($requiredParams, $errorMessage="One or more required parameters were omitted."){
		if(!isset($requiredParams)){ return Status::ok(); } //no required-params
		if(!is_array($requiredParams)){ $requiredParams = array($requiredParams); }
		//find and list all requiredParams that are missing
		$missingParams = array();
		foreach($requiredParams as $param){
			if(!isset($_REQUEST[$param])){
				$missingParams[] = $param; //append to list of missing params
				}
			}
		//if we have all necessary parameters, return and allow the program to continue
		if(count($missingParams)==0){ return Status::ok(); }
		//die, emitting an error message
		return Status::error(array(
			"message" => $errorMessage,
			"requiredParameters" => $requiredParams,
			"omittedParameters" => $missingParams
			));
		}
	}

?>
