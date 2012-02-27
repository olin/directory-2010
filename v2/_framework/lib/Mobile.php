<?php

require_once("Status.php");
require_once("DBManager.php");
require_once("Security.php");

//Handles everything that has to do with access-by-email
//including setup & auth
final class MobileEmail {
	
	//creates the user with the given credentials
	public static function requestCode($uid){
		try {
			$existing = self::refreshUnconfirmedCodes($uid);
			if(count($existing)>0){
				return $existing[0];
				}
			
			//get request code
			$code = strtoupper(Security::alphaNumSalt(8));
			
			//build a prepared query
			$query = DBManager::getConnection()->prepare('INSERT INTO mobile(uid,email,status,code,expires) values(:uid,NULL,:status,:code,DATE_ADD(NOW(),INTERVAL 10 MINUTE))');
			//parameterize and execute the query
			//return TRUE or FALSE - was new record added?
			$result = $query->execute(array(
				':uid' => $uid,
				':status' => 'REQUEST',
				':code' => $code
				));
			//var_dump($query->errorInfo());
			return $code;
			//TODO: die on internal error
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
		
	//creates the user with the given credentials
	public static function refreshUnconfirmedCodes($uid){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('UPDATE mobile SET expires=DATE_ADD(NOW(),INTERVAL 10 MINUTE) WHERE uid=:uid AND status=:status');
			//parameterize and execute the query
			$result = $query->execute(array(
				':uid' => $uid,
				':status' => 'REQUEST'
				));
			//build a prepared query
			$query = DBManager::getConnection()->prepare('SELECT code FROM mobile WHERE uid=:uid AND status=:status');
			//parameterize and execute the query
			$result = $query->execute(array(
				':uid' => $uid,
				':status' => 'REQUEST'
				));
			$results = $query->fetchAll();
			$codes = Array();
			foreach($results as $result){
				$codes[] = $result['code'];
				}
			return $codes;
			//TODO: die on internal error
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
		
	//gets validated email addresses belonging to the given UID
	public static function getValidatedAddresses($uid){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('SELECT email FROM mobile WHERE uid=:uid AND status=:status');
			//parameterize and execute the query
			$result = $query->execute(array(
				':uid' => $uid,
				':status' => 'CONFIRMED'
				));
			$results = $query->fetchAll();
			$addrs = Array();
			foreach($results as $result){
				$addrs[] = $result['email'];
				}
			return $addrs;
			//TODO: die on internal error
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
	
	//is the given request code still a valid request code? 
	public static function isRequestExpired($code){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('SELECT uid, expires FROM mobile WHERE code=:code AND status=:status AND expires<NOW()');
			//parameterize and execute the query
			$result = $query->execute(array(
				':code' => $code,
				':status' => 'REQUEST'
				));
			//grab results and return them
			$results = $query->fetchAll();
			return count($results)>0;
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
		
	//is the given request code still a valid request code? 
	public static function isCodeConfirmed($code){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('SELECT email,expires FROM mobile WHERE code=:code AND status=:status');
			//parameterize and execute the query
			$result = $query->execute(array(
				':code' => $code,
				':status' => 'CONFIRMED'
				));
			//grab results and return them
			$results = $query->fetchAll();
			if(count($results)==0){ return false; }
			return $results[0]['email'];
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
		
	//is the given email address a confirmed address? 
	public static function isEmailConfirmed($email){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('SELECT uid FROM mobile WHERE email=:email AND status=:status');
			//parameterize and execute the query
			$result = $query->execute(array(
				':email' => $email,
				':status' => 'CONFIRMED'
				));
			//grab results and return them
			$results = $query->fetchAll();
			return count($results)>0;
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
	
	//Receieved $code from address $email - mark it down
	//as valid in the database if this is indeed a valid code
	public static function validateCode($code,$email){
		try {
			if(self::isRequestExpired($code)){ return; }
			
			//build a prepared query
			$query = DBManager::getConnection()->prepare('UPDATE mobile SET email=:email,status=:status,expires=NULL WHERE code=:code');
			//parameterize and execute the query
			$result = $query->execute(array(
				':code' => $code,
				':email' => $email,
				':status' => 'CONFIRMED'
				));
			
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
		
		
	
	//remove $code for the given UID
	public static function deleteCode($uid,$code){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('DELETE FROM mobile WHERE uid=:uid AND code=:code');
			//parameterize and execute the query
			$result = $query->execute(array(
				':uid' => $uid,
				':code' => $code
				));
			return true;
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
		
	//remove $email for the given UID
	public static function deleteEmail($uid,$email){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('DELETE FROM mobile WHERE uid=:uid AND email=:email');
			//parameterize and execute the query
			$result = $query->execute(array(
				':uid' => $uid,
				':email' => $email
				));
			return true;
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
	
	}

?>