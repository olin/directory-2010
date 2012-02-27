<?php
class SearchHelperComponent extends Object {
	
	/* The model fields that can be searched.  The keys of this array are the
	 * fields in the model to search; the value for each key is an array
	 * of restriction keywords that apply to that model field.  This means that if
	 * the user specifies a search term with a restriction (e.g. "name:jeff" or "dorm:EH"),
	 * then that search term will only search the related fields and no others.
	 */
	var $searchFields = array(
		'Account.email' 		=> array('email'),
		'Account.firstName' 	=> array('name','firstname'),
		'Account.lastName'		=> array('name','lastname'),
		'UserDetail.nickname' 	=> array('name','nickname'),
		'UserDetail.classYearExpected' => array('year','class'),
		'UserDetail.campusMailbox' => array('mb'),
		'Building.shortName'	=> array('dorm','dormshort'),
		'Building.longName'		=> array('dorm','dormlong'),
		'UserDetail.campusRoom'	=> array('room'),
		'UserDetail.phoneMobile'=> array('phone'),
		'UserDetail.imAOL'		=> array('im'),
		'UserDetail.imGTalk'	=> array('im'),
		'UserDetail.imICQ'		=> array('im'),
		'UserDetail.imMSN'		=> array('im'),
		'UserDetail.imSkype'	=> array('im')
	);
	
	var $apiFieldMapping = array(
		'Account.id'				=> 'uid',
		'Account.email'				=> 'email',
		'Account.firstName'			=> 'name.first',
		'Account.lastName'			=> 'name.last',
		'UserDetail.nickname'		=> 'name.nick',
		'UserDetail.isAway'			=> 'isAway',
		'UserDetail.classYearExpected'	=> 'classOf',
		'UserDetail.campusMailbox'	=> 'campus.mailbox',
		'Building.id'				=> 'campus.dorm.building.id',
		'Building.shortName'		=> 'campus.dorm.building.shortName',
		'Building.longName'			=> 'campus.dorm.building.longName',
		'UserDetail.campusRoom'		=> 'campus.dorm.room',
		'UserDetail.phoneMobile'	=> 'phone.mobile',
		'UserDetail.imAOL'			=> 'im.AOL',
		'UserDetail.imGTalk'		=> 'im.GTalk',
		'UserDetail.imICQ'			=> 'im.ICQ',
		'UserDetail.imMSN'			=> 'im.MSN',
		'UserDetail.imSkype'		=> 'im.Skype'
	);
	
	
	//generates a query that looks for a single keyword across all search fields
	function genKeywordClause($keyword){
		$restr = explode(':',$keyword);
		$restriction = strtolower(trim($restr[0]));
		$query = trim(end($restr));
		if(count($restr)==1){
			$restriction = null;
		}else if(strlen($restriction)==0){
			return null;
		}
		if(strlen($query)==0){
			return null;
		}
		$likes = array();
		foreach($this->searchFields as $field => $restrictions){
			if($restriction!=null && !in_array($restriction,$restrictions)){
				continue;
			}
			$likes["$field LIKE"] = "%$query%";
		}
		if(count($likes)==0){
			return null;
		}
		return array("OR" => $likes);
	}
	
	function buildQuery($query){
		$keywords = preg_split('/\s+/',trim($query));
		$conditions = array("Account.permissions NOT LIKE"=>"%invisible%");
		foreach($keywords as $keyword){
			$cond = $this->genKeywordClause($keyword);
			if($cond==null){ continue; }
			$conditions[] = $cond;
		}
		if(count($conditions)==0){
			return null;
		}
		return $conditions;
	}
	
	//takes a single result like array(Account=>..., UserDetail=>..., Building=>...)
	//and flattens it according to $apiFieldMapping defined above
	function flattenSingleResultAsStructuredArrays($result){
		$flat = array();
		foreach($this->apiFieldMapping as $origKey => $newKey){
			$p = explode(".",$origKey);
			if(count($p)!=2){ continue; }
			$model = $p[0]; $field = $p[1];
			if(!isset($result[$model])
				||!isset($result[$model][$field])
				||$result[$model][$field]==null){
				continue;
			}
			//now go put this new value into output structure
			$ok = explode(".",$newKey);
			$tgt = &$flat;
			for($i=0; $i<count($ok)-1; $i++){
				$k = $ok[$i];
				if(!isset($tgt[$k])){
					$tgt[$k] = array();
				}
				$tgt = &$tgt[$k];
			}
			$k = $ok[count($ok)-1];
			$tgt[$k] = $result[$model][$field];
		}
		return $flat;
	}
	
	//takes a single result like array(Account=>..., UserDetail=>..., Building=>...)
	//and flattens it according to $apiFieldMapping defined above
	//without expanding the output values into a hierarchy
	function flattenSingleResultAs1DArray($result){
		$flat = array();
		foreach($this->apiFieldMapping as $origKey => $newKey){
			$p = explode(".",$origKey);
			if(count($p)!=2){ continue; }
			$model = $p[0]; $field = $p[1];
			if(!isset($result[$model])
				||!isset($result[$model][$field])
				||$result[$model][$field]==null){
				$flat[] = null;
			}else{
				$flat[] = $result[$model][$field];
			}
		}
		return $flat;
	}
	
	//returns the names of the columns in the flattened arrahy
	function get1DArrayKeys(){
		return array_values($this->apiFieldMapping);
	}
	
	//takes a multi-user array(array({Account},{UserDetail},{Building})) and flattens it
	//according to the $apiFieldMapping defined above
	function flattenResultsForAPI($results, $isStructured=true){
		$flat = array();
		if(!$isStructured){
			$flat[] = $this->get1DArrayKeys();
		}
		foreach($results as $result){
			if($isStructured){
				$flat[] = $this->flattenSingleResultAsStructuredArrays($result);
			}else{
				$flat[] = $this->flattenSingleResultAs1DArray($result);
			}
		}
		return $flat;
	}

	//takes in results and decorates each user with extra info
	function postProcessUsers($results){
		foreach($results as &$result){
			if(!isset($result['uid'])){ continue; }
			$uid = $result['uid'];
			if(file_exists("./img/u/$uid.jpg")){
				$result['img'] = "img/u/$uid.jpg";
			}else{
				$result['img'] = "img/u/unknown.jpg";
			}
		}
		return $results;
	}
	
	//takes a single user array({Account},{UserDetail},{Building}) and flattens it
	//according to the $apiFieldMapping defined above
	function flattenUserForAPI($user, $isStructured=true){
		if($isStructured){
			return $this->flattenSingleResultAsStructuredArrays($user);
		}else{
			return $this->flattenSingleResultAs1DArray($user);
		}
	}
	
}
?>
