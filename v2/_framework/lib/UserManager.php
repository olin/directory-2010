<?php

require_once("Status.php");
require_once("DBManager.php");

//Handles everything that has to do with user management
//including creation, modification, PW rescue, login, and deletion of users
//Uses PDO to authenticate with a MySQL database
final class UserManager {
	
	//creates the user with the given credentials
	public static function createUser($userInfo){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('INSERT INTO people(uid,name_first,name_last,year_original,year_expected,email) values(:uid,:name_first,:name_last,:year_original,:year_expected,:email)');
			//parameterize and execute the query
			//return TRUE or FALSE - was new record added?
			$result = $query->execute(array(
				':uid' => $userInfo['uid'],
				':name_first' => $userInfo['firstName'],
				':name_last' => $userInfo['lastName'],
				':year_original' => $userInfo['classOf'],
				':year_expected' => $userInfo['classOf'],
				':email' => $userInfo['email']
				));
			//var_dump($query->errorInfo());
			return $result;
			//TODO: die on internal error
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
	
	//retrieves name, email and grad year for the given UID 
	public static function getInformation($uid){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare('SELECT * FROM people WHERE uid=:uid');
			//parameterize and execute the query
			$query->execute(array(':uid' => $uid));
			//grab first row as associative array and return it
			$first = $query->fetch();
			return DataManager::removeNumericFields($first);
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		return false;
		}

	//retrieves name, email and grad year for the given UID 
	public static function getMatches($q){
		try {
			//input processing/validation
			if(is_string($q)){ $q = explode(' ',trim($q)); }
			if($q==null || count($q)==0){ return Status::error("Invalid query specified"); }
			
			//start building query
			$sql = "SELECT * FROM people WHERE ";
			//build AND-joined list of all clauses
			$qp = array();
			for($i=0; $i<count($q); $i++){
				//format word as %word%, which is substring-match in SQL's "LIKE" statement ( % in MySQL LIKE is equivalent to the RegEx .+ )
				$q[$i] = "%".$q[$i]."%";
				//this clause finds the parameterized term "?" in the agglomerated list of fields
				$qp[] = "(CONVERT(CONCAT_WS(' ',uid,name_first,name_last,name_nick,year_original,year_expected,olin_mbox,room_number,away_city,away_state,away_country,home_city,home_state,home_country,phone_number,im_aol,im_gtalk,im_icq,im_msn,im_skype) USING latin1)) LIKE ?";
				}
			$sql .= implode(' AND ',$qp);
			$sql .= ' ORDER BY name_last, name_first, year_expected';
			//bind query and execute
			$query = DBManager::getConnection()->prepare($sql);
			$query->execute($q);
			//grab results and return them
			$results = $query->fetchAll();
			for($i=0; $i<count($results); $i++){
				$results[$i] = DataManager::removeNumericFields($results[$i]);
				}
			return $results;
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		return false;
		}
		
	//returns true if the user exists, false otherwise 
	public static function userExists($uid){
		try {
			//build a prepared query
			$query = DBManager::getConnection()->prepare("SELECT uid FROM people WHERE uid=:uid");
			//parameterize and execute the query
			$query->execute(array(':uid' => $uid));
			//iterate over returned results to find user and log him/her in
			while($row = $query->fetch()){
				return true; //found the user
				}
			//TODO: die on internal error
			return false; //could not find the user
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
	
	//updates the given information in the array
	//$updateArray is associative: key is column in DB; $val is new val in db
	public static function updateInformation($uid,$updateArray){
		try {
			//build a prepared query
			$colsToUpdate = array_keys($updateArray);
			$valsToUpdate = array_values($updateArray);
			$setStrParts = array_keys($updateArray);
			for($i=0; $i<count($setStrParts); $i++){
				$setStrParts[$i] = $setStrParts[$i]."=?";
				}
			$setStr = implode($setStrParts,', ');
			$query = DBManager::getConnection()->prepare('UPDATE people SET '.$setStr.' WHERE uid=?');
			$valsToUpdate[] = $uid; //append UID to prepared query value array
			//parameterize and execute the query
			//return TRUE or FALSE - was new record added?
			$result = $query->execute($valsToUpdate);
			//print("<pre>");var_dump($setStr); print("<br />\n");var_dump($query->errorInfo());
			return $result;
		} catch (PDOException $e) {
			return Status::error($e->getMessage());
			}
		}
		
	}


//Data massaging
final class DataManager {
	
	//removes numeric fields from PDO databasee results
	public static function removeNumericFields($row){
		$rtn = array();
		foreach(array_keys($row) as $field){
			if(!is_string($field)){ continue; } //weed out numeric indices
			$rtn[$field] = $row[$field];
			}
		return $rtn;
		}
	
	//returns true if all $row[$fields] are either NULL or the empty string
	private static function allEmpty($row, $fields){
		foreach($fields as $field){
			if($row[$field]!=NULL and $row[$field]!=''){ //found a non-empty element
				return false;
				}
			}
		return true; //all were empty
		}
	
	//masks fields not meant to be displayed (per user settings)
	public static function maskFields($row){
		$excludes = array();
		
		//convert specific fields to boolean type (for output)
		$boolFields = array('year_isaway','away_hide','home_hide','phone_hide','im_hide');
		foreach($boolFields as $field){
			if(!isset($row[$field])){ continue; }
			$row[$field] = ($row[$field]=='1');
			}
		
		//"hide" incompletely-filled fields
		if(self::allEmpty($row,array('away_street','away_city','away_state','away_zip'))){ $row['away_hide']=true; }
		if(self::allEmpty($row,array('home_street','home_city','home_state','home_zip'))){ $row['home_hide']=true; }
		if(self::allEmpty($row,array('im_aol','im_gtalk','im_icq','im_msn','im_skype'))){ $row['im_hide']=true; }
		if(self::allEmpty($row,array('phone_number'))){ $row['phone_hide']=true; }
		

		//determine which settings should mask which values from being surfaced from the database
		if(!$row['year_isaway']){ $row['away_hide']=true; }
		if($row['year_isaway']){ $excludes = array_merge($excludes,array('olin_mbox','room_bid','room_number')); }
		if($row['away_hide']){ $excludes = array_merge($excludes,array('away_street','away_apt','away_city','away_state','away_zip','away_country')); }
		if($row['home_hide']){ $excludes = array_merge($excludes,array('home_street','home_apt','home_city','home_state','home_zip','home_country')); }
		if($row['phone_hide']){ $excludes = array_merge($excludes,array('phone_number')); }
		if($row['im_hide']){ $excludes = array_merge($excludes,array('im_aol','im_gtalk','im_icq','im_msn','im_skype')); }
		
		//remove excluded values from row
		$excludes = array_unique($excludes);
		foreach($excludes as $attr){
			//unset($row[$attr]);
			$row[$attr]='';
			}
		
		//return sanitized row
		return $row;
		}
	
	}

?>