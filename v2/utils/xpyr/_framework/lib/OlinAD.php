<?php

	require_once("Status.php");

	class ADTools {
		/*
		 * Microsoft Active Directory stores timestamps as tenths of microseconds elapsed since 1/1/1601
		 * We need to store some specific conversion constants for that.
		 */
		const W2UX = 11644473600; //number of seconds between 1/1/1601 and 1/1/1970

		/*
		 * Converts Active Directory-formatted raw timestamps into UNIX timestamps
		 * AD timestamps are stored as # of tenths of microseconds elapsed since 00:00 GMT on 1/1/1601
		 * UNIX timestamps are stored as # of seconds elapsed since 00:00 GMT on 1/1/1970
		 */
		public static function convertADtoUNIX($adTime){
			$adTime /= 10000000; #convert to # of seconds since MS Active Directory epoch
			$adTime -= self::W2UX; #seconds since UNIX epoch
			return round($adTime);
			}

		/*
		 * Converts UNIX timestamps into Active Directory-formatted timestamps
		 * UNIX timestamps are stored as # of seconds elapsed since 00:00 GMT on 1/1/1970
		 * AD timestamps are stored as # of tenths of microseconds elapsed since 00:00 GMT on 1/1/1601
		 */
		public static function convertUNIXtoAD($unixTime){
			$unixTime += self::W2UX; #seconds since MSAD epoch
			$unixTime *= 10000000; #convert to # of tenths-of-microseconds since MS Active Directory epoch
			return round($unixTime);
			}

		}

	class OlinAD {

		/*
		 * Opens a connection to the specified host,
		 * binds with the specified credentials, and returns the resource.
		 * Returns null if any error occurred.
		 */
		private static function ldap_open($host,$dn,$pw){
			$conn = @ldap_connect($host);
			if(!$conn){ return null; }
			if(ldap_errno($conn)!=0){ return $conn; }
			ldap_set_option($conn,LDAP_OPT_PROTOCOL_VERSION,3); //bind v3 is needed
			ldap_set_option($conn,LDAP_OPT_REFERRALS, 0); //needed for MS AD LDAP
			@ldap_bind($conn,$dn,$pw); //log in as whoever
			return $conn;
			}

		/*
		 * Checks provided credentials on specified host
		 * returns true if login succeeds, false otherwise
		 */
		private static function ldap_login($host,$dn,$pass){
			$conn = self::ldap_open($host,$dn,$pass); //attempt connection
			$accepted = (ldap_errno($conn)==0); //only true if we logged in and bound successfully
			ldap_close($conn); //close connection when done
			return $accepted; //booleanises error condition
			}

		/*
		 * Get max password age, in seconds
		 */
		public static function getMaxPwdAge($user,$pass){
			/* first, attempt to connect to the AD LDAP with the provided credentials */
			$conn = self::ldap_open("olindc01.olin.edu","milkyway\\$user",$pass);
			if(!$conn){ //general failure to connect
				return Status::error(array("errsrc"=>"LDAP Connect"),"An unknown error has occured.");
			}else if(ldap_errno($conn)!=0){ //more specific failure
				return Status::error(array("errsrc"=>"LDAP Connect","errno"=>ldap_errno($conn)),ldap_error($conn));
				}

			/* second, execute a search to find the record for the root of the AD tree (which has the maxpwdage attribute) */
			$baseDN = "DC=olin,DC=edu";
			$filter = "(objectclass=domain)";
			$retrieveFields = array("maxpwdage");
			$sr = @ldap_read($conn, $baseDN, $filter, $retrieveFields);
			if(ldap_errno($conn)!=0){ //more specific failure
				return Status::error(array("errsrc"=>"LDAP Search","errno"=>ldap_errno($conn)),ldap_error($conn));
			}else if(!$sr){ //general failure to connect
				return Status::error(array("errsrc"=>"LDAP Search"),"An unknown error has occured.");
				}

			/* third, retrieve the data */
			$results = ldap_get_entries($conn, $sr);
			if(!$results){ //general failure to connect
				if(ldap_errno($conn)!=0){ //more specific failure
					return Status::error(array("errsrc"=>"LDAP Retrieval","errno"=>ldap_errno($conn)),ldap_error($conn));
				}else{ //general failure to connect
					return Status::error(array("errsrc"=>"LDAP Retrieval"),"An unknown error has occured.");
					}
			}else if(!isset($results["count"])||$results["count"]==0){
				return Status::error(array("errsrc"=>"LDAP Retrieval"),"Could not retrieve information for that user.");
			}else if($results["count"]!=1){
				return Status::error(array("errsrc"=>"LDAP Retrieval"),"Query yielded more than one result, cannot retrieve information for that user");
				}
			$record = $results[0];

			/* finally, validate the data and output it in a friendly format */
			if(!isset($record["maxpwdage"])||$record["maxpwdage"]["count"]==0){
				return Status::error(array("errsrc"=>"LDAP Retrieval"),"Could not retrieve current e-mail address.");
				}
			$maxPwdAge = $record["maxpwdage"][0]; //stored in AD format, must convert
			$maxPwdAge = round(( pow(2,32) - $maxPwdAge ) / 10000000); //convert from AD format into seconds
			return $maxPwdAge;
			}

		/*
		 * Given an Olin AD username and password, this script will attempt to log in,
		 * and obtain UID, first and last name, expected grad year, and email.
		 */
		public static function getADInfo($user,$pass){
			/* first, attempt to connect to the AD LDAP with the provided credentials */
			$conn = self::ldap_open("olindc01.olin.edu","milkyway\\$user",$pass);
			if(!$conn){ //general failure to connect
				return Status::error(array("errsrc"=>"LDAP Connect"),"An unknown error has occured.");
			}else if(ldap_errno($conn)!=0){ //more specific failure
				return Status::error(array("errsrc"=>"LDAP Connect","errno"=>ldap_errno($conn)),ldap_error($conn));
				}

			/* second, execute a search to find the record for the account with the provided UID */
			$baseDN = "OU=People,DC=olin,DC=edu";
			$filter = "(&(&(objectCategory=person)(objectClass=user))(SAMAccountName=$user))";
			$retrieveFields = array("givenname","sn","mail","description","pwdLastSet");
			$sr = @ldap_search($conn, $baseDN, $filter, $retrieveFields);
			if(ldap_errno($conn)!=0){ //more specific failure
				return Status::error(array("errsrc"=>"LDAP Search","errno"=>ldap_errno($conn)),ldap_error($conn));
			}else if(!$sr){ //general failure to connect
				return Status::error(array("errsrc"=>"LDAP Search"),"An unknown error has occured.");
				}

			/* third, retrieve the data */
			$results = ldap_get_entries($conn, $sr);
			if(!$results){ //general failure to connect
				if(ldap_errno($conn)!=0){ //more specific failure
					return Status::error(array("errsrc"=>"LDAP Retrieval","errno"=>ldap_errno($conn)),ldap_error($conn));
				}else{ //general failure to connect
					return Status::error(array("errsrc"=>"LDAP Retrieval"),"An unknown error has occured.");
					}
			}else if(!isset($results["count"])||$results["count"]==0){
				return Status::error(array("errsrc"=>"LDAP Retrieval"),"Could not retrieve information for that user.");
			}else if($results["count"]!=1){
				return Status::error(array("errsrc"=>"LDAP Retrieval"),"Query yielded more than one result, cannot retrieve information for that user");
				}
			$record = $results[0];

			/* finally, validate the data and output it in a friendly format */
			//check first (given) and last (sur-) name
			if(!isset($record["givenname"])||$record["givenname"]["count"]==0||!isset($record['sn'])||$record['sn']["count"]==0){
				#return Status::error(array("errsrc"=>"LDAP Retrieval"),"Could not retrieve first or last name for the user.");
				}
			$firstName = $record["givenname"][0];
			$lastName = $record["sn"][0];
			//next attempt to find expected graduation year
			if(!isset($record["description"])||$record["description"]["count"]==0){
				#return Status::error(array("errsrc"=>"LDAP Retrieval"),"Could not retrieve expected graduation year.");
				}
			try{
				$classof = intval(eregi_replace(".*Class of (....).*","\\1",$record["description"][0]));
			}catch(Exception $e) {
				#return Status::error(array("errsrc"=>"LDAP Retrieval"),"Expected graduation year was not in correct (integer) format");
				}
			//next collect email address
			if(!isset($record["mail"])||$record["mail"]["count"]==0){
				#return Status::error(array("errsrc"=>"LDAP Retrieval"),"Could not retrieve current e-mail address.");
				}
			$email = $record["mail"][0];
			//next collect UNIX timestamp for password-last-expires date
			$pwdExpires = null;
			if(isset($record["pwdlastset"]) && $record["pwdlastset"]["count"]>0){
				$pwdLastSet = ADTools::convertADtoUNIX($record["pwdlastset"][0]);
				$maxPwdAge = self::getMaxPwdAge($user,$pass);
				$pwdExpires = $pwdLastSet + $maxPwdAge;
				}
			//built information array and return it
			$data = array(
				"uid" => $user,
				"firstName" => $firstName,
				"lastName" => $lastName,
				"classOf" => $classof,
				"email" => $email,
				"pwdExpires" => $pwdExpires
				);
			return $data;
			}

		}

	?>
